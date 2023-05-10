<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\Twig\Extension;

use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Storage\Storage;
use Novaway\Bundle\FeatureFlagBundle\Twig\Extension\FeatureFlagExtension;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class FeatureFlagExtensionTest extends TestCase
{
    private FeatureFlagExtension $extension;

    protected function setUp(): void
    {
        $this->extension = new FeatureFlagExtension($this->createStorageMock());
    }

    /**
     * @dataProvider features
     */
    public function testIsFeatureEnabledReturnFeatureState(string $feature, bool $isEnabled): void
    {
        $twigFunctionCallable = $this->getTwigFunctionCallable('isFeatureEnabled');

        static::assertSame($isEnabled, $twigFunctionCallable($feature));
    }

    /**
     * @dataProvider features
     */
    public function testIsDisabledMethod(string $feature, bool $isEnabled): void
    {
        $twigFunctionCallable = $this->getTwigFunctionCallable('isFeatureDisabled');

        static::assertNotSame($isEnabled, $twigFunctionCallable($feature));
    }

    public function features(): iterable
    {
        yield 'existing feature' => ['foo', true];
        yield 'non existing feature' => ['bar', false];
    }

    /**
     * @return MockObject&Storage
     */
    private function createStorageMock(): MockObject
    {
        $storage = $this->createMock(FeatureManager::class);
        $storage->method('isEnabled')->willReturnCallback(fn (string $feature): bool => 'foo' === $feature);
        $storage->method('isDisabled')->willReturnCallback(fn (string $feature): bool => 'foo' !== $feature);

        return $storage;
    }

    private function getTwigFunctionCallable(string $functionName): callable
    {
        foreach ($this->extension->getFunctions() as $twigFunction) {
            if ($twigFunction->getName() === $functionName) {
                return $twigFunction->getCallable();
            }
        }

        $this->fail("No '$functionName' Twig function.");
    }
}
