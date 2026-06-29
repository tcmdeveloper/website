<?php

// -----------------------------------------------------
// SPLIT GOOGLE NAME
// -----------------------------------------------------

if (! function_exists('split_google_name')) {

    function split_google_name(string $name): array
    {
        $parts = explode(' ', trim($name));

        return [
            'first_name' => $parts[0] ?? '',
            'last_name' => count($parts) > 1
                ? implode(' ', array_slice($parts, 1))
                : '',
        ];
    }

}