<?php

namespace Lyhty\Macros\Macros\Str;

use Closure;

/**
 * @mixin \Illuminate\Support\Str
 */
class WrapMacro
{
    public function __invoke(): Closure
    {
        return function ($string, $start, $finish = null): string {
            return static::finish(static::start($string, $start), $finish ?? $start);
        };
    }
}
