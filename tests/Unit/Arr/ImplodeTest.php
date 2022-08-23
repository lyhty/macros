<?php

namespace Lyhty\Macros\Tests\Unit\Arr;

use Illuminate\Support\Arr;
use Lyhty\Macros\Tests\Unit\MacroUnitTestCase;

class ImplodeTest extends MacroUnitTestCase
{
    protected string $class = Arr::class;

    protected string $macro = 'implode';

    public function testImploding(): void
    {
        $str = (string) $this->callStaticMacro(['foo', 'bar'], ' ')->upper();
        $this->assertSame('FOO BAR', $str);
    }
}
