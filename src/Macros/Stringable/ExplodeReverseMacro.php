<?php

namespace Lyhty\Macros\Macros\Stringable;

use Closure;
use Illuminate\Support\Str;

/**
 * @mixin \Illuminate\Support\Stringable
 */
class ExplodeReverseMacro
{
    public function __invoke(): Closure
    {
        return function (string $delimiter, int $limit = PHP_INT_MAX) {
            return Str::explodeReverse($this->value, $delimiter, $limit);
        };
    }
}
