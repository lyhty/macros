<?php

namespace Lyhty\Macros;

use Illuminate\Support\Collection as SupportCollection;
use Lyhty\Macronite\MacroServiceProvider as ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    const CONFIG_NAME = 'lyhty_macros';

    /**
     * The macro mappings for the application.
     *
     * @var array
     */
    protected static array $macros = [
        \Illuminate\Database\Eloquent\Builder::class => [
            'selectKey' => Builder\SelectKeyMacro::class,
            'whereLike' => Builder\WhereLikeMacro::class,
            'orWhereLike' => Builder\WhereLikeOrMacro::class,
        ],
        \Illuminate\Database\Query\Builder::class => [
            'selectRawArr' => Builder\SelectRawArrMacro::class,
        ],
        \Illuminate\Support\Collection::class => [
            'mergeMany' => Collection\MergeManyMacro::class,
            'pick' => Collection\PickMacro::class,
            'pluckMany' => Collection\PickMacro::class,
            'whereExtends' => Collection\WhereExtendsMacro::class,
            'whereImplements' => Collection\WhereImplementsMacro::class,
            'whereUses' => Collection\WhereUsesMacro::class,
        ],
        \Illuminate\Support\Arr::class => [
            'associate' => Arr\AssociateMacro::class,
            'combine' => Arr\CombineMacro::class,
            'fillKeys' => Arr\FillKeysMacro::class,
            'join' => Arr\JoinMacro::class,
            'unzip' => Arr\UnzipMacro::class,
            'zip' => Arr\ZipMacro::class,
        ],
        \Illuminate\Support\Str::class => [
            'explodeReverse' => Str\ExplodeReverseMacro::class,
            'wrap' => Str\WrapMacro::class,
        ],
        \Illuminate\Support\Stringable::class => [
            'explodeReverse' => Stringable\ExplodeReverseMacro::class,
            'wrap' => Stringable\WrapMacro::class,
        ],
        \Carbon\CarbonPeriod::class => [
            'collect' => CarbonPeriod\CollectMacro::class,
        ],
    ];

    /**
     * Return the macros mappings array.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function filterMacros(SupportCollection $macros)
    {
        $config = $this->app->make('config')->get(sprintf('%s.disabled', static::CONFIG_NAME), []);

        return $macros->reject(fn ($class) => in_array($class, $config));
    }

    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        $this->publishes([
            $this->getConfigPath() => config_path(static::CONFIG_NAME.'.php'),
        ]);

        parent::boot();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->getConfigPath(), static::CONFIG_NAME);
    }

    protected function getConfigPath(): string
    {
        return __DIR__.'/../config/'.static::CONFIG_NAME.'.php';
    }
}
