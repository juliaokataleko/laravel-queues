<?php
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('upload', [SaleController::class, 'index']);
Route::post('upload', [SaleController::class, 'upload']);
Route::get('batch', [SaleController::class, 'batch']);
