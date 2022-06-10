<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Manager;

use Novaway\Bundle\FeatureFlagBundle\Model\FeatureInterface;
use Novaway\Bundle\FeatureFlagBundle\Storage\StorageInterface;

/**
 * @internal
 */
final class LegacyFeatureManager extends DefaultFeatureManager implements StorageInterface
{
    public function get(string $feature): FeatureInterface
    {
        return $this->storage->get($feature);
    }

    public function check(string $feature): bool
    {
        return $this->storage->check($feature);
    }
}
