<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Manager;

interface FeatureManager
{
    /**
     * Check if feature is enabled
     */
    public function isEnabled(string $feature): bool;

    /**
     * Check if feature is disabled
     */
    public function isDisabled(string $feature): bool;
}
