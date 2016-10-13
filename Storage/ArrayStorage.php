<?php

namespace Novaway\Bundle\FeatureFlagBundle\Storage;

class ArrayStorage implements StorageInterface
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
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->features;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled($feature)
    {
        if (!isset($this->features[$feature])) {
            return false;
        }

        return (bool) $this->features[$feature];
    }

    /**
     * {@inheritdoc}
     */
    public function isDisabled($feature)
    {
        return false === $this->isEnabled($feature);
    }
}
