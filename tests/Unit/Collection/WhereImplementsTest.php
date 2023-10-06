<?php

namespace Lyhty\Macros\Tests\Unit\Collection;

use Countable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Lyhty\Macros\Tests\Unit\MacroUnitTestCase;

class WhereImplementsTest extends MacroUnitTestCase
{
    protected string $class = Collection::class;

    protected string $macro = 'whereImplements';

    public function testWhereInstancesImplementDefinedInterface(): void
    {
        $collection = collect([
            new JsonResponse,
            new ResourceCollection([]),
            new AnonymousResourceCollection([], JsonResource::class),
        ]);

        $this->assertContainsOnlyInstancesOf(
            ResourceCollection::class,
            $this->callMacro($collection, Countable::class)
        );
    }

    public function testWhereClassnamesImplementDefinedInterface(): void
    {
        $collection = collect([
            JsonResponse::class,
            ResourceCollection::class,
            AnonymousResourceCollection::class,
        ]);

        $result = $this->callMacro($collection, Countable::class);

        $this->assertCount(2, $result);
        $this->assertSame(ResourceCollection::class, $result->first());
    }

    public function testNonClasses(): void
    {
        $collection = collect(['foo', 123456, INF, 3.0, fn () => 'bar']);

        $this->assertCount(0, $this->callMacro($collection, Countable::class));
    }
}
