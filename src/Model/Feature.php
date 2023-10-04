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
    /** @var string */
    private $key;

    /** @var string */
    private $description;

    /** @var bool */
    private $enabled;

    /**
     * Constructor
     */
    public function __construct(string $key, bool $enabled, string $description = null)
    {
        $this->key = $key;
        $this->enabled = $enabled;
        $this->description = $description ?? '';
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

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'enabled' => $this->enabled,
            'description' => $this->description,
        ];
    }
}
