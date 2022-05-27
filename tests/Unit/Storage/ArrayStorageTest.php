<?php

declare(strict_types=1);

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\Storage;

use Novaway\Bundle\FeatureFlagBundle\Model\Feature;
use Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage;
use PHPUnit\Framework\TestCase;

final class ArrayStorageTest extends TestCase
{
    public function testAllReturnEmptyArrayIfNoFeatureDefined(): void
    {
        $storage = new ArrayStorage();

        static::assertEmpty($storage->all());
    }

    public function testAllReturnDefinedFeatures(): void
    {
        $storage = new ArrayStorage([
            'foo' => ['enabled' => false],
            'bar' => ['enabled' => true, 'description' => 'Feature bar description'],
        ]);

        $features = $storage->all();

        static::assertCount(2, $features);
        static::assertEquals(
            new Feature('foo', false),
            $features['foo'],
        );
        static::assertEquals(
            new Feature('bar', true, 'Feature bar description'),
            $features['bar'],
        );
    }

    /**
     * @dataProvider features
     */
    public function testCheckMethodReturnFeatureState(array $featuresDefinition, string $feature, bool $isEnabled): void
    {
        $storage = new ArrayStorage($featuresDefinition);

        static::assertSame($isEnabled, $storage->check($feature));
    }

    /**
     * @dataProvider features
     */
    public function testIsEnabledMethodReturnFeatureState(array $featuresDefinition, string $feature, bool $isEnabled): void
    {
        $storage = new ArrayStorage($featuresDefinition);

        static::assertSame($isEnabled, $storage->isEnabled($feature));
    }

    /**
     * @dataProvider features
     */
    public function testIsDisabledMethod(array $featuresDefinition, string $feature, bool $isEnabled): void
    {
        $storage = new ArrayStorage($featuresDefinition);

        static::assertNotSame($isEnabled, $storage->isDisabled($feature));
    }

    public function features(): iterable
    {
        yield 'no feature defined' => [[], 'foo', false];

        $featuresDefinition = [
            'foo' => ['enabled' => false],
            'bar' => ['enabled' => true, 'description' => 'Feature bar description'],
        ];

        yield 'enable feature' => [$featuresDefinition, 'foo', false];
        yield 'disable feature' => [$featuresDefinition, 'bar', true];
        yield 'unknown feature' => [$featuresDefinition, 'unknown', false];
    }
}