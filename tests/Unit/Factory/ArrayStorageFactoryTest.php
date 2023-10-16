<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\Factory;

use Novaway\Bundle\FeatureFlagBundle\Exception\ConfigurationException;
use Novaway\Bundle\FeatureFlagBundle\Factory\ArrayStorageFactory;
use Novaway\Bundle\FeatureFlagBundle\Model\FeatureFlag;
use Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage;
use PHPUnit\Framework\TestCase;

/**
 * @codingStandardsIgnoreFile
 *
 * @SuppressWarnings(PHPMD)
 */
class ArrayStorageFactoryTest extends TestCase
{
    public function testShouldThrowExceptionBecauseFeaturesIsNotDefined(): void
    {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('Error while configure storage foo. Verify your configuration at "novaway_feature_flag.storages.foo.options". The required option "features" is missing.');

        $factory = $this->getFactory();
        $factory->createStorage('foo');
    }

    public function testShouldCreateStorage(): void
    {
        $options = [
            'features' => [
                'my_feature_1' => [],
                'my_feature_2' => null,
                'my_feature_3' => false,
                'my_feature_4' => ['enabled' => true, 'description' => 'Lorem Ipsum'],
                'my_feature_5' => ['enabled' => true],
            ],
        ];

        $factory = $this->getFactory();
        $storage = $factory->createStorage('foo', $options);
        static::assertInstanceOf(ArrayStorage::class, $storage);
        $features = $storage->all();
        static::assertIsArray($features);
        static::assertCount(5, $features);

        static::assertArrayHasKey('my_feature_1', $features);
        static::assertArrayHasKey('my_feature_2', $features);
        static::assertArrayHasKey('my_feature_3', $features);
        static::assertArrayHasKey('my_feature_4', $features);
        static::assertArrayHasKey('my_feature_5', $features);

        static::assertInstanceOf(FeatureFlag::class, $features['my_feature_1']);
        static::assertSame('my_feature_1', $features['my_feature_1']->getKey());
        static::assertTrue($features['my_feature_1']->isEnabled());
        static::assertSame('', $features['my_feature_1']->getDescription());

        static::assertInstanceOf(FeatureFlag::class, $features['my_feature_2']);
        static::assertSame('my_feature_2', $features['my_feature_2']->getKey());
        static::assertTrue($features['my_feature_2']->isEnabled());
        static::assertSame('', $features['my_feature_2']->getDescription());

        static::assertInstanceOf(FeatureFlag::class, $features['my_feature_3']);
        static::assertSame('my_feature_3', $features['my_feature_3']->getKey());
        static::assertFalse($features['my_feature_3']->isEnabled());
        static::assertSame('', $features['my_feature_3']->getDescription());

        static::assertInstanceOf(FeatureFlag::class, $features['my_feature_4']);
        static::assertSame('my_feature_4', $features['my_feature_4']->getKey());
        static::assertTrue($features['my_feature_4']->isEnabled());
        static::assertSame('Lorem Ipsum', $features['my_feature_4']->getDescription());

        static::assertInstanceOf(FeatureFlag::class, $features['my_feature_5']);
        static::assertSame('my_feature_5', $features['my_feature_5']->getKey());
        static::assertTrue($features['my_feature_5']->isEnabled());
        static::assertSame('', $features['my_feature_5']->getDescription());
    }

    private function getFactory(): ArrayStorageFactory
    {
        return new ArrayStorageFactory();
    }
}
