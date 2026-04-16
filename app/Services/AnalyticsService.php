<?php

namespace App\Services;

use App\Models\SearchKeyword;
use App\Models\TrafficVisit;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AnalyticsService
{
    private static ?bool $trafficVisitsTableExists = null;

    private static ?bool $searchKeywordsTableExists = null;

    public static function trackVisit(Request $request): void
    {
        if ($request->method() !== 'GET') {
            return;
        }

        if ($request->expectsJson()) {
            return;
        }

        $path = trim($request->path(), '/');
        if (str_starts_with($path, 'admin') || str_starts_with($path, 'storage')) {
            return;
        }

        if (! self::tableExists('traffic_visits')) {
            return;
        }

        $refererHost = parse_url((string) $request->headers->get('referer'), PHP_URL_HOST);
        $source = self::classifySource($refererHost ? (string) $refererHost : null);

        try {
            TrafficVisit::create([
                'source' => $source,
                'referer_host' => $refererHost ?: null,
                'path' => '/' . $path,
            ]);
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    public static function trackSearch(?string $query): void
    {
        $keyword = mb_strtolower(trim((string) $query));
        if ($keyword === '' || mb_strlen($keyword) < 2) {
            return;
        }

        if (! self::tableExists('search_keywords')) {
            return;
        }

        try {
            $record = SearchKeyword::firstOrNew(['keyword' => $keyword]);
            $record->count = (int) $record->count + 1;
            $record->last_searched_at = now();
            $record->save();
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    private static function tableExists(string $table): bool
    {
        $property = $table === 'traffic_visits'
            ? 'trafficVisitsTableExists'
            : 'searchKeywordsTableExists';

        if (self::${$property} !== null) {
            return self::${$property};
        }

        try {
            return self::${$property} = Schema::hasTable($table);
        } catch (QueryException|Throwable $exception) {
            report($exception);

            return self::${$property} = false;
        }
    }

    private static function classifySource(?string $host): string
    {
        if ($host === null || $host === '') {
            return 'direct';
        }

        $host = mb_strtolower($host);

        foreach (['google.', 'bing.', 'yahoo.', 'duckduckgo.'] as $searchEngine) {
            if (str_contains($host, $searchEngine)) {
                return 'search';
            }
        }

        foreach (['facebook.com', 'instagram.com', 'tiktok.com', 'twitter.com', 'x.com', 't.co', 'linkedin.com'] as $social) {
            if (str_contains($host, $social)) {
                return 'social';
            }
        }

        return 'referral';
    }
}
