<?php

namespace Lyhty\Macros\Arr;

use Closure;

/**
 * @mixin \Illuminate\Support\Arr
 */
class AssociateMacro
{
    public function __invoke(): Closure
    {
        return function (array $array, $fill = null) {
            $transformed = [];

            foreach ($array as $key => $value) {
                // e.g. "foo" => "bar"
                if (is_string($key)) {
                    static::set($transformed, $key, $value);
                }
                // e.g. 1 => "gar"
                else {
                    [$key, $index] = [$value, $key];
                    $key = value($key, $index);
                    static::set($transformed, $key, value($fill, $key, $index));
                }
            }

            return $transformed;
        };
    }
}
