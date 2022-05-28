<?php

namespace Novaway\Bundle\FeatureFlagBundle\Model;

interface FeatureInterface
{
    /**
     * Get key
     */
    public function getKey(): string;

    /**
     * Get description
     */
    public function getDescription(): string;

    /**
     * Check if flag is enabled
     */
    public function isEnabled(): bool;
}
