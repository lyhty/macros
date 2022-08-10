<?php

namespace Lyhty\Macros\Arr;

use Closure;

/**
 * @mixin \Illuminate\Support\Arr
 */
class ZipMacro
{
    public function __invoke(): Closure
    {
        return function (array $array, string $zipper = '.') {
            if (! static::isAssoc($array)) {
                throw new \Exception('Given array must be associative.');
            }

            foreach ($array as $key => &$value) {
                $value = "{$key}{$zipper}{$value}";
            }

            return array_values($array);
        };
    }
}
