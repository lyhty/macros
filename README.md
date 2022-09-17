<p>
  <img src="https://matti.suoraniemi.com/storage/lyhty-macros.png" width="400">
</p>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lyhty/macros.svg?style=flat-square)](https://packagist.org/packages/lyhty/macros)
[![PHP](https://img.shields.io/packagist/php-v/lyhty/macros?style=flat-square)](https://packagist.org/packages/lyhty/macros)
[![Laravel](https://img.shields.io/static/v1?label=&message=^8.0%20|%20^9.0&color=red&style=flat-square&logo=laravel&logoColor=white)](https://packagist.org/packages/lyhty/macronite)
[![Total Downloads](https://img.shields.io/packagist/dt/lyhty/macros.svg?style=flat-square)](https://packagist.org/packages/lyhty/macros)
[![Tests](https://img.shields.io/github/workflow/status/lyhty/macros/Run%20tests?style=flat-square)](https://github.com/lyhty/macros/actions/workflows/php.yml)
[![StyleCI](https://github.styleci.io/repos/514416534/shield)](https://github.styleci.io/repos/514416534)
[![License](https://img.shields.io/packagist/l/lyhty/macros.svg?style=flat-square)](https://packagist.org/packages/lyhty/macros)

<!-- CUTOFF -->

This package provides some additional, convenient macros for you to use with your Laravel project.

## Installation

Install the package with Composer:

    composer require lyhty/macros

The package registers itself automatically.

## Macros

Here's a brief documentation on the macros the package provides.

- [`Illuminate\Database\Eloquent\Builder`](#illuminatedatabaseeloquentbuilder)
  - [`selectKey`](#builderselectkey)
  - [`whereLike` & `orWhereLike`](#builderwherelike--orwherelike)
- [`Illuminate\Database\Query\Builder`](#illuminatedatabasequerybuilder)
  - [`selectRawArr`](#builderselectrawarr)
- [`Illuminate\Support\Collection`](#illuminatesupportcollection)
  - [`mergeMany`](#collectionmergemany)
  - [`pick` (was `pluckMany`)](#collectionpick-was-pluckmany)
  - [`whereExtends`](#collectionwhereextends)
  - [`whereImplements`](#collectionwhereimplements)
  - [`whereUses`](#collectionwhereuses)
- [`Illuminate\Support\Arr`](#illuminatesupportarr)
  - [`associate`](#arrassociate)
  - [`combine`](#arrcombine)
  - [`fillKeys`](#arrfillkeys)
  - [`implode`](#arrimplode)
  - [`join`](#arrjoin)
  - [`zip`](#arrzip)
  - [`unzip`](#arrunzip)
- [`Illuminate\Support\Str`](#illuminatesupportstr)
  - [`explodeReverse`](#strexplodereverse)
  - [`wrap`](#strwrap)
- [`Illuminate\Support\Stringable`](#illuminatesupportstringable)
  - [`explodeReverse`](#stringableexplodereverse)
  - [`wrap`](#stringablewrap)
- [`Carbon\CarbonPeriod`](#carboncarbonperiod)
  - [`collect`](#carbonperiodcollect)

### `Illuminate\Database\Eloquent\Builder`

#### `Builder::selectKey`

Select the key of the model in the query (uses Model's `getKey` method).

```php
$query = User::query()->selectKey();

$query->toSql(); // "select `id` from `users`"
```

### `Illuminate\Database\Query\Builder`

#### `Builder::whereLike` & `orWhereLike`

> ⚠️ This macro relies on `Str::explodeReverse` macro. If you want to disable the latter macro, this macro will no longer function.

> ⚠️ The `Builder::orWhereLike` macro relies on `Builder::whereLike` macro. If you want to disable the `whereLike` macro, be sure to disable the `orWhereLike` macro as well.

```php
$query = User::query()
    ->whereLike('name', 'Matti Suo', 'right')
    ->orWhereLike('name', 'ranie')
    ->orWhereLike('name', 'mi', 'left');

$query->toSql();
// "select * from `users` where (`users`.`name` LIKE ?) or (`users`.`name` LIKE ?) or (`users`.`name` LIKE ?)"
// First ? being "Matti Suo%", second "%ranie%" and third "%mi"

$query = User::query()->whereLike('games.name', 'Apex Leg', 'right');

$query->toSql();
// select * from `users` where (exists
//   (select * from `games` where `users`.`id` = `games`.`user_id` and `games`.`name` LIKE ?)
// )
// ? being "Apex Leg%"

$query = User::query()->whereLike(['games.console.name', 'games.platforms.name'], 'Xbox');

$query->toSql();
// select * from `users` where (exists (select * from `games` where `users`.`id` = `games`.`user_id` and (exists
// (select * from `consoles` where `games`.`console_id` = `consoles`.`id` and (`consoles`.`name` LIKE ?)) or exists
// (select * from `platforms` inner join `platform_game` on `platforms`.`id` = `platform_game`.`platform_id` where
// `games`.`id` = `platform_game`.`game_id` and (`platforms`.`name` LIKE ?)))))
// ? being "Xbox"
```

#### `Builder::selectRawArr`

Add raw select statements as an array, instead of as a one ugly string (`selectRaw`).

```php
$query = User::query()->selectRawArr([
    'concat(`id`, "-", `name`) as id_name',
    'concat(`email`, "-", `name`) as email_name'
]);
// 🤩

$query->first()->toArray(); // ["id_name" => "1-Matti", "email_name" => "matti@suoraniemi.com-Matti"]

// Instead of:
$query = User::query()->selectRaw('concat(`id`, "-", `name`) as id_name, concat(`email`, "-", `name`) as email_name');
// 🤢
```

### `Illuminate\Support\Collection`

#### `Collection::mergeMany`

Merge multiple arrays/collections to the collection in one go.

```php
$data = new Collection([1,2,3]);

$data->mergeMany([4], [5], [6]); // [1, 2, 3, 4, 5, 6]
```

#### `Collection::pick` (was `pluckMany`)

Pick several keys from the collection items. The first value should be an array of keys
you want to pick up from the collection items. The second value determines whether keys
will be preserved and in which format:

- `PICK_WITH_FULL_KEYS (>= 2)`:
  - Keeps even the possibly nested values in their original depths.
- `PICK_WITH_PARTIAL_KEYS (1)`:
  - Flattens the results while keeping the keys.
- `PICK_WITHOUT_KEYS (0)`:
  - No keys will be preserved

```php
$data = User::query()->get();

$data->pick(['id', 'name', 'metadata.loggedIn'])->toArray();
// [[1, "Matti Suoraniemi", true], [2, "Foo Bar", false]]

$data->pick(['id', 'name', 'metadata.loggedIn'], 1)->toArray();
// [
//   ["id" => 1, "name" => "Matti Suoraniemi", "loggedIn" => true],
//   ["id" => 2, "name" => "Foo Bar", "loggedIn" => false]
// ]

$data->pick(['id', 'name', 'metadata.loggedIn'], 2)->toArray();
// [
//   ["id" => 1, "name" => "Matti Suoraniemi", "metadata" => ["loggedIn" => true]],
//   ["id" => 2, "name" => "Foo Bar", "metadata" => ["loggedIn" => false]]
// ]
```

#### `Collection::whereExtends`

Filter classes and/or objects that extend the given class.

```php
use Illuminate\Database\Eloquent\Model;

$data = new Collection([
    \App\Models\User::class,
    \App\Models\Game::class,
    \App\Models\Console::class,
    \App\Models\Hobby::class,
]);

$data->whereExtends(Model::class)->count(); // 4
```

#### `Collection::whereImplements`

Filter classes and/or objects that implement the given interface.

```php
use App\Contracts\PlayableOnConsole;

$data = new Collection([
    \App\Models\User::class,
    \App\Models\Game::class,
    \App\Models\Console::class,
    \App\Models\Hobby::class,
]);

$data->whereImplements(PlayableOnConsole::class)->toArray(); // ["App\Models\Game"]
```

#### `Collection::whereUses`

Filter classes and/or objects that use the given trait.

```php
use Illuminate\Notifications\Notifiable;

$data = new Collection([
    \App\Models\User::class,
    \App\Models\Game::class,
    \App\Models\Console::class,
    \App\Models\Hobby::class,
]);

$data->whereUses(Notifiable::class)->toArray(); // ["App\Models\User"]
```

### `Illuminate\Support\Arr`

#### `Arr::associate`

Converts an array into a fully associative array by converting any values with an integer key
to the value being the key filled with the given fill value. Values that have a string key already
won't be touched.

```php
Arr::associate(['foo']); // ["foo" => null]
Arr::associate(['foo', 'bar' => []], []); // ["foo" => [], "bar" => []]
Arr::associate(['foo', 'bar' => []], fn () => Arr::random(['foo', 'bar'])); // ["foo" => "foo", "bar" => []]
Arr::associate([fn () => Str::reverse('foo'), 'bar' => []]); // ["oof" => null, "bar" => []]
```

#### `Arr::combine`

Similar to `array_combine`, but allows to have more keys than values. Keys without value will be set
as null.

```php
Arr::combine(['foo', 'zoo'], ["bar", "gar"]); // ["foo" => "bar", "zoo" => "gar"]
Arr::combine(['foo', 'zoo'], ["bar"]); // ["foo" => "bar", "zoo" => null]
```

#### `Arr::fillKeys`

Fills given keys with given value. You can also set that only keys that already exist in the array
can become filled. In other words, if the key `foo` is to be filled with value `bar`, but the key
`foo` doesn't already exist in the array, the array will remain unchanged.

```php
$array = ['foo' => 'bar', 'zoo' => 'gar'];

Arr::fillKeys($array, ['foo', 'zoo'], null); // ["foo" => null, "zoo" => null]
Arr::fillKeys($array, ['foo', 'zoo', 'boo'], null); // ["foo" => null, "zoo" => null, "boo" => null]
Arr::fillKeys($array, ['foo', 'zoo', 'boo'], null, true); // ["foo" => null, "zoo" => null]
```

#### `Arr::implode`

Implodes given array with given separator to a `\Illuminate\Support\Stringable` instance.

```php
$array = ['foo', 'bar'];

(string) Arr::implode($array, ' ')->upper(); // "FOO BAR"
```

#### `Arr::join`

Collection's nice join method brought to Arr.

```php
Arr::join(['foo', 'bar', 'zoo'], ', ', ' and '); // "foo, bar and zoo"
```

#### `Arr::zip`

Zips the key and value together with the given zipper.

```php
Arr::zip(['foo' => 'bar', 'zoo' => 'gar'], ':'); // ["foo:bar", "zoo:gar"]
```

#### `Arr::unzip`

Unzips keys to key and value with the given zipper.

```php
Arr::unzip(['foo:bar', 'zoo:gar'], ':'); // ["foo" => "bar", "zoo" => "gar"]
```

#### `Str::explodeReverse`

Explodes the given string from the end instead of the start and returns it as
a `Illuminate\Support\Collection` class instance.

```php
Str::explodeReverse('games.platforms.name', '.', 2)->toArray(); // ['games.platforms', 'name']

// Whereas normal explode function would do:
explode('.', 'games.platforms.name', 2); // ['games', 'platforms.name']
```

### `Illuminate\Support\Str`

#### `Str::wrap`

Wraps the string with given character(s).

```php
Str::wrap('foo', ':'); // ":foo:"
Str::wrap('bar', '<', '>'); // "<bar>"
Str::wrap('!zoo', '!'); // "!zoo!"
```

### `Illuminate\Support\Stringable`

#### `Stringable::explodeReverse`

> ⚠️ This macro relies on `Str::explodeReverse` macro. If you want to disable that macro, this macro will no longer function.

See [Illuminate\Support\Str::explodeReverse](#illuminatesupportstrexplodereverse)

```php
Str::of('games.platforms.name')->explodeReverse('.', 2)->toArray(); // ['games.platforms', 'name']

// Whereas normal explode function would do:
Str::of('games.platforms.name')->explode('.', 2)->toArray(); // ['games', 'platforms.name']
```

#### `Stringable::wrap`

> ⚠️ This macro relies on `Str::wrap` macro. If you want to disable that macro, this macro will no longer function.

See [Illuminate\Support\Str::wrap](#illuminatesupportstrwrap)

```php
(string) Str::of('foo')->upper()->wrap(':'); // ":FOO:"
(string) Str::of('bar')->upper()->wrap('<', '>'); // "<BAR>"
(string) Str::of('!zoo')->upper()->wrap('!'); // "!ZOO!"
```

### `Carbon\CarbonPeriod`

#### `CarbonPeriod::collect`

```php
$dates = CarbonPeriod::between('yesterday', 'today')->collect();

$dates->first()->toDateTimeString(); // "2022-06-14 00:00:00"
```

## License

Lyhty Macros is open-sourced software licensed under the [MIT license](LICENSE).
