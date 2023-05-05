<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Model;

/**
 * @immutable
 */
class Feature implements FeatureInterface
{
    public function __construct(
        private readonly string $key,
        private readonly bool $enabled,
        private readonly string $description = ''
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'enabled' => $this->enabled,
            'description' => $this->description,
        ];
    }
}
