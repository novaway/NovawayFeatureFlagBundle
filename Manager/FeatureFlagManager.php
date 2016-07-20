<?php

namespace Novaway\Bundle\FeatureFlagBundle\Manager;

class FeatureFlagManager
{
    /** @var array */
    private $features;

    /**
     * Constructor
     *
     * @param array $features
     */
    public function __construct(array $features = [])
    {
        $this->features = $features;
    }

    /**
     * Check if feature is enabled
     *
     * @param string $feature
     * @return bool
     */
    public function isEnabled($feature)
    {
        if (!isset($this->features[$feature])) {
            return false;
        }

        return (bool) $this->features[$feature];
    }

    /**
     * Check if feature is disabled
     *
     * @param string $feature
     * @return bool
     */
    public function isDisabled($feature)
    {
        return false === $this->isEnabled($feature);
    }
}
