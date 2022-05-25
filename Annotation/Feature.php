<?php

namespace Novaway\Bundle\FeatureFlagBundle\Annotation;

use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @NamedArgumentConstructor()
 */
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
}
