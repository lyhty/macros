<?php

namespace Lyhty\Macros\Tests\Unit\Collection;

use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\CollectsResources;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Lyhty\Macros\Tests\Unit\MacroUnitTestCase;

class WhereUsesTest extends MacroUnitTestCase
{
    protected string $class = Collection::class;

    protected string $macro = 'whereUses';

    public function testWhereInstancesUsesDefinedTrait(): void
    {
        $collection = collect([
            new JsonResponse(),
            new ResourceCollection([]),
            new AnonymousResourceCollection([], Collection::class),
        ]);

        $this->assertContainsOnlyInstancesOf(
            ResourceCollection::class,
            $this->callMacro($collection, CollectsResources::class)
        );
    }

    public function testWhereClassnamesUsesDefinedTrait(): void
    {
        $collection = collect([
            JsonResponse::class,
            ResourceCollection::class,
            AnonymousResourceCollection::class,
            Model::class,
        ]);

        $result = $this->callMacro($collection, HasAttributes::class);

        $this->assertCount(1, $result);
        $this->assertSame(Model::class, $result->first());
    }

    public function testNonClasses(): void
    {
        $collection = collect(['foo', 123456, INF, 3.0, fn () => 'bar']);

        $this->assertCount(0, $this->callMacro($collection, HasAttributes::class));
    }
}
