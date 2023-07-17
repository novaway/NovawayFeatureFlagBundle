<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Model;

class FeatureFlag implements Feature
{
    protected string $name = '';
    protected bool $enabled = true;
    protected string $description = '';

    public function __construct(
        string $name = '',
        bool $enabled = true,
        string $description = ''
    ) {
        $this->name = $name;
        $this->enabled = $enabled;
        $this->description = $description;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'enabled' => $this->enabled,
            'description' => $this->description,
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
