<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\Manager;

use Novaway\Bundle\FeatureFlagBundle\Manager\DefaultFeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage;
use Novaway\Bundle\FeatureFlagBundle\Storage\Storage;
use PHPUnit\Framework\TestCase;

final class DefaultFeatureManagerTest extends TestCase
{
    private const FEATURES = [
        'features' => [
            'feature_1' => ['name' => 'feature_1', 'enabled' => true],
            'feature_2' => ['name' => 'feature_2', 'enabled' => false],
        ],
    ];

    private DefaultFeatureManager $manager;
    private Storage $storage;

    protected function setUp(): void
    {
        $this->storage = new ArrayStorage(self::FEATURES);
        $this->manager = new DefaultFeatureManager('foo', $this->storage);
    }

    public function testAllFeaturesCanBeRetrieved(): void
    {
        $this->assertSame($this->storage->all(), $this->manager->all());
    }

    public function testIsFeatureEnabled(): void
    {
        $this->assertTrue($this->manager->isEnabled('feature_1'));
        $this->assertFalse($this->manager->isEnabled('feature_2'));
    }

    public function testIsFeatureDisabled(): void
    {
        $this->assertTrue($this->manager->isDisabled('feature_2'));
        $this->assertFalse($this->manager->isDisabled('feature_1'));
    }
}
