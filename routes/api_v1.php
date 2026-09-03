<?php

use App\Http\Controllers\api\v1\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/status', [MainController::class, 'status']);
