<?php

namespace Lyhty\Macros\Macros\Collection;

use Closure;

/**
 * @mixin \Illuminate\Support\Collection
 */
class WhereUsesMacro
{
    public function __invoke(): Closure
    {
        return function (string $traitName, bool $recursive = true) {
            return $this->filter(function ($class) use ($traitName, $recursive) {
                return ((is_string($class) && class_exists($class)) || is_object($class))
                    && class_uses_trait($class, $traitName, $recursive);
            });
        };
    }
}
