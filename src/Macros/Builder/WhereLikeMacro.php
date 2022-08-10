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
        $class = static::class;

        return function ($attributes, string $searchTerm, string $pattern = 'both', bool $or = false) use ($class) {
            return $this->{! $or ? 'where' : 'orWhere'}(
                fn (Builder $query) => $class::scope(
                    $query,
                    $class::format($attributes),
                    $class::formatSearch($searchTerm, $pattern)
                )
            );
        };
    }

    public static function formatSearch($searchTerm, $pattern): string
    {
        $searchTerm = trim($searchTerm, '%');

        switch ($pattern) {
            case 'l':
            case 'left': return "%{$searchTerm}";
            case 'r':
            case 'right': return "{$searchTerm}%";
            default: return "%{$searchTerm}%";
        }
    }

    public static function format($attributes): array
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

    public static function scope(Builder $query, array $values, $searchTerm)
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
