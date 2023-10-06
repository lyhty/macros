<?php

namespace Lyhty\Macros\Tests\Unit\Arr;

use Illuminate\Support\Arr;
use Lyhty\Macros\Tests\Unit\MacroUnitTestCase;

class CombineTest extends MacroUnitTestCase
{
    protected string $class = Arr::class;

    protected string $macro = 'combine';

    protected function assertCombining(array $expected, array $keys, array $values): void
    {
        $actual = $this->callStaticMacro($keys, $values);

        $this->assertSame($expected, $actual);
    }

    public function testRegularCombining(): void
    {
        $this->assertCombining([1 => 1, 2 => 2, 3 => 3], [1, 2, 3], [1, 2, 3]);
    }

    public function testCombiningWithMoreKeys(): void
    {
        $this->assertCombining(
            ['foo' => 'bar', 'zoo' => null],
            ['foo', 'zoo'],
            ['bar']
        );
    }

    public function testCombiningWithMoreValues(): void
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage(
            'array_combine(): Argument #1 ($keys) and argument #2 ($values) must have the same number of elements'
        );

        Arr::combine(['bar'], ['foo', 'zoo']);
    }
}
