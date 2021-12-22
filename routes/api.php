<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CasinoController;


Route::get('/test', function () {
    return "This is api testing";
});

Route::get('countries', [CasinoController::class,'getCountries']);
Route::get('brands', [CasinoController::class,'getBrands']);
Route::get('categories', [CasinoController::class,'getCategories']);
Route::get('games', [CasinoController::class,'getGames']);
