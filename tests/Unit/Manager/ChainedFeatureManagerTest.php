<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\Manager;

use Novaway\Bundle\FeatureFlagBundle\Manager\ChainedFeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Manager\DefaultFeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage;
use PHPUnit\Framework\TestCase;

final class ChainedFeatureManagerTest extends TestCase
{
    private const FEATURES_MANAGER1 = [
        'features' => [
            'feature_1' => ['name' => 'feature_1', 'enabled' => true],
            'feature_2' => ['name' => 'feature_2', 'enabled' => false],
        ],
    ];
    private const FEATURES_MANAGER2 = [
        'features' => ['feature_3' => ['name' => 'feature_3', 'enabled' => true]],
    ];

    private ChainedFeatureManager $manager;
    private DefaultFeatureManager $managerFoo;
    private DefaultFeatureManager $managerBar;

    protected function setUp(): void
    {
        $this->manager = new ChainedFeatureManager([
            $this->managerFoo = new DefaultFeatureManager('foo', new ArrayStorage(self::FEATURES_MANAGER1)),
            $this->managerBar = new DefaultFeatureManager('bar', new ArrayStorage(self::FEATURES_MANAGER2)),
        ]);
    }

    public function testAllFeaturesCanBeRetrievedFromAttachedStorage(): void
    {
        static::assertEquals([$this->managerFoo, $this->managerBar], $this->manager->getManagers());
    }

    public function testGetAll(): void
    {
        $features = [];
        foreach ($this->manager->all() as $featureName => $featureData) {
            $features[] = $featureName;
        }

        static::assertCount(3, $features);
        static::assertContainsEquals('feature_1', $features);
        static::assertContainsEquals('feature_2', $features);
        static::assertContainsEquals('feature_3', $features);
    }

    public function testIsFeatureEnabled(): void
    {
        static::assertTrue($this->manager->isEnabled('feature_1'));
        static::assertTrue($this->manager->isEnabled('feature_3'));
        static::assertFalse($this->manager->isEnabled('feature_2'));
    }

    public function testIsFeatureDisabled(): void
    {
        static::assertTrue($this->manager->isDisabled('feature_2'));
        static::assertFalse($this->manager->isDisabled('feature_1'));
        static::assertFalse($this->manager->isDisabled('feature_3'));
    }

    public function testGetName(): void
    {
        static::assertSame(ChainedFeatureManager::class, $this->manager->getName());
    }
}
