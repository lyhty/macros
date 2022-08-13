<?php

namespace Lyhty\Macros\Tests\Unit\Collection;

use Illuminate\Support\Collection;
use Lyhty\Macros\Tests\Unit\MacroUnitTestCase;
use Lyhty\Macros\Collection\PickMacro as Macro;

class PickTest extends MacroUnitTestCase
{
    protected string $class = Collection::class;

    protected string $macro = 'pick';

    public function testSelectingWithoutKeys()
    {
        $collection = collect([
            ['name' => 'Foo Bar', 'email' => 'foo@bar.com', 'metadata' => ['logged_in' => false]],
            ['name' => 'Goo Bar', 'email' => 'goo@bar.com', 'metadata' => ['logged_in' => true]],
            ['name' => 'Noo Bar', 'email' => 'noo@bar.com'],
        ]);

        $this->assertSame(
            [['Foo Bar', false], ['Goo Bar', true], ['Noo Bar', null]],
            $this->callMacro($collection, ['name', 'metadata.logged_in'])->toArray()
        );
    }

    public function testSelectingWithPartialKeys()
    {
        $collection = collect([
            ['name' => 'Foo Bar', 'email' => 'foo@bar.com', 'metadata' => ['logged_in' => false]],
            ['name' => 'Goo Bar', 'email' => 'goo@bar.com', 'metadata' => ['logged_in' => true]],
            ['name' => 'Noo Bar', 'email' => 'noo@bar.com'],
        ]);

        $this->assertSame([
            ['name' => 'Foo Bar', 'logged_in' => false],
            ['name' => 'Goo Bar', 'logged_in' => true],
            ['name' => 'Noo Bar', 'logged_in' => null],
        ], $this->callMacro($collection, ['name', 'metadata.logged_in'], Macro::PRESERVE_KEYS_PARTIAL)->toArray());
    }

    public function testSelectingWithFullKeys()
    {
        $collection = collect([
            ['name' => 'Foo Bar', 'email' => 'foo@bar.com', 'metadata' => ['logged_in' => false]],
            ['name' => 'Goo Bar', 'email' => 'goo@bar.com', 'metadata' => ['logged_in' => true]],
            ['name' => 'Noo Bar', 'email' => 'noo@bar.com'],
        ]);

        $this->assertSame([
            ['name' => 'Foo Bar', 'metadata' => ['logged_in' => false]],
            ['name' => 'Goo Bar', 'metadata' => ['logged_in' => true]],
            ['name' => 'Noo Bar', 'metadata' => ['logged_in' => null]],
        ], $this->callMacro($collection, ['name', 'metadata.logged_in'], Macro::PRESERVE_KEYS_FULL)->toArray());
    }
}
