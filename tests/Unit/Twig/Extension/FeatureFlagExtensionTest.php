<?php

declare(strict_types=1);

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\Twig\Extension;

use Novaway\Bundle\FeatureFlagBundle\Storage\StorageInterface;
use Novaway\Bundle\FeatureFlagBundle\Twig\Extension\FeatureFlagExtension;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class FeatureFlagExtensionTest extends TestCase
{
    /** @var FeatureFlagExtension */
    private $extension;

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
     * @return MockObject&StorageInterface
     */
    private function createStorageMock(): MockObject
    {
        $storage = $this->createMock(StorageInterface::class);
        $storage->method('all')->willReturn(['foo']);
        $storage->method('isEnabled')->willReturnCallback(function (string $feature): bool {
            return 'foo' === $feature;
        });
        $storage->method('isDisabled')->willReturnCallback(function (string $feature): bool {
            return 'foo' !== $feature;
        });

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