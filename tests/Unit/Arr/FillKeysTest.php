<?php

namespace Lyhty\Macros\Tests\Unit\Arr;

use Illuminate\Support\Arr;
use Lyhty\Macros\Tests\Unit\MacroUnitTestCase;

class FillKeysTest extends MacroUnitTestCase
{
    protected string $class = Arr::class;

    protected string $macro = 'fillKeys';

    public function testFillingKeys(): void
    {
        $expected = ['foo' => 'test', 'bar' => null];
        $actual = $this->callStaticMacro(['foo' => null, 'bar' => null], 'foo', 'test');

        $this->assertSame($expected, $actual);
    }

    public function testFillingKeysWithAddedKeys(): void
    {
        $expected = ['foo' => 'test', 'bar' => null, 5 => 'test'];
        $actual = $this->callStaticMacro(['foo' => null, 'bar' => null], ['foo', 5], 'test');

        $this->assertSame($expected, $actual);
    }

    public function testFillingKeysWithAddedKeysAndIgnore(): void
    {
        $expected = ['foo' => 'test', 'bar' => null];
        $actual = $this->callStaticMacro(['foo' => null, 'bar' => null], ['foo', 5], 'test', true);

        $this->assertSame($expected, $actual);
    }
}
