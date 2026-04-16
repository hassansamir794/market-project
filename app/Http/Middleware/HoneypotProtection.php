<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HoneypotProtection
{
    public function handle(Request $request, Closure $next): Response
    {
        $honeypotField = (string) config('security.honeypot_field', 'company');
        $timestampField = (string) config('security.honeypot_timestamp_field', 'form_started_at');
        $minimumSeconds = max(0, (int) config('security.minimum_form_fill_seconds', 2));

        if ($request->filled($honeypotField)) {
            abort(422);
        }

        $startedAt = (int) $request->input($timestampField);
        if ($startedAt <= 0) {
            abort(422);
        }

        $submittedTooFast = (time() - $startedAt) < $minimumSeconds;
        if ($submittedTooFast) {
            abort(422);
        }

        return $next($request);
    }
}
