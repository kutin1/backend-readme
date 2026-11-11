<?php

if (!function_exists('highlight')) {
    function highlight(?string $text, ?string $query): string
    {
        if ($text === null || $query === null || $query === '') {
            return (string) $text;
        }

        $words = array_filter(explode(' ', $query));

        foreach ($words as $word) {
            $word = preg_quote($word, '/');

            $text = preg_replace(
                "/($word)/iu",
                '<mark>$1</mark>',
                $text
            );
        }

        return $text;
    }
}
