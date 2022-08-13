<?php

namespace Lyhty\Macros\Tests\Unit\CarbonPeriod;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Lyhty\Macros\Tests\Unit\MacroUnitTestCase;

class CollectTest extends MacroUnitTestCase
{
    protected string $class = CarbonPeriod::class;

    protected string $macro = 'collect';

    public function testPeriodCollection()
    {
        $result = CarbonPeriod::between('2022-01-01', '2022-01-15')->collect();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertContainsOnlyInstancesOf(Carbon::class, $result);
    }
}
