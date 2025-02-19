<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Storage;

use Novaway\Bundle\FeatureFlagBundle\Model\Feature;
use Novaway\Bundle\FeatureFlagBundle\Model\FeatureInterface;

class ArrayStorage extends AbstractStorage
{
    /** @var FeatureInterface[] */
    private $features;

    /**
     * Constructor
     *
     * @param array<string, array{enabled: bool, description: ?string, options: ?array<mixed>} > $features
     */
    public function __construct(array $features = [])
    {
        ksort($features);

        $this->features = [];
        foreach ($features as $key => $feature) {
            $this->features[$key] = new Feature($key, $feature['enabled'], $feature['description'] ?? '', $feature['options'] ?? []);
        }
    }

    public function all(): array
    {
        return $this->features;
    }

    public function get(string $feature): FeatureInterface
    {
        if (!isset($this->features[$feature])) {
            throw new FeatureUndefinedException("Feature '$feature' not exists.");
        }

        return $this->features[$feature];
    }
}
