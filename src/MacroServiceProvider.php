<?php

namespace Lyhty\Macros;

use Illuminate\Support\ServiceProvider;

abstract class MacroServiceProvider extends ServiceProvider
{
    /**
     * The macro mappings for the application.
     *
     * @var array
     */
    protected static array $macros = [];

    /**
     * Bootstrap closure based application macros.
     *
     * @return void
     */
    public function bootMacros(): void
    {
        //
    }

    /**
     * Return the macros mappings array.
     *
     * @return array
     */
    public function getMacros(): array
    {
        return static::$macros;
    }

    /**
     * Bootstrap application macros.
     *
     * @return void
     */
    public function boot()
    {
        foreach ($this->getMacros() as $macroable => $macros) {
            collect($macros)
                ->reject(
                    fn ($class, $macro) => static_method_exists($macroable, 'hasMacro') &&
                    $macroable::hasMacro($macro)
                )
                ->filter(fn ($class) => class_exists($class))
                ->each(fn ($class, $macro) => $macroable::macro($macro, app($class)()));
        }

        static::bootMacros();
    }
}

/**
 * Checks if static method exists.
 *
 * @param  object|string  $object_or_class
 * @param  string  $method
 * @return void
 */
function static_method_exists($object_or_class, string $method)
{
    if (! method_exists($object_or_class, $method)) {
        return false;
    }

    $reflection = new \ReflectionMethod($object_or_class, $method);

    return $reflection->isStatic();
}
