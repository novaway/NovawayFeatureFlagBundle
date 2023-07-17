<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Storage;

use Novaway\Bundle\FeatureFlagBundle\Exception\FeatureUndefinedException;
use Novaway\Bundle\FeatureFlagBundle\Model\Feature;
use Novaway\Bundle\FeatureFlagBundle\Model\FeatureFlag;

final class ArrayStorage implements Storage
{
    private array $features;

    public function __construct(array $options = [])
    {
        $this->features = array_map(function (array $feature) {
            return new FeatureFlag($feature['name'], $feature['enabled'], $feature['description'] ?? '');
        }, $options['features']);
    }

    public function all(): array
    {
        return $this->features;
    }

    public function get(string $feature): Feature
    {
        if (!isset($this->features[$feature])) {
            throw new FeatureUndefinedException(sprintf('Feature \'%s\' not exists.', $feature));
        }

        return $this->features[$feature];
    }
}
