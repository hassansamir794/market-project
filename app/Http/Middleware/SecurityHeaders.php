<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Add baseline security headers to every response.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '0');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-site');
        $response->headers->set('Content-Security-Policy', $this->buildCsp($request));

        if (app()->environment('production') && $request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }

    private function buildCsp(Request $request): string
    {
        $isLocal = app()->environment('local');
        $scriptSrc = ["'self'", "'unsafe-inline'"];
        $styleSrc = ["'self'", "'unsafe-inline'"];
        $connectSrc = $isLocal ? ["'self'", 'https:', 'http:'] : ["'self'", 'https:'];
        $viteSources = $this->viteSources($isLocal);

        foreach ($viteSources as $viteSource) {
            $scriptSrc[] = $viteSource;
            $styleSrc[] = $viteSource;
            $connectSrc[] = $viteSource;
        }

        if ($isLocal) {
            $connectSrc[] = 'ws:';
            $connectSrc[] = 'wss:';
        }

        $directives = [
            "default-src 'self'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
            "object-src 'none'",
            'img-src ' . implode(' ', ["'self'", 'data:', 'blob:', 'https:']),
            'font-src ' . implode(' ', ["'self'", 'data:', 'https:']),
            'media-src ' . implode(' ', ["'self'", 'blob:', 'https:']),
            'script-src ' . implode(' ', $scriptSrc),
            'style-src ' . implode(' ', $styleSrc),
            'connect-src ' . implode(' ', $connectSrc),
            'frame-src ' . implode(' ', ["'self'", 'https://www.google.com', 'https://maps.google.com']),
        ];

        if (app()->environment('production') && $request->isSecure()) {
            $directives[] = 'upgrade-insecure-requests';
        }

        return implode('; ', $directives);
    }

    private function viteSources(bool $isLocal): array
    {
        if (! $isLocal) {
            return [];
        }

        $port = (int) env('VITE_PORT', 5173);
        if ($port <= 0) {
            $port = 5173;
        }

        $host = (string) env('VITE_HMR_HOST', '');
        if ($host === '') {
            $host = 'localhost';
            $appUrlHost = parse_url((string) config('app.url'), PHP_URL_HOST);
            if (is_string($appUrlHost) && $appUrlHost !== '') {
                $host = $appUrlHost;
            }
        }

        $host = trim($host);
        if ($host === '') {
            return [];
        }

        return [
            'http://' . $host . ':' . $port,
            'https://' . $host . ':' . $port,
            'ws://' . $host . ':' . $port,
            'wss://' . $host . ':' . $port,
        ];
    }
}
