<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\Manager;

use Novaway\Bundle\FeatureFlagBundle\Checker\ExpressionLanguageChecker;
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
            'feature_3' => ['name' => 'feature_3', 'enabled' => true, 'expression' => 'foo'],
        ],
    ];

    private Storage $storage;
    private ExpressionLanguageChecker $expressionLanguageChecker;

    protected function setUp(): void
    {
        $this->storage = new ArrayStorage(self::FEATURES);
        $this->expressionLanguageChecker = $this->createMock(ExpressionLanguageChecker::class);
    }

    public function testAllFeaturesCanBeRetrieved(): void
    {
        $this->expressionLanguageChecker->expects($this->never())->method('isGranted');

        $manager = $this->getManager();

        static::assertEquals($this->storage->all(), $manager->all());
    }

    public function testIsFeatureEnabled(): void
    {
        $this->expressionLanguageChecker->expects($this->once())->method('isGranted')->with('foo')->willReturn(true);

        $manager = $this->getManager();

        static::assertSame('foo', $manager->getName());
        static::assertTrue($manager->isEnabled('feature_1'));
        static::assertFalse($manager->isEnabled('feature_2'));
        static::assertTrue($manager->isEnabled('feature_3'));
    }

    public function testIsFeatureDisabled(): void
    {
        $this->expressionLanguageChecker->expects($this->once())->method('isGranted')->with('foo')->willReturn(true);

        $manager = $this->getManager();

        static::assertSame('foo', $manager->getName());
        static::assertFalse($manager->isDisabled('feature_1'));
        static::assertTrue($manager->isDisabled('feature_2'));
        static::assertFalse($manager->isDisabled('feature_3'));
    }

    private function getManager(): DefaultFeatureManager
    {
        return new DefaultFeatureManager('foo', $this->storage, $this->expressionLanguageChecker);
    }
}
