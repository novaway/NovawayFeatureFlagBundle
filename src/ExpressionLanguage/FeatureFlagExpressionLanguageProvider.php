<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\ExpressionLanguage;

use Novaway\Bundle\FeatureFlagBundle\Manager\ChainedFeatureManager;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

final class FeatureFlagExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    public function __construct(
        private readonly ChainedFeatureManager $featureManager,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new ExpressionFunction(
                'is_feature_enabled',
                fn ($str) => sprintf('$this->isFeatureEnabled(%s)', $str),
                fn ($arguments, $str) => $this->featureManager->isEnabled($str),
            ),
            new ExpressionFunction(
                'is_feature_disabled',
                fn ($str) => sprintf('$this->isFeatureDisabled(%s)', $str),
                fn ($arguments, $str) => $this->featureManager->isDisabled($str),
            ),
        ];
    }
}
