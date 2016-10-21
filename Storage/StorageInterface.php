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
    public function all();

    /**
     * Check if feature is enabled or not
     *
     * @param string $feature
     * @return bool
     */
    public function check($feature);

    /**
     * Check if feature is enabled
     *
     * @param string $feature
     * @return bool
     */
    public function isEnabled($feature);

    /**
     * Check if feature is disabled
     *
     * @param string $feature
     * @return bool
     */
    public function isDisabled($feature);
}
