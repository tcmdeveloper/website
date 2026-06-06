<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class UsernameGenerator
{
    /**
     * Generate an available username based on a full name.
     */
    public function generate(string $name): string
    {
        $base = $this->normalize($name);

        $candidates = $this->initialCandidates($base);

        foreach ($candidates as $username) {
            if ($this->isAvailable($username)) {
                return $username;
            }
        }

        return $this->generateFallback($base);
    }

    /**
     * Normalize name into a clean username base.
     */
    protected function normalize(string $name): string
    {
        $base = Str::of($name)
            ->lower()
            ->ascii()
            ->replaceMatches('/[^a-z0-9]+/', '')
            ->trim()
            ->toString();

        return $base !== '' ? $base : 'user';
    }

    /**
     * First set of username guesses.
     */
    protected function initialCandidates(string $base): array
    {
        return [
            $base,
            $base . rand(1, 99),
            $base . rand(100, 999),
        ];
    }

    /**
     * Check if username is available.
     */
    protected function isAvailable(string $username): bool
    {
        return !User::where('username', $username)->exists();
    }

    /**
     * Last-resort fallback loop.
     */
    protected function generateFallback(string $base): string
    {
        $i = 1;

        do {
            $username = $base . $i;
            $i++;
        } while (! $this->isAvailable($username));

        return $username;
    }
}