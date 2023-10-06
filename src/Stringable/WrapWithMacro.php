<?php

namespace Lyhty\Macros\Stringable;

use Closure;
use Illuminate\Support\Str;

/**
 * @mixin \Illuminate\Support\Stringable
 */
class WrapWithMacro
{
    public function __invoke(): Closure
    {
        return function ($start, $finish = null) {
            return new static(Str::wrapWith($this->value, $start, $finish));
        };
    }
}
