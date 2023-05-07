<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Manager;

final class ChainedFeatureManager
{
    public function __construct(
        private readonly iterable $featureManagers,
    ) {
    }

    public function isEnabled(string $feature): bool
    {
        foreach ($this->featureManagers as $featureManager) {
            if ($featureManager->isEnabled($feature)) {
                return true;
            }
        }

        return false;
    }

    public function isDisabled(string $feature): bool
    {
        return false === $this->isEnabled($feature);
    }
}
