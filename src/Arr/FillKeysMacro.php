<?php

namespace Lyhty\Macros\Arr;

use Closure;

/**
 * @mixin \Illuminate\Support\Arr
 */
class FillKeysMacro
{
    public function __invoke(): Closure
    {
        return function (array $array, $keys, $value = null, bool $onlyExisting = false) {
            $keys = static::wrap($keys);

            return array_replace($array, array_fill_keys(
                $onlyExisting ? array_intersect($keys, array_keys($array)) : $keys,
                $value
            ));
        };
    }
}
