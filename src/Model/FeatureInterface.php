<?php

namespace Novaway\Bundle\FeatureFlagBundle\Model;

interface FeatureInterface
{
    /**
     * Get key
     *
     * @return string
     */
    public function getKey();

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Check if flag is enabled
     *
     * @return bool
     */
    public function isEnabled();
}
