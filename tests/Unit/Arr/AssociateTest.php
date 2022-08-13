<?php

namespace Lyhty\Macros\Tests\Unit\Arr;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Lyhty\Macros\Tests\Unit\MacroUnitTestCase;

class AssociateTest extends MacroUnitTestCase
{
    protected string $class = Arr::class;

    protected string $macro = 'associate';

    protected function assertAssociation(array $expected, array $actual, $fill): void
    {
        $actual = $this->callStaticMacro($actual, $fill);

        $this->assertSame($expected, $actual);
    }

    public function testFullyKeyedArray(): void
    {
        $expected = ['foo' => 'bar', 'zoo' => 'gar'];

        $this->assertAssociation($expected, $expected, null);
    }

    public function testPartiallyKeyedArray(): void
    {
        $this->assertAssociation(
            ['foo' => 'test', 'bar' => 'test', 'zoo' => []],
            ['foo', 'bar', 'zoo' => []],
            'test'
        );
    }

    public function testFullAssociation(): void
    {
        $this->assertAssociation(
            ['one' => ['test'], 'two' => ['test'], 'three' => ['test']],
            ['one', 'two', 'three'],
            ['test']
        );
    }

    public function testNesting(): void
    {
        $this->assertAssociation(
            ['foo' => ['bar' => ['zulu' => []]], 'zoo' => ['gar' => []]],
            ['foo.bar.zulu', 'zoo.gar' => []],
            []
        );
    }

    public function testFillingWithClosure(): void
    {
        $this->assertAssociation(
            ['one' => 'ONE', 'two' => 'TWO', 'three' => 'THREE'],
            ['one', 'two', 'three'],
            fn ($key, $index) => Str::upper($key)
        );

        $this->assertAssociation(
            ['one' => 0, 'two' => 10, 'three' => 20],
            ['one', 'two', 'three'],
            fn ($key, $index) => $index * 10
        );
    }
}
