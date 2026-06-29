<?php

namespace App\Services;

use Illuminate\Support\Str;

class YoutubeService
{
    public function id(string $url): ?string
    {
        $parts = parse_url($url);

        if (!isset($parts['host'])) {
            return null;
        }

        $host = Str::lower($parts['host']);

        if ($host === 'youtu.be') {
            return trim($parts['path'] ?? '', '/');
        }

        if (!empty($parts['query'])) {
            parse_str($parts['query'], $query);

            if (!empty($query['v'])) {
                return $query['v'];
            }
        }

        if (!empty($parts['path']) && preg_match('#^/(shorts|live|embed)/([^/]+)#', $parts['path'], $m)) {
            return $m[2];
        }

        return null;
    }
}