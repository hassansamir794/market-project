<?php

namespace App\Http\Middleware;

use App\Services\AnalyticsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackTrafficSource
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        AnalyticsService::trackVisit($request);

        return $response;
    }
}

