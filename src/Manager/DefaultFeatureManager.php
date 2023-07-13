<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Manager;

use Novaway\Bundle\FeatureFlagBundle\Exception\FeatureUndefinedException;
use Novaway\Bundle\FeatureFlagBundle\Model\Feature;
use Novaway\Bundle\FeatureFlagBundle\Storage\Storage;

class DefaultFeatureManager implements FeatureManager
{
    public function __construct(
        private readonly string $name,
        private readonly Storage $storage,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return iterable<Feature>
     */
    public function all(): iterable
    {
        return $this->storage->all();
    }

    public function isEnabled(string $feature): bool
    {
        try {
            return $this->storage->get($feature)->isEnabled();
        } catch (FeatureUndefinedException) {
            return false;
        }
    }

    public function isDisabled(string $feature): bool
    {
        return false === $this->isEnabled($feature);
    }
}
