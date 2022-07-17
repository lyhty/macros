<?php

namespace Lyhty\Macros\Macros\Arr;

use Closure;

/**
 * @mixin \Illuminate\Support\Arr
 */
class JoinMacro
{
    public function __invoke(): Closure
    {
        return fn (array $array, string $glue, string $finalGlue = '') => (
            collect($array)->join($glue, $finalGlue)
        );
    }
}
