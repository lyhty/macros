<?php

namespace Lyhty\Macros\Builder;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

/**
 * @mixin \Illuminate\Database\Query\Builder
 */
class SelectRawArrMacro
{
    public function __invoke(): Closure
    {
        return function ($select) {
            $bindings = [];

            if ($select instanceof Arrayable) {
                $select = $select->toArray();
            }

            foreach ($select as $index => $row) {
                if (is_array($row) && count($row) > 1) {
                    $bindings = array_merge($bindings, Arr::wrap($row[1]));
                    $select[$index] = $row[0];
                }
            }

            return $this->selectRaw(implode(', ', $select), $bindings);
        };
    }
}
