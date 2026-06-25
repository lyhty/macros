<?php

namespace Lyhty\Macros\Builder;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Lyhty\Macros\SearchPattern;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @link https://freek.dev/1182-searching-models-using-a-where-like-query-in-laravel
 */
class SearchLikeMacro
{
    public function __invoke(): Closure
    {
        $class = static::class;

        return function (
            array|string $attributes,
            string $searchTerm,
            SearchPattern|string|null $pattern = SearchPattern::Both,
            bool $or = false
        ) use ($class) {
            $pattern = is_null($pattern)
                ? (str_contains($searchTerm, '%') ? SearchPattern::Custom : SearchPattern::Both)
                : SearchPattern::from($pattern);

            return $this->where(
                column: fn (Builder $query) => $class::scope(
                    $query,
                    $class::format($attributes),
                    $pattern->format($searchTerm)
                ),
                boolean: $or ? 'or' : 'and'
            );
        };
    }

    private static function format(array|string $attributes): array
    {
        $attrs = ['_attrs' => []];

        foreach (Arr::wrap($attributes) as $attribute) {
            if (Str::contains($attribute, '.')) {
                [$relation, $attr] = Str::explodeReverse($attribute, '.', 2);
                $attrs["$relation._attrs"][] = $attr;
            } else {
                $attrs['_attrs'][] = $attribute;
            }
        }

        return Arr::undot($attrs);
    }

    private static function scope(Builder $query, array $values, string $searchTerm)
    {
        foreach ($values['_attrs'] ?? [] as $index => $attribute) {
            $model = $query->getModel();
            $query->orWhere($model->qualifyColumn($attribute), 'LIKE', $searchTerm);
        }

        foreach (Arr::except($values, ['_attrs']) as $relationship => $sub) {
            $query->orWhereHas($relationship, fn (Builder $query) => $query->where(
                fn (Builder $query) => static::scope($query, $sub, $searchTerm)
            ));
        }
    }
}
