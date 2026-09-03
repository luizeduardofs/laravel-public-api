<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MainController extends Controller
{
  public function status(): JsonResponse
  {
    return response()->json([
      'status' => 'API v1 is running'
    ]);
  }
}
