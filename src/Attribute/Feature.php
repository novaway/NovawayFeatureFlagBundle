<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Attribute;

use Attribute;

#[Attribute]
class Feature
{
    public function __construct(
        public readonly string $name,
        public readonly bool $enabled = true
    ) {
    }

    /**
     * @return array{feature: string, enabled: bool}
     */
    public function toArray(): array
    {
        return [
            'feature' => $this->name,
            'enabled' => $this->enabled,
        ];
    }
}
