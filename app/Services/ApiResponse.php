<?php

namespace App\Services;

class ApiResponse
{
  public static function success(array $data, string $message = 'Success', int $code = 200)
  {
    return response()->json([
      'status' => $code,
      'message' => $message,
      'data' => $data
    ], $code);
  }

  public static function error(string $message = 'Error', int $code = 400, array $errors = [])
  {
    return response()->json([
      'status' => $code,
      'message' => $message,
      'errors' => $errors,
    ], $code);
  }
}
