<?php

declare(strict_types=1);

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functional;

use Novaway\Bundle\FeatureFlagBundle\Model\Feature;
use Novaway\Bundle\FeatureFlagBundle\Storage\StorageInterface;

final class DefaultFeatureStorageTest extends WebTestCase
{
    /** @var StorageInterface */
    private $defaultRegisteredStorage;

    protected function setUp(): void
    {
        $this->defaultRegisteredStorage = static::getContainer()->get('novaway_feature_flag.manager.feature');
    }

    public function testDefaultFeatureManagerIsStorage(): void
    {
        static::assertInstanceOf(StorageInterface::class, $this->defaultRegisteredStorage);
    }

    /**
     * @dataProvider features
     */
    public function testIsFeatureEnabled(string $feature, bool $isEnabled): void
    {
        static::assertSame($isEnabled, $this->defaultRegisteredStorage->isEnabled($feature));
    }

    /**
     * @dataProvider features
     */
    public function testIsFeatureDisabled(string $feature, bool $isEnabled): void
    {
        static::assertNotSame($isEnabled, $this->defaultRegisteredStorage->isDisabled($feature));
    }

    /**
     * @dataProvider features
     */
    public function testCheckFeatureState(string $feature, bool $isEnabled): void
    {
        static::assertSame($isEnabled, $this->defaultRegisteredStorage->check($feature));
    }

    public function testAccessAllRegisteredFeatures(): void
    {
        $features = $this->defaultRegisteredStorage->all();

        static::assertCount(3, $features);
        static::assertEquals(
            new Feature('override', false),
            $features['override'],
        );
        static::assertEquals(
            new Feature('foo', true),
            $features['foo'],
        );
        static::assertEquals(
            new Feature('bar', false, 'Bar feature description'),
            $features['bar'],
        );
    }

    public function features(): iterable
    {
        yield 'overrided feature configuration' => ['override', false];
        yield 'feature enabled' => ['foo', true];
        yield 'feature disabled' => ['bar', false];
    }
}