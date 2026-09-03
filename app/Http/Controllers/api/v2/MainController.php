<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MainController extends Controller
{
  public function status(): JsonResponse
  {
    return response()->json([
      'status' => 'API v2 is running'
    ]);
  }
}
