<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
  public function register(): void
  {
    //
  }

  public function boot(): void
  {
    // Rate limiter  config
    RateLimiter::for('api', function (Request $request) {
      $rateLimit = config('app.rate_limit', 0);

      if ($rateLimit > 0) {
        return Limit::perMinute($rateLimit)->by($request->ip());
      } else {
        return Limit::none();
      }
    });
  }
}
