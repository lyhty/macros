<?php

namespace Lyhty\Macros\Arr;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

/**
 * @mixin \Illuminate\Support\Arr
 */
class ImplodeMacro
{
    public function __invoke(): Closure
    {
        return function ($array, $separator = ''): Stringable {
            return Str::of(implode($separator, $array));
        };
    }
}
