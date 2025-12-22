<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Models\Portfolio;
use App\Models\SystemSettings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class FetchStockPricesAtClose extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:fetch-closing-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and save stock closing prices at market close (only for stocks in portfolio holdings)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking market status...');

        // Get market opening and closing times
        $marketOpeningTime = SystemSettings::get('market_opening_time', '09:00');
        $marketClosingTime = SystemSettings::get('market_closing_time', '17:00');
        
        $currentTime = now()->format('H:i');
        $currentDay = now()->dayOfWeek; // 0 = Sunday, 6 = Saturday
        $today = now()->format('Y-m-d');

        // Check if it's a weekend
        if ($currentDay == 0 || $currentDay == 6) {
            $this->info('Market is closed (Weekend). No action taken.');
            return Command::SUCCESS;
        }

        // Check if market is currently open
        if ($currentTime >= $marketOpeningTime && $currentTime < $marketClosingTime) {
            $this->info('Market is currently open. No action taken.');
            return Command::SUCCESS;
        }

        // Check if it's at or past market closing time
        if ($currentTime >= $marketClosingTime) {
            // Check if we've already updated prices today
            $alreadyUpdated = Stock::whereDate('price_updated_at', $today)
                ->whereNotNull('closing_price')
                ->exists();

            if ($alreadyUpdated) {
                $this->info('Stock prices have already been updated today. Skipping...');
                return Command::SUCCESS;
            }

            $this->info('Market is closed. Fetching closing prices for stocks in portfolio holdings...');
            
            // Get unique stocks that are in portfolio holdings
            $portfolios = Portfolio::with('holdings:id,portfolio_id,stock_id', 'holdings.stock:id,symbol')->get(['id']);
            $stocksInHoldings = $portfolios->flatMap->holdings->pluck('stock')->unique('id');
            $updatedCount = 0;
            $failedCount = 0;

            $this->info("Processing {$stocksInHoldings->count()} stocks from portfolio holdings...");

            foreach ($stocksInHoldings as $stock) {
                try {
                    $response = Http::timeout(10)->get("https://dps.psx.com.pk/timeseries/int/{$stock->symbol}");
                    
                    if ($response->successful()) {
                        $data = $response->json();
                        $closingPrice = $data['data'][0][1] ?? null;
                        
                        if ($closingPrice !== null) {
                            $stock->update([
                                'closing_price' => $closingPrice,
                                'price_updated_at' => now()
                            ]);
                            $updatedCount++;
                        } else {
                            $this->warn("No price data found for {$stock->symbol}");
                            $failedCount++;
                        }
                    } else {
                        $this->warn("Failed to fetch price for {$stock->symbol}. Status: {$response->status()}");
                        $failedCount++;
                    }
                } catch (\Exception $e) {
                    Log::error("Error fetching price for {$stock->symbol}: " . $e->getMessage());
                    $this->error("Error fetching price for {$stock->symbol}: " . $e->getMessage());
                    $failedCount++;
                }

                // Small delay to avoid rate limiting
                usleep(100000); // 0.1 second delay
            }

            $this->info("Completed! Updated: {$updatedCount}, Failed: {$failedCount}");
            return Command::SUCCESS;
        }

        $this->info('Market is closed but not at closing time. No action taken.');
        return Command::SUCCESS;
    }
}

