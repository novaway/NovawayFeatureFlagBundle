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
    public function __construct(
        private readonly string $key,
        private readonly bool $enabled,
        private readonly ?string $expression = null,
        private readonly string $description = ''
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

    public function getExpression(): ?string
    {
        return $this->expression;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'enabled' => $this->enabled,
            'expression' => $this->expression,
            'description' => $this->description,
        ];
    }
}
