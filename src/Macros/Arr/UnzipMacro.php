<?php

namespace Lyhty\Macros\Macros\Arr;

use Closure;

/**
 * @mixin \Illuminate\Support\Arr
 */
class UnzipMacro
{
    public function __invoke(): Closure
    {
        return function (array $array, string $zipper) {
            foreach ($array as $key => $attribute) {
                [$newKey, $newAttr] = explode($zipper, $attribute, 2);
                $array[$newKey] = $newAttr ?: null;
                unset($array[$key]);
            }

            return $array;
        };
    }
}
