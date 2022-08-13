<?php

namespace Lyhty\Macros\Tests\Unit\Arr;

use Illuminate\Support\Arr;
use Lyhty\Macros\Tests\Unit\MacroUnitTestCase;

use const Lyhty\Macros\Tests\ARR_ZIPPERS;

class UnzipTest extends MacroUnitTestCase
{
    protected string $class = Arr::class;

    protected string $macro = 'unzip';

    public function testUnzippingArrays(): void
    {
        foreach (ARR_ZIPPERS as $zipper) {
            $array = $this->callStaticMacro(["foo{$zipper}bar", "one{$zipper}two"], $zipper);
            $this->assertSame(['foo' => 'bar', 'one' => 'two'], $array);
        }
    }

    public function testUnzippingAssociativeArray()
    {
        $array = $this->callStaticMacro(['foo' => 'bar:zoo', 'one:two'], ':');

        $this->assertSame(['bar' => 'zoo', 'one' => 'two'], $array);
    }
}
