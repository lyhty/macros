<?php

namespace Lyhty\Macros\Str;

use Closure;

/**
 * @mixin \Illuminate\Support\Str
 */
class WrapWithMacro
{
    public function __invoke(): Closure
    {
        return function ($string, $start, $finish = null): string {
            return static::finish(static::start($string, $start), $finish ?? $start);
        };
    }
}
