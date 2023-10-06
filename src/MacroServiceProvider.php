<?php

namespace Lyhty\Macros;

use Illuminate\Support\Collection as SupportCollection;
use Lyhty\Macronite\MacroServiceProvider as ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    const CONFIG_NAME = 'lyhty_macros';

    /**
     * The macro mappings for the application.
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
            'implode' => Arr\ImplodeMacro::class,
            'join' => Arr\JoinMacro::class,
            'unzip' => Arr\UnzipMacro::class,
            'zip' => Arr\ZipMacro::class,
        ],
        \Illuminate\Support\Str::class => [
            'explodeReverse' => Str\ExplodeReverseMacro::class,
            'wrapWith' => Str\WrapWithMacro::class,
        ],
        \Illuminate\Support\Stringable::class => [
            'explodeReverse' => Stringable\ExplodeReverseMacro::class,
            'wrapWith' => Stringable\WrapWithMacro::class,
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
    protected function filterMacros(SupportCollection $macros, string $macroableClass)
    {
        $disabled = $this->explodeDisabled();

        if (! isset($disabled[$macroableClass])) {
            return $macros;
        }

        $disabled = $disabled[$macroableClass]
            ->filter(fn ($value) => $value[2])
            ->map(fn ($value) => $value[1])
            ->all();

        return $macros->reject(fn ($value, $key) => in_array($key, $disabled));
    }

    protected function explodeDisabled(): SupportCollection
    {
        $config = $this->app->make('config');

        return collect($config->get(sprintf('%s.disabled', static::CONFIG_NAME), []))
            ->map(function ($value, $key) {
                $disabled = is_bool($value) ? $value : true;
                [$class, $method] = explode('@', is_numeric($key) ? $value : $key, 2);

                return [$class, $method, $disabled];
            })
            ->values()
            ->groupBy(0);
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
