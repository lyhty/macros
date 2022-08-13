<?php

namespace Lyhty\Macros\Tests\Integration;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Lyhty\Macros\MacroServiceProvider;
use Lyhty\Macros\Tests\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class DisableTest extends TestCase
{
    protected static $provider = TestMacroServiceProvider::class;

    public function testMacroIsDisabled(): void
    {
        $this->assertFalse(Arr::hasMacro('associate'));
        $this->assertFalse(Str::hasMacro('explodeReverse'));
    }

    public function testMacroIsEnabled(): void
    {
        $this->assertTrue(Arr::hasMacro('combine'));
    }
}

class TestMacroServiceProvider extends MacroServiceProvider
{
    protected function getConfigPath(): string
    {
        return sprintf('%s/config/%s.php', __DIR__, static::CONFIG_NAME);
    }
}
