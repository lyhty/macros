<?php

namespace Lyhty\Macros\Macros\Builder;

use Closure;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class SelectKeyMacro
{
    public function __invoke(): Closure
    {
        return fn () => $this->addSelect($this->getModel()->getKeyName());
    }
}
