<?php

use App\Http\Controllers\api\v1\MainController;
use Illuminate\Support\Facades\Route;

// STATUS
Route::get('/status', [MainController::class, 'status']);

// CATEGORIES
Route::get('/categories', [MainController::class, 'listCategories']);
Route::get('/categories/{id}', [MainController::class, 'getCategoryById']);
Route::post('/categories/create', [MainController::class, 'createCategory']);
Route::put('/categories/{id}/update', [MainController::class, 'updateCategory']);
Route::delete('/categories/{id}/delete', [MainController::class, 'deleteCategory']);

// PRODUCTS
Route::get('/products', [MainController::class, 'listProducts']);
Route::get('/products/{id}', [MainController::class, 'getProductById']);
Route::get('/categories/{id}/products', [MainController::class, 'getProductByCategory']);
Route::post('/products/create', [MainController::class, 'createProducts']);
Route::put('/products/{id}/update', [MainController::class, 'updateProducts']);
Route::delete('/products/{id}/delete', [MainController::class, 'deleteProducts']);

// MOVEMENTS
Route::get('/movements', [MainController::class, 'listMovements']);
Route::get('/movements/ordered/{field}/{direction}', [MainController::class, 'listMovementsOrdered']);
Route::post('/movements/create', [MainController::class, 'createMovements']);
Route::put('/movements/{id}/update', [MainController::class, 'updateMovements']);
Route::delete('/movements/{id}/delete', [MainController::class, 'deleteMovements']);
