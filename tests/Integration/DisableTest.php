<?php

namespace Lyhty\Macros\Tests\Integration;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Lyhty\Macros\MacroServiceProvider;
use Lyhty\Macros\Tests\TestCase;

/**
 * @runTestsInSeparateProcesses
 *
 * @preserveGlobalState disabled
 */
class DisableTest extends TestCase
{
    protected static $provider = TestMacroServiceProvider::class;

    public function testMacroIsDisabled(): void
    {
        $tests = [
            Arr::class => 'associate',
            Str::class => 'explodeReverse',
        ];

        foreach ($tests as $class => $macro) {
            $this->assertFalse(
                $class::hasMacro($macro),
                sprintf('%s still has macro `%s` when it should be disabled.', $class, $macro)
            );
        }
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
