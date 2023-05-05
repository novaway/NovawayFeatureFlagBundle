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

class ArrayStorage implements Storage
{
    /**
     * @param array<string, array{enabled: bool, description: ?string}> $data
     */
    public static function fromArray(array $data): self
    {
        ksort($data);

        $features = [];
        foreach ($data as $key => $feature) {
            $features[$key] = new Feature($key, $feature['enabled'], $feature['description'] ?? '');
        }

        return new self($features);
    }

    /**
     * @param FeatureInterface[] $features
     */
    public function __construct(
        private array $features = [],
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        return $this->features;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $feature): FeatureInterface
    {
        if (!isset($this->features[$feature])) {
            throw new FeatureUndefinedException("Feature '$feature' not exists.");
        }

        return $this->features[$feature];
    }
}
