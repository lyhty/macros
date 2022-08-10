<?php

namespace Lyhty\Macros\Builder;

use Closure;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class WhereLikeOrMacro
{
    public function __invoke(): Closure
    {
        return function ($attributes, string $searchTerm, $pattern = 'both') {
            return $this->whereLike($attributes, $searchTerm, $pattern, true);
        };
    }
}
