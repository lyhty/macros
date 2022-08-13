<?php

namespace Lyhty\Macros\Arr;

use Closure;

/**
 * @mixin \Illuminate\Support\Arr
 */
class JoinMacro
{
    public function __invoke(): Closure
    {
        return function (array $array, string $glue, string $finalGlue = '') {
            if ($finalGlue === '' || count($array) <= 1) {
                return implode($glue, $array);
            }

            $final = array_pop($array);

            return implode($glue, $array).$finalGlue.$final;
        };
    }
}
