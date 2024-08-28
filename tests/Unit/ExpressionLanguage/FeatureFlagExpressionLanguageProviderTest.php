<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\ExpressionLanguage;

use Novaway\Bundle\FeatureFlagBundle\ExpressionLanguage\FeatureFlagExpressionLanguageProvider;
use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class FeatureFlagExpressionLanguageProviderTest extends TestCase
{
    /** @var ExpressionLanguage */
    private $expressionLanguage;
    /** @var FeatureManager&MockObject */
    private $featureManager;

    protected function setUp(): void
    {
        $this->featureManager = $this->createMock(FeatureManager::class);

        $this->expressionLanguage = new class($this->featureManager) extends ExpressionLanguage {
            public function __construct($featureManager, ?CacheItemPoolInterface $cache = null, array $providers = [])
            {
                array_unshift($providers, new FeatureFlagExpressionLanguageProvider($featureManager));

                parent::__construct($cache, $providers);
            }
        };
    }

    /**
     * @dataProvider isFeatureEnabled
     */
    public function testIsFeatureEnabledFunctionForwardCallToManager(string $featureName, bool $expectedValue)
    {
        $this->featureManager->expects(self::once())
            ->method('isEnabled')
            ->with($featureName)
            ->willReturn($expectedValue);

        static::assertSame($expectedValue, $this->expressionLanguage->evaluate('is_feature_enabled("'.$featureName.'")'));
    }

    /**
     * @dataProvider isFeatureDisabled
     */
    public function testIsFeatureDisabledFunctionForwardCallToManager(string $featureName, bool $expectedValue)
    {
        $this->featureManager->expects(self::once())
            ->method('isDisabled')
            ->with($featureName)
            ->willReturn($expectedValue);

        static::assertSame($expectedValue, $this->expressionLanguage->evaluate('is_feature_disabled("'.$featureName.'")'));
    }

    public function isFeatureEnabled()
    {
        yield ['foo', true];
        yield ['foo', false];
        yield ['bar', true];
        yield ['bar', false];
    }

    public function isFeatureDisabled()
    {
        yield ['foo', true];
        yield ['foo', false];
        yield ['bar', true];
        yield ['bar', false];
    }
}
