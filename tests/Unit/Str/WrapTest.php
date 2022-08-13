<?php

namespace Lyhty\Macros\Tests\Unit\Str;

use Illuminate\Support\Str;
use Lyhty\Macros\Tests\Unit\MacroUnitTestCase;

class WrapTest extends MacroUnitTestCase
{
    protected string $class = Str::class;

    protected string $macro = 'wrap';

    public function testSimpleWrapping(): void
    {
        $string = $this->callStaticMacro('foo', '!');
        $this->assertSame('!foo!', $string);

        $string = $this->callStaticMacro('!foo!', '!!');
        $this->assertSame('!!!foo!!!', $string);
    }

    public function testWrappingWithDifferentCaps(): void
    {
        $string = $this->callStaticMacro('foo', '<', '>');

        $this->assertSame('<foo>', $string);
    }

    public function testWrappingWithAlreadyCappedString(): void
    {
        $string = $this->callStaticMacro('!foo!', '!');
        $this->assertSame('!foo!', $string);

        $string = $this->callStaticMacro('!!foo!!', '!');
        $this->assertSame('!foo!', $string);

        $string = $this->callStaticMacro('<foo>', '<', '>');
        $this->assertSame('<foo>', $string);
    }
}
