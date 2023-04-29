<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Storage;

use Novaway\Bundle\FeatureFlagBundle\Model\FeatureInterface;

interface Storage
{
    /**
     * Return all features
     *
     * @return FeatureInterface[]
     */
    public function all(): array;

    /**
     * Return feature flag
     *
     * @throws FeatureUndefinedException If the feature doesn't exist
     */
    public function get(string $feature): FeatureInterface;
}
