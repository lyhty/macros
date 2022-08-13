<?php

namespace Lyhty\Macros\Tests\Unit\Arr;

use Illuminate\Support\Arr;
use Lyhty\Macros\Tests\Unit\MacroUnitTestCase;

class JoinTest extends MacroUnitTestCase
{
    protected string $class = Arr::class;

    protected string $macro = 'join';

    public function testRegularJoining(): void
    {
        $string = $this->callStaticMacro(['one', 'two', 'three'], ', ');
        $this->assertSame('one, two, three', $string);

        $string = $this->callStaticMacro(['one', 'two'], ', ');
        $this->assertSame('one, two', $string);

        $string = $this->callStaticMacro(['one'], ', ');
        $this->assertSame('one', $string);
    }

    public function testRegularJoiningWithDifferentTypes(): void
    {
        $string = $this->callStaticMacro([1, 2, 3], ':');
        $this->assertSame('1:2:3', $string);

        $string = $this->callStaticMacro([1.5, 2.5], '!');
        $this->assertSame('1.5!2.5', $string);
    }

    public function testFinalGlueJoining(): void
    {
        $string = $this->callStaticMacro(['one', 'two', 'three'], ', ', ' and ');
        $this->assertSame('one, two and three', $string);

        $string = $this->callStaticMacro([1, 2.951], ', ', ' and ');
        $this->assertSame('1 and 2.951', $string);

        $string = $this->callStaticMacro(['one'], ', ', ' and ');
        $this->assertSame('one', $string);
    }
}
