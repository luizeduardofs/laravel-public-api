<?php

use App\Http\Controllers\api\v1\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/status', [MainController::class, 'status']);

Route::get('/categories', [MainController::class, 'listCategories']);
Route::get('/categories/{id}', [MainController::class, 'getCategoryById']);

Route::get('/products', [MainController::class, 'listProducts']);
Route::get('/products/{id}', [MainController::class, 'getProductById']);

Route::get('/movements', [MainController::class, 'listMovements']);
