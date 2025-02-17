<?php

use App\Models\Stock;
use App\Models\Sector;
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
use App\Http\Controllers\FinanceCategoryController;
use App\Http\Controllers\PortfolioHoldingController;

Auth::routes();

Route::get('/', function () {
//     $response = Http::get('https://dps.psx.com.pk/symbols');
//         $data = $response->json();
//         $types = array_filter(array_unique(array_column($data, 'sectorName')));
//         $sectorIds = [];
//         foreach ($types as $type) {
//             $sector = Sector::firstOrCreate(['name' => $type]);
//             $sectorIds[$type] = $sector->id;
//         }
// // dd("sectorIds",$sectorIds);
//         foreach ($data as $stock) {
//             if (!empty($stock['symbol']) && !empty($stock['sectorName']) && isset($sectorIds[$stock['sectorName']])) {
//                 Stock::firstOrCreate([
//                     'sector_id' => $sectorIds[$stock['sectorName']],
//                     'symbol' => $stock['symbol'],
//                     'name' => $stock['name'],
//                 ]);
//             }
//         }
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('profile', ProfileController::class);
    Route::get('finance-categories/by-type', [FinanceCategoryController::class, 'getCategoriesByType'])->name('finance-categories.by-type');
    Route::get('portfolios/trade', [PortfolioController::class, 'tradeStocks'])->name('portfolios.trade');
    Route::resource('portfolios', PortfolioController::class);
    Route::resource('portfolio-holdings', PortfolioHoldingController::class);
    Route::resource('finance-categories', FinanceCategoryController::class);
    Route::resource('finance-records', FinanceRecordController::class);

});

Route::controller(SocialiteController::class)->group(function () {
    Route::get('auth/{driver}', 'redirectToSocialiteDriver')->name('auth.login-page');
    Route::get('auth/{driver}/callback', 'handleSocialiteDriverCallback');
});
