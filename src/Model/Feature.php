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
     * Get key
     */
    public function getKey(): string;

    /**
     * Get description
     */
    public function getDescription(): string;

    /**
     * Check if flag is enabled
     */
    public function isEnabled(): bool;
}
