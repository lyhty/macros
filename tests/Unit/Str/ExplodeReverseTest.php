<?php

namespace Lyhty\Macros\Tests\Unit\Str;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Lyhty\Macros\Tests\Unit\MacroUnitTestCase;

class ExplodeReverseTest extends MacroUnitTestCase
{
    protected string $class = Str::class;

    protected string $macro = 'explodeReverse';

    public function testReturnsCollectionOfStrings(): void
    {
        $result = $this->callStaticMacro('user.products.manifacturer', '.');
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertContainsOnly('string', $result);
    }

    public function testReverseExplodingStringFully(): void
    {
        $result = $this->callStaticMacro('user.products.manifacturer', '.');
        $this->assertCount(3, $result);
        $this->assertSame(['user', 'products', 'manifacturer'], $result->toArray());
    }

    public function testReverseExplodingStringWithLimit(): void
    {
        $result = $this->callStaticMacro('user.products.manifacturer', '.', 2);
        $this->assertCount(2, $result);
        $this->assertSame(['user.products', 'manifacturer'], $result->toArray());

        $result = $this->callStaticMacro('user.products.manifacturer', '.', 1);
        $this->assertCount(1, $result);
        $this->assertSame(['user.products.manifacturer'], $result->toArray());
    }

    public function testExplodingWithNonexistingSeparator(): void
    {
        $result = $this->callStaticMacro('user.products.manifacturer', ',', 2);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(1, $result);
        $this->assertSame(['user.products.manifacturer'], $result->toArray());
    }
}
