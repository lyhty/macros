<?php

namespace Lyhty\Macros\Tests\Unit;

use Lyhty\Macros\Tests\TestCase;

abstract class MacroUnitTestCase extends TestCase
{
    protected string $class = '';

    protected string $macro = '';

    public function testHasMacro()
    {
        $this->assertTrue($this->class::hasMacro($this->macro));
    }

    public function callStaticMacro(...$args)
    {
        return $this->class::{$this->macro}(...$args);
    }

    public function callMacro($instance, ...$args)
    {
        return $instance->{$this->macro}(...$args);
    }
}
