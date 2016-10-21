<?php

namespace Novaway\Bundle\FeatureFlagBundle\Model;

class Feature implements FeatureInterface
{
    /** @var string */
    private $key;

    /** @var string */
    private $description;

    /** @var bool */
    private $enabled;

    /**
     * Constructor
     *
     * @param string $key
     * @param bool   $enabled
     * @param string $description
     */
    public function __construct($key, $enabled, $description = null)
    {
        $this->key = $key;
        $this->enabled = (bool) $enabled;
        $this->description = $description;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Feature
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->enabled;
    }
}
