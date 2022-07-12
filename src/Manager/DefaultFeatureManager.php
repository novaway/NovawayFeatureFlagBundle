<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Manager;

use Novaway\Bundle\FeatureFlagBundle\Model\FeatureInterface;
use Novaway\Bundle\FeatureFlagBundle\Storage\FeatureUndefinedException;
use Novaway\Bundle\FeatureFlagBundle\Storage\StorageInterface;

class DefaultFeatureManager implements FeatureManager
{
    /** @var StorageInterface */
    protected $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return FeatureInterface[]
     */
    public function all(): array
    {
        return $this->storage->all();
    }

    public function isEnabled(string $feature): bool
    {
        try {
            return $this->storage->get($feature)->isEnabled();
        } catch (FeatureUndefinedException $e) {
            return false;
        }
    }

    public function isDisabled(string $feature): bool
    {
        return false === $this->isEnabled($feature);
    }
}
