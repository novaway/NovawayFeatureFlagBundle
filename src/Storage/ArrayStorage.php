<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Storage;

use Novaway\Bundle\FeatureFlagBundle\Model\Feature;
use Novaway\Bundle\FeatureFlagBundle\Model\FeatureFlag;

class ArrayStorage implements Storage
{
    public static function create(array $options): self
    {
        return self::fromArray($options['features'] ?? []);
    }

    /**
     * @param array<string, bool|array{enabled: bool, description: ?string}> $data
     */
    public static function fromArray(array $data): self
    {
        ksort($data);

        $features = [];
        foreach ($data as $key => $feature) {
            if (is_bool($feature)) {
                $feature = [
                    'enabled' => $feature,
                    'description' => '',
                ];
            }

            $features[$key] = new FeatureFlag($key, $feature['enabled'], $feature['description'] ?? '');
        }

        return new self($features);
    }

    /**
     * @param Feature[] $features
     */
    public function __construct(
        private array $features = [],
    ) {
    }

    public function all(): array
    {
        return $this->features;
    }

    public function get(string $feature): Feature
    {
        if (!isset($this->features[$feature])) {
            throw new FeatureUndefinedException("Feature '$feature' not exists.");
        }

        return $this->features[$feature];
    }
}
