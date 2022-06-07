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
     * Return feature flag
     */
    public function get(string $feature): FeatureInterface;

    /**
     * Check if feature is enabled or not
     *
     * @deprecated This method is deprecated since 2.3.0 and will be removed in the next major release.
     *             Please use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager instead.
     */
    public function check(string $feature): bool;

    /**
     * Check if feature is enabled
     *
     * @deprecated This method is deprecated since 2.3.0 and will be removed in the next major release.
     *             Please use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager instead.
     */
    public function isEnabled(string $feature): bool;

    /**
     * Check if feature is disabled
     *
     * @deprecated This method is deprecated since 2.3.0 and will be removed in the next major release.
     *             Please use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager instead.
     */
    public function isDisabled(string $feature): bool;
}
