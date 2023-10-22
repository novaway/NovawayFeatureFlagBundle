<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Manager;

final class ChainedFeatureManager implements FeatureManager
{
    /**
     * @param iterable<FeatureManager> $featureManagers
     */
    public function __construct(
        private readonly iterable $featureManagers,
    ) {
    }

    /**
     * @return iterable<FeatureManager>
     */
    public function getManagers(): iterable
    {
        return $this->featureManagers;
    }

    public function all(): iterable
    {
        foreach ($this->featureManagers as $featureManager) {
            yield from $featureManager->all();
        }
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

    public function getName(): string
    {
        return ChainedFeatureManager::class;
    }
}
