<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\DataCollector;

use Novaway\Bundle\FeatureFlagBundle\DataCollector\FeatureCollector;
use Novaway\Bundle\FeatureFlagBundle\Manager\ChainedFeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Model\FeatureFlag;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @codingStandardsIgnoreFile
 *
 * @SuppressWarnings(PHPMD)
 */
class FeatureCollectorTest extends TestCase
{
    private FeatureManager $defaultManager;
    private FeatureManager $manager;

    protected function setUp(): void
    {
        $this->defaultManager = $this->createMock(FeatureManager::class);
        $this->manager = $this->createMock(FeatureManager::class);
    }

    public function testShouldCollectData(): void
    {
        $feature1 = new FeatureFlag('foo');
        $feature2 = new FeatureFlag('bar', false);
        $feature3 = new FeatureFlag('baz', true);

        $this->defaultManager->expects($this->exactly(4))->method('getName')->willReturn('defaultManager');
        $this->defaultManager->expects($this->once())->method('all')->willReturn([$feature1, $feature2]);

        $this->manager->expects($this->exactly(3))->method('getName')->willReturn('fooManager');
        $this->manager->expects($this->once())->method('all')->willReturn([$feature3]);

        $request = $this->createMock(Request::class);
        $response = $this->createMock(Response::class);

        $this->assertTrue(true);

        $collector = $this->getCollector();
        $collector->reset();
        $collector->collect($request, $response);

        $this->assertSame(
            [
                'defaultManager' => [
                    [
                        'name' => 'foo',
                        'enabled' => true,
                        'description' => '',
                    ],
                    [
                        'name' => 'bar',
                        'enabled' => false,
                        'description' => '',
                    ],
                ],
                'fooManager' => [
                    [
                        'name' => 'baz',
                        'enabled' => true,
                        'description' => '',
                    ],
                ],
            ],
            $collector->getFeatures()
        );

        $this->assertSame(2, $collector->getActiveFeatureCount());
        $this->assertSame(3, $collector->getFeatureCount());
        $this->assertSame('novaway_feature_flag.feature_collector', $collector->getName());
    }

    private function getCollector(): FeatureCollector
    {
        return new FeatureCollector(new ChainedFeatureManager(new \ArrayObject([$this->defaultManager, $this->manager])));
    }
}
