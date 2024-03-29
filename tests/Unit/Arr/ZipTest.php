<?php

namespace Lyhty\Macros\Tests\Unit\Arr;

use Exception;
use Illuminate\Support\Arr;
use Lyhty\Macros\Tests\Unit\MacroUnitTestCase;

class ZipTest extends MacroUnitTestCase
{
    protected string $class = Arr::class;

    protected string $macro = 'zip';

    public function testZippingArrays(): void
    {
        foreach ([':', '=>', 'BETWEEN', 3 * 10] as $zipper) {
            $array = $this->callStaticMacro(['foo' => 'bar', 'one' => 'two'], $zipper);
            $this->assertSame(["foo{$zipper}bar", "one{$zipper}two"], $array);
        }
    }

    public function testZippingNonAssociativeArray(): void
    {
        $this->expectException(Exception::class);

        $this->callStaticMacro(['one', 'two', 'three'], '>');
    }
}
