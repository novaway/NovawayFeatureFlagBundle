<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Storage;

use Novaway\Bundle\FeatureFlagBundle\Model\FeatureInterface;

interface StorageInterface
{
    /**
     * Return all features
     *
     * @return FeatureInterface[]
     */
    public function all(): array;

    /**
     * Check if feature is enabled or not
     *
     * @param string $feature
     */
    public function check($feature): bool;

    /**
     * Check if feature is enabled
     *
     * @param string $feature
     */
    public function isEnabled($feature): bool;

    /**
     * Check if feature is disabled
     *
     * @param string $feature
     */
    public function isDisabled($feature): bool;
}
