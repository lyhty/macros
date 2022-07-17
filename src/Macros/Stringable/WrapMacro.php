<?php

namespace Lyhty\Macros\Macros\Stringable;

use Closure;
use Illuminate\Support\Str;

/**
 * @mixin \Illuminate\Support\Stringable
 */
class WrapMacro
{
    public function __invoke(): Closure
    {
        return function ($start, $finish = null) {
            return new static(Str::wrap($this->value, $start, $finish));
        };
    }
}
