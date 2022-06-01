<?php

namespace Novaway\Bundle\FeatureFlagBundle\Storage;

abstract class AbstractStorage implements StorageInterface
{
    /**
     * {@inheritdoc}
     */
    public function isEnabled($feature)
    {
        return true === $this->check($feature);
    }

    /**
     * {@inheritdoc}
     */
    public function isDisabled($feature)
    {
        return false === $this->check($feature);
    }
}
