<?php

namespace Lyhty\Macros;

enum SearchPattern
{
    case Left;
    case Right;
    case Both;
    case Custom;

    public function format(string $searchTerm) {
        $trimmedSearchTerm = trim($searchTerm, '%');

        return match ($this) {
            static::Left => "%{$trimmedSearchTerm}",
            static::Right => "{$trimmedSearchTerm}%",
            static::Both => "%{$trimmedSearchTerm}%",
            default => $searchTerm
        };
    }

    public static function from(string|self $value)
    {
        if (! is_string($value)) return $value;

        return match ($value) {
            'l', 'left' => self::Left,
            'r', 'right' => self::Right,
            default => self::Both,
        };
    }
}
