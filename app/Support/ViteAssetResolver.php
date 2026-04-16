<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

class ViteAssetResolver
{
    private static ?string $hotUrl = null;

    private static ?array $manifest = null;

    public function tags(array $entries): HtmlString
    {
        $tags = $this->hotTags($entries) ?? $this->buildTags($entries);

        return new HtmlString(implode("\n", $tags));
    }

    private function hotTags(array $entries): ?array
    {
        $hotUrl = $this->hotUrl();
        if ($hotUrl === null) {
            return null;
        }

        $tags = [
            '<script type="module" src="' . e($hotUrl . '/@vite/client') . '"></script>',
        ];

        foreach ($entries as $entry) {
            $assetUrl = $hotUrl . '/' . ltrim($entry, '/');

            if (str_ends_with($entry, '.css')) {
                $tags[] = '<link rel="stylesheet" href="' . e($assetUrl) . '" />';
                continue;
            }

            $tags[] = '<script type="module" src="' . e($assetUrl) . '"></script>';
        }

        return $tags;
    }

    private function buildTags(array $entries): array
    {
        $manifest = $this->manifest();
        $tags = [];

        foreach ($entries as $entry) {
            if (! isset($manifest[$entry]['file'])) {
                continue;
            }

            foreach ($manifest[$entry]['css'] ?? [] as $cssFile) {
                $tags[] = '<link rel="stylesheet" href="' . e(asset('build/' . $cssFile)) . '" />';
            }

            $file = $manifest[$entry]['file'];
            $assetUrl = asset('build/' . $file);

            if (str_ends_with($entry, '.css')) {
                $tags[] = '<link rel="stylesheet" href="' . e($assetUrl) . '" />';
                continue;
            }

            $tags[] = '<script type="module" src="' . e($assetUrl) . '"></script>';
        }

        return $tags;
    }

    private function hotUrl(): ?string
    {
        if (! filter_var(env('VITE_USE_DEV_SERVER', false), FILTER_VALIDATE_BOOL)) {
            return self::$hotUrl = null;
        }

        if (self::$hotUrl !== null) {
            return self::$hotUrl;
        }

        $hotFile = public_path('hot');
        if (! is_file($hotFile)) {
            return self::$hotUrl = null;
        }

        $url = trim((string) file_get_contents($hotFile));
        if ($url === '') {
            return self::$hotUrl = null;
        }

        $parts = parse_url($url);
        if (! is_array($parts) || empty($parts['host'])) {
            return self::$hotUrl = null;
        }

        $port = $parts['port'] ?? (($parts['scheme'] ?? 'http') === 'https' ? 443 : 80);
        $connection = @fsockopen($parts['host'], $port, $errno, $errorMessage, 0.25);

        if (! is_resource($connection)) {
            return self::$hotUrl = null;
        }

        fclose($connection);

        return self::$hotUrl = rtrim($url, '/');
    }

    private function manifest(): array
    {
        if (self::$manifest !== null) {
            return self::$manifest;
        }

        $manifestPath = public_path('build/manifest.json');
        if (! is_file($manifestPath)) {
            return self::$manifest = [];
        }

        $manifest = json_decode((string) file_get_contents($manifestPath), true);

        return self::$manifest = is_array($manifest) ? $manifest : [];
    }
}
