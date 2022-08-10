<?php

namespace Lyhty\Macros\Collection;

use Closure;

/**
 * @mixin \Illuminate\Support\Collection
 */
class WhereImplementsMacro
{
    public function __invoke(): Closure
    {
        return function ($interface) {
            return $this->filter(fn ($class) => (
                ((is_string($class) && class_exists($class)) || is_object($class))
                    && class_implements_interface($class, $interface)
            ));
        };
    }
}
