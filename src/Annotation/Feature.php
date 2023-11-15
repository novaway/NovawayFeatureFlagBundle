<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Annotation;

use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @NamedArgumentConstructor()
 */
#[\Attribute]
class Feature
{
    /** @var string */
    private $feature;

    /** @var bool */
    private $enabled;

    /**
     * Constructor
     */
    public function __construct(string $name, bool $enabled = true)
    {
        $this->feature = $name;
        $this->enabled = $enabled;
    }

    /**
     * Get feature name
     */
    public function getFeature(): string
    {
        return $this->feature;
    }

    /**
     * Get if feature should be enabled or not
     */
    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return array{feature: string, enabled: bool}
     */
    public function toArray(): array
    {
        return [
            'feature' => $this->feature,
            'enabled' => $this->enabled,
        ];
    }
}
