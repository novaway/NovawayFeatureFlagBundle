<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Storage;

abstract class AbstractStorage implements StorageInterface
{
    /**
     * {@inheritdoc}
     */
    public function isEnabled(string $feature): bool
    {
        return true === $this->check($feature);
    }

    /**
     * {@inheritdoc}
     */
    public function isDisabled(string $feature): bool
    {
        return false === $this->check($feature);
    }
}
