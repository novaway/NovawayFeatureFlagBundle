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
final class FeatureFlag implements Feature
{
    /**
     * @param array<mixed> $options
     */
    public function __construct(
        private readonly string $key,
        private readonly bool $enabled,
        private readonly string $description = '',
        private readonly array $options = [],
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return array<mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'enabled' => $this->enabled,
            'description' => $this->description,
            'options' => $this->options,
        ];
    }
}
