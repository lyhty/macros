<?php

namespace Lyhty\Macros\Macros\Collection;

use Closure;

/**
 * @mixin \Illuminate\Support\Collection
 */
class WhereExtendsMacro
{
    public function __invoke(): Closure
    {
        return function ($parent) {
            return $this->filter(fn ($class) => (
                ((is_string($class) && class_exists($class)) || is_object($class))
                && class_extends($class, $parent)
            ));
        };
    }
}
