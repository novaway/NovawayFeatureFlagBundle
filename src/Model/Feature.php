<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Model;

interface Feature
{
    /**
     * Get name.
     */
    public function getName(): string;

    /**
     * Check if flag is enabled.
     */
    public function isEnabled(): bool;

    /**
     * Get description.
     */
    public function getDescription(): string;

    /**
     * @return array{name: string, enabled: bool, description: string}
     */
    public function toArray(): array;
}
