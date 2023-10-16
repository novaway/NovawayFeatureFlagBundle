<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\ExpressionLanguage;

use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

final class FeatureFlagExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    /** @var FeatureManager */
    private $featureManager;

    public function __construct(FeatureManager $featureManager)
    {
        $this->featureManager = $featureManager;
    }

    public function getFunctions(): array
    {
        return [
            new ExpressionFunction(
                'is_feature_enabled',
                function ($str) {
                    return sprintf('$this->isFeatureEnabled(%s)', $str);
                },
                function ($arguments, $str) {
                    return $this->featureManager->isEnabled($str);
                }
            ),
            new ExpressionFunction(
                'is_feature_disabled',
                function ($str) {
                    return sprintf('$this->isFeatureDisabled(%s)', $str);
                },
                function ($arguments, $str) {
                    return $this->featureManager->isDisabled($str);
                },
            ),
        ];
    }
}
