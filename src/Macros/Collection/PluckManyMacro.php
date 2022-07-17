<?php

namespace Lyhty\Macros\Macros\Collection;

use Closure;

/**
 * @mixin \Illuminate\Support\Collection
 */
class PluckManyMacro
{
    public function __invoke(): Closure
    {
        return function ($attr) {
            $attrs = is_string($attr) ? func_get_args() : $attr;

            return $this->map->only($attrs);
        };
    }
}
