<?php

namespace Lyhty\Macros\Tests\Unit\Collection;

use Illuminate\Support\Collection;
use Lyhty\Macros\Tests\Unit\MacroUnitTestCase;

class MergeManyTest extends MacroUnitTestCase
{
    protected string $class = Collection::class;

    protected string $macro = 'mergeMany';

    public function testSimpleMergingOfManyArrays(): void
    {
        $collection = collect(['one'])->mergeMany(['two'], ['three']);
        $this->assertSame(['one', 'two', 'three'], $collection->toArray());

        $collection = collect(['one'])->mergeMany('two', 'three');
        $this->assertSame(['one', 'two', 'three'], $collection->toArray());
    }

    public function testMergingOfManyArraysWithDuplicateValues(): void
    {
        $collection = collect(['one'])->mergeMany(['one', 'two'], ['three']);

        $this->assertSame(['one', 'one', 'two', 'three'], $collection->toArray());
    }

    public function testMergingManyAssociativeArrays(): void
    {
        $collection = collect(['one' => 1])->mergeMany(['two' => 2], ['three' => 3]);

        $this->assertSame(['one' => 1, 'two' => 2, 'three' => 3], $collection->toArray());
    }

    public function testMergingOfManyAssociativeArraysWithExistingKeys(): void
    {
        $collection = collect(['one' => 1])->mergeMany(['one' => 10, 'two' => 2], ['three' => 3]);

        $this->assertSame(['one' => 10, 'two' => 2, 'three' => 3], $collection->toArray());
    }

    public function testMergingManyDeepArrays(): void
    {
        $collection = collect(['foo' => ['bar' => 1]])->mergeMany(
            ['zoo' => ['gar' => 2]],
            ['goo' => ['tar' => ['har' => 3]]]
        );

        $this->assertSame(
            ['foo' => ['bar' => 1], 'zoo' => ['gar' => 2], 'goo' => ['tar' => ['har' => 3]]],
            $collection->toArray()
        );
    }

    public function testMergingManyDeepArraysWithExistingKeys(): void
    {
        $collection = collect(['foo' => ['bar' => 1]])->mergeMany(
            ['foo' => ['gar' => 2]],
            ['zoo' => ['bar' => 3]]
        );

        $this->assertSame(
            ['foo' => ['gar' => 2], 'zoo' => ['bar' => 3]],
            $collection->toArray()
        );
    }
}
