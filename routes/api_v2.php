<?php

use App\Http\Controllers\api\v2\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/status', [MainController::class, 'status']);
