<?php

namespace Lyhty\Macros\Tests;

use Lyhty\Macros\MacroServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected static $provider = MacroServiceProvider::class;

    protected function getPackageProviders($app)
    {
        return [
            static::$provider,
            \Lyhty\Macronite\MacroniteServiceProvider::class,
        ];
    }
}
