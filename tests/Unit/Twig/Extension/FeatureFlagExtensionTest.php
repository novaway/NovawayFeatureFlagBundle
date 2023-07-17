<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\Twig\Extension;

use Novaway\Bundle\FeatureFlagBundle\Manager\ChainedFeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Twig\Extension\FeatureFlagExtension;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class FeatureFlagExtensionTest extends TestCase
{
    private FeatureFlagExtension $extension;

    protected function setUp(): void
    {
        $this->extension = new FeatureFlagExtension($this->createChainedFeatureManager());
    }

    #[DataProvider('features')]
    public function testIsFeatureEnabledReturnFeatureState(string $feature, bool $isEnabled): void
    {
        $twigFunctionCallable = $this->getTwigFunctionCallable('isFeatureEnabled');

        $this->assertSame($isEnabled, $twigFunctionCallable($feature));
    }

    #[DataProvider('features')]
    public function testIsDisabledMethod(string $feature, bool $isEnabled): void
    {
        $twigFunctionCallable = $this->getTwigFunctionCallable('isFeatureDisabled');

        $this->assertNotSame($isEnabled, $twigFunctionCallable($feature));
    }

    public static function features(): iterable
    {
        yield 'existing feature' => ['foo', true];
        yield 'non existing feature' => ['bar', false];
    }

    public function testShouldValidateName()
    {
        $this->assertSame('feature_flag_extension', $this->extension->getName());
    }

    private function createChainedFeatureManager(): ChainedFeatureManager
    {
        $featureManager = $this->createMock(FeatureManager::class);
        $featureManager->method('isEnabled')->willReturnCallback(fn (string $feature): bool => 'foo' === $feature);
        $featureManager->method('isDisabled')->willReturnCallback(fn (string $feature): bool => 'foo' !== $feature);

        return new ChainedFeatureManager([$featureManager]);
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
