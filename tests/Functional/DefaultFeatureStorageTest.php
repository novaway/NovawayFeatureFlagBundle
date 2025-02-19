<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

        static::assertCount(4, $features);
        static::assertEquals(
            [
                'override' => new Feature('override', false),
                'foo' => new Feature('foo', true),
                'bar' => new Feature('bar', false, 'Bar feature description', [
                    'foo' => 'bar',
                    'tableau' => ['test' => 'zaza'],
                ]),
                'env_var' => new Feature('env_var', false),
            ],
            $features,
        );
    }

    public function features(): iterable
    {
        yield 'overrided feature configuration' => ['override', false];
        yield 'feature enabled' => ['foo', true];
        yield 'feature disabled' => ['bar', false];
        yield 'unknow feature is considered as disabled' => ['my-unknow-feature', false];
        yield 'environment variable flag' => ['env_var', false];
    }
}
