<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functional;

use Novaway\Bundle\FeatureFlagBundle\Manager\DefaultFeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Model\Feature;
use Novaway\Bundle\FeatureFlagBundle\Storage\StorageInterface;

final class DefaultFeatureStorageTest extends WebTestCase
{
    /** @var StorageInterface */
    private $defaultRegisteredStorage;

    protected function setUp(): void
    {
        $this->defaultRegisteredStorage = static::getContainer()->get(DefaultFeatureManager::class);
    }

    public function testDefaultFeatureManagerIsFeatureManager(): void
    {
        static::assertInstanceOf(FeatureManager::class, $this->defaultRegisteredStorage);
    }

    public function testAccessAllRegisteredFeatures(): void
    {
        $features = $this->defaultRegisteredStorage->all();

        static::assertCount(4, $features);
        static::assertEquals(
            [
                'override' => new Feature('override', false),
                'foo' => new Feature('foo', true),
                'bar' => new Feature('bar', false, 'Bar feature description'),
                'env_var' => new Feature('env_var', false),
            ],
            $features,
        );
    }
}
