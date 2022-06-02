<?php

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
     * @return bool
     */
    public function check($feature): bool;

    /**
     * Check if feature is enabled
     *
     * @param string $feature
     * @return bool
     */
    public function isEnabled($feature): bool;

    /**
     * Check if feature is disabled
     *
     * @param string $feature
     * @return bool
     */
    public function isDisabled($feature): bool;
}
