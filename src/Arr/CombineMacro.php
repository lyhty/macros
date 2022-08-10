<?php

namespace Lyhty\Macros\Arr;

use Closure;

/**
 * @mixin \Illuminate\Support\Arr
 */
class CombineMacro
{
    public function __invoke(): Closure
    {
        return function (array $keys, array $values) {
            return array_combine($keys, array_pad($values, count($keys), null));
        };
    }
}
