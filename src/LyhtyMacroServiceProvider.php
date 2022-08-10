<?php

namespace Lyhty\Macros;

use Illuminate\Support\Arr;
use Lyhty\Macros\MacroServiceProvider as ServiceProvider;

class LyhtyMacroServiceProvider extends ServiceProvider
{
    const CONFIG = 'lyhty_macros';

    /**
     * The macro mappings for the application.
     *
     * @var array
     */
    protected static array $macros = [
        \Illuminate\Database\Eloquent\Builder::class => [
            'selectKey' => Macros\Builder\SelectKeyMacro::class,
            'whereLike' => Macros\Builder\WhereLikeMacro::class,
            'orWhereLike' => Macros\Builder\WhereLikeOrMacro::class,
        ],
        \Illuminate\Database\Query\Builder::class => [
            'selectRawArr' => Macros\Builder\SelectRawArrMacro::class,
        ],
        \Illuminate\Support\Collection::class => [
            'mergeMany' => Macros\Collection\MergeManyMacro::class,
            'pluckMany' => Macros\Collection\PluckManyMacro::class,
            'whereExtends' => Macros\Collection\WhereExtendsMacro::class,
            'whereImplements' => Macros\Collection\WhereImplementsMacro::class,
            'whereUses' => Macros\Collection\WhereUsesMacro::class,
        ],
        \Illuminate\Support\Arr::class => [
            'associate' => Macros\Arr\AssociateMacro::class,
            'combine' => Macros\Arr\CombineMacro::class,
            'fillKeys' => Macros\Arr\FillKeysMacro::class,
            'join' => Macros\Arr\JoinMacro::class,
            'unzip' => Macros\Arr\UnzipMacro::class,
            'zip' => Macros\Arr\ZipMacro::class,
        ],
        \Illuminate\Support\Str::class => [
            'explodeReverse' => Macros\Str\ExplodeReverseMacro::class,
            'wrap' => Macros\Str\WrapMacro::class,
        ],
        \Illuminate\Support\Stringable::class => [
            'explodeReverse' => Macros\Stringable\ExplodeReverseMacro::class,
            'wrap' => Macros\Stringable\WrapMacro::class,
        ],
        \Carbon\CarbonPeriod::class => [
            'collect' => Macros\CarbonPeriod\CollectMacro::class,
        ],
    ];

    /**
     * Return the macros mappings array.
     *
     * @return array
     */
    public function getMacros(): array
    {
        $config = Arr::get($this->app, sprintf('config.%s.disabled', static::CONFIG), []);

        return collect(parent::getMacros())
            ->map(function ($classes) use ($config) {
                return collect($classes)->reject(
                    fn ($class) => in_array($class, $config)
                );
            })->toArray();
    }

    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/'.static::CONFIG.'.php' => config_path(static::CONFIG.'.php'),
        ]);

        parent::boot();

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\MacroMakeCommand::class,
                Commands\MacroGenerateCommand::class,
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/'.static::CONFIG.'.php', static::CONFIG);
    }
}
