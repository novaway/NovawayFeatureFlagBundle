<?php

namespace Novaway\Bundle\FeatureFlagBundle\Storage;

abstract class AbstractStorage implements StorageInterface
{
    /**
     * {@inheritdoc}
     */
    public function isEnabled($feature): bool
    {
        return true === $this->check($feature);
    }

    /**
     * {@inheritdoc}
     */
    public function isDisabled($feature): bool
    {
        return false === $this->check($feature);
    }
}
