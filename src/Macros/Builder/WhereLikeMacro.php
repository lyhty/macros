<?php

namespace Lyhty\Macros\Macros\Builder;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @link https://freek.dev/1182-searching-models-using-a-where-like-query-in-laravel
 */
class WhereLikeMacro
{
    public function __invoke(): Closure
    {
        return function ($attributes, string $searchTerm, string $pattern = 'both', bool $or = false) {
            $patterns = [
                'left' => fn ($search) => "%{$search}",
                'right' => fn ($search) => "{$search}%",
                'both' => fn ($search) => "%{$search}%",
            ];

            $this->{! $or ? 'where' : 'orWhere'}(function (Builder $query) use ($attributes, $searchTerm, $pattern, $patterns) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        is_string($attribute) && Str::contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm, $pattern, $patterns) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas($relationName,
                                function (Builder $query) use ($relationAttribute, $searchTerm, $pattern, $patterns) {
                                    $query->where($relationAttribute, 'LIKE', $patterns[$pattern]($searchTerm));
                                }
                            );
                        },
                        function (Builder $query) use ($attribute, $searchTerm, $pattern, $patterns) {
                            $query->orWhere($attribute, 'LIKE', $patterns[$pattern]($searchTerm));
                        }
                    );
                }
            });

            return $this;
        };
    }
}
