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
    public function testShouldThrowExceptionBecauseFeaturesIsNotDefined()
    {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('Error while configure storage foo. Verify your configuration at "novaway_feature_flag.storages.foo.options". The required option "features" is missing.');

        $factory = $this->getFactory();
        $factory->createStorage('foo');
    }

    public function testShouldCreateStorage()
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
        $this->assertInstanceOf(ArrayStorage::class, $storage);
        $features = $storage->all();
        $this->assertIsArray($features);
        $this->assertCount(5, $features);

        $this->assertArrayHasKey('my_feature_1', $features);
        $this->assertArrayHasKey('my_feature_2', $features);
        $this->assertArrayHasKey('my_feature_3', $features);
        $this->assertArrayHasKey('my_feature_4', $features);
        $this->assertArrayHasKey('my_feature_5', $features);

        $this->assertInstanceOf(FeatureFlag::class, $features['my_feature_1']);
        $this->assertSame('my_feature_1', $features['my_feature_1']->getName());
        $this->assertTrue($features['my_feature_1']->isEnabled());
        $this->assertSame('', $features['my_feature_1']->getDescription());

        $this->assertInstanceOf(FeatureFlag::class, $features['my_feature_2']);
        $this->assertSame('my_feature_2', $features['my_feature_2']->getName());
        $this->assertTrue($features['my_feature_2']->isEnabled());
        $this->assertSame('', $features['my_feature_2']->getDescription());

        $this->assertInstanceOf(FeatureFlag::class, $features['my_feature_3']);
        $this->assertSame('my_feature_3', $features['my_feature_3']->getName());
        $this->assertFalse($features['my_feature_3']->isEnabled());
        $this->assertSame('', $features['my_feature_3']->getDescription());

        $this->assertInstanceOf(FeatureFlag::class, $features['my_feature_4']);
        $this->assertSame('my_feature_4', $features['my_feature_4']->getName());
        $this->assertTrue($features['my_feature_4']->isEnabled());
        $this->assertSame('Lorem Ipsum', $features['my_feature_4']->getDescription());

        $this->assertInstanceOf(FeatureFlag::class, $features['my_feature_5']);
        $this->assertSame('my_feature_5', $features['my_feature_5']->getName());
        $this->assertTrue($features['my_feature_5']->isEnabled());
        $this->assertSame('', $features['my_feature_5']->getDescription());
    }

    private function getFactory(): ArrayStorageFactory
    {
        return new ArrayStorageFactory();
    }
}
