<?php

use App\Models\Stock;
use App\Models\Sector;
use App\Models\Portfolio;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Livewire\DividendCalculator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HostelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventTypeController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\FinanceRecordController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\FinanceCategoryController;
use App\Http\Controllers\PortfolioHoldingController;

Auth::routes(['register' => true]);

Route::get('/', function () {
    $response = Http::timeout(10)
                                ->withHeaders(getPsxApiHeaders())
                                ->get("https://dps.psx.com.pk/timeseries/int/DCR");
                            if ($response->successful()) {
                                $data = $response->json();
                                $price = $data['data'][0][1] ?? 0;
                            }
    dd($price);
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('profile', ProfileController::class);
    Route::get('finance-categories/by-type', [FinanceCategoryController::class, 'getCategoriesByType'])->name('finance-categories.by-type');
    Route::get('portfolios/trade', [PortfolioController::class, 'tradeStocks'])->name('portfolios.trade');
    Route::get('portfolios/stats', [PortfolioController::class, 'getStats'])->name('portfolios.stats');
    Route::resource('portfolios', PortfolioController::class);
    Route::resource('portfolio-holdings', PortfolioHoldingController::class);
    Route::resource('finance-categories', FinanceCategoryController::class);
    Route::resource('finance-records', FinanceRecordController::class);
    Route::resource('system-settings', SystemSettingController::class);

});
if (config('auth.socialite_enabled')) {
    Route::controller(SocialiteController::class)->group(function () {
        Route::get('auth/{driver}', 'redirectToSocialiteDriver')->name('auth.login-page');
        Route::get('auth/{driver}/callback', 'handleSocialiteDriverCallback');
    });
}

Route::get('/dividend-calculator', DividendCalculator::class)->name('dividend.calculator');

Route::get('record', function () {
    // return redirect('record/' . Str::random(6));
    return redirect('record/' . random_int(100000, 999999));
});

Route::get('record/{id}', function (string $id) {
    return $id;
});
