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
use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Model\FeatureFlag;
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
    private FeatureManager $managerFoo;
    private FeatureManager $managerBar;

    protected function setUp(): void
    {
        $this->managerFoo = $this->createMock(FeatureManager::class);
        $this->managerBar = $this->createMock(FeatureManager::class);

        $this->manager = new ChainedFeatureManager(new \ArrayObject([$this->managerFoo, $this->managerBar]));
    }

    public function testAllFeaturesCanBeRetrievedFromAttachedStorage(): void
    {
        static::assertEquals([$this->managerFoo, $this->managerBar], (array) $this->manager->getManagers());
    }

    public function testGetAll(): void
    {
        $this->managerFoo
            ->expects($this->once())
            ->method('all')
            ->willReturn(array_map(function (array $data) {
                return new FeatureFlag(key: $data['name'], enabled: $data['enabled']);
            }, self::FEATURES_MANAGER1['features']))
        ;
        $this->managerBar
            ->expects($this->once())
            ->method('all')
            ->willReturn(array_map(function (array $data) {
                return new FeatureFlag(key: $data['name'], enabled: $data['enabled']);
            }, self::FEATURES_MANAGER2['features']))
        ;

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
        $matcher = $this->exactly(3);
        $this->managerFoo
            ->expects($matcher)
            ->method('isEnabled')
            ->willReturnCallback(function (string $name) use ($matcher) {
                match ($matcher->numberOfInvocations()) {
                    1 => $this->assertSame('feature_1', $name),
                    2 => $this->assertSame('feature_3', $name),
                    3 => $this->assertSame('feature_2', $name),
                };

                return match ($matcher->numberOfInvocations()) {
                    1, 2 => true,
                    3 => false,
                };
            })
        ;
        $this->managerBar->expects($this->once())->method('isEnabled')->with('feature_2')->willReturn(false);

        static::assertTrue($this->manager->isEnabled('feature_1'));
        static::assertTrue($this->manager->isEnabled('feature_3'));
        static::assertFalse($this->manager->isEnabled('feature_2'));
    }

    public function testIsFeatureDisabled(): void
    {
        $matcher = $this->exactly(3);
        $this->managerFoo
            ->expects($matcher)
            ->method('isEnabled')
            ->willReturnCallback(function (string $name) use ($matcher) {
                match ($matcher->numberOfInvocations()) {
                    1 => $this->assertSame('feature_1', $name),
                    2 => $this->assertSame('feature_3', $name),
                    3 => $this->assertSame('feature_2', $name),
                };

                return match ($matcher->numberOfInvocations()) {
                    1, 2 => true,
                    3 => false,
                };
            })
        ;
        $this->managerBar->expects($this->once())->method('isEnabled')->with('feature_2')->willReturn(false);

        static::assertFalse($this->manager->isDisabled('feature_1'));
        static::assertFalse($this->manager->isDisabled('feature_3'));
        static::assertTrue($this->manager->isDisabled('feature_2'));
    }

    public function testGetName(): void
    {
        static::assertSame(ChainedFeatureManager::class, $this->manager->getName());
    }
}
