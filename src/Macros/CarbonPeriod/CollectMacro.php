<?php

namespace Lyhty\Macros\Macros\CarbonPeriod;

use Closure;

/**
 * @mixin \Carbon\CarbonPeriod
 */
class CollectMacro
{
    public function __invoke(): Closure
    {
        return fn () => collect($this->toArray());
    }
}
