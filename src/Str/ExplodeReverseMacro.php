<?php

namespace Lyhty\Macros\Str;

use Closure;

/**
 * @mixin \Illuminate\Support\Str
 */
class ExplodeReverseMacro
{
    public function __invoke(): Closure
    {
        return function (string $string, string $separator, int $limit = PHP_INT_MAX) {
            return collect(explode($separator, static::reverse($string), $limit))
                ->map(fn ($value) => static::reverse($value))
                ->sortKeysDesc()
                ->values();
        };
    }
}
