<?php

namespace Novaway\Bundle\FeatureFlagBundle\Storage;

interface StorageInterface
{
    /**
     * Return all features
     *
     * @return array
     */
    public function all();

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
