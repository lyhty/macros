<?php

namespace Lyhty\Macros\Collection;

use Closure;

/**
 * @mixin \Illuminate\Support\Collection
 */
class MergeManyMacro
{
    public function __invoke(): Closure
    {
        return function (...$items) {
            $merged = [];

            foreach ($items as &$item) {
                $merged = array_merge($merged, $this->getArrayableItems($item));
            }

            return $this->merge($merged);
        };
    }
}
