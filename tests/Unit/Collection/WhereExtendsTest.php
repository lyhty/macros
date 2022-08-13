<?php

namespace Lyhty\Macros\Tests\Unit\Collection;

use Illuminate\Support\Collection;
use JsonException;
use Lyhty\Macros\Tests\Unit\MacroUnitTestCase;
use OverflowException;
use RangeException;
use RuntimeException;

class WhereExtendsTest extends MacroUnitTestCase
{
    protected string $class = Collection::class;

    protected string $macro = 'whereExtends';

    public function testWhereInstancesExtendDefinedClass(): void
    {
        $collection = collect([
            new OverflowException,
            new RuntimeException,
            new JsonException,
            new RangeException
        ]);

        $this->assertContainsOnlyInstancesOf(
            RuntimeException::class,
            $this->callMacro($collection, RuntimeException::class)
        );
    }

    public function testWhereClassnamesExtendDefinedClass(): void
    {
        $collection = collect([OverflowException::class, RuntimeException::class, JsonException::class]);
        $result = $this->callMacro($collection, RuntimeException::class);

        $this->assertCount(1, $result);
        $this->assertSame(OverflowException::class, $result->first());
    }

    public function testNonClasses(): void
    {
        $collection = collect(['foo', 123456, INF, 3.0, fn () => 'bar']);

        $this->assertCount(0, $this->callMacro($collection, RuntimeException::class));
    }
}
