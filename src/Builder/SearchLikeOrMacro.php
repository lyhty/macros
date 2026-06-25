<?php

namespace Lyhty\Macros\Builder;

use Closure;
use Lyhty\Macros\SearchPattern;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class SearchLikeOrMacro
{
    public function __invoke(): Closure
    {
        return function (
            array|string $attributes,
            string $searchTerm,
            SearchPattern|string|null $pattern = SearchPattern::Both
        ) {
            return $this->searchLike($attributes, $searchTerm, $pattern, true);
        };
    }
}
