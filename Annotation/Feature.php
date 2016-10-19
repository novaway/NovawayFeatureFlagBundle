<?php

namespace Novaway\Bundle\FeatureFlagBundle\Annotation;

/**
 * @Annotation
 */
class Feature
{
    /** @var string */
    private $feature;

    /** @var bool */
    private $enabled;

    /**
     * Constructor
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (!isset($values['value'])) {
            throw new \RuntimeException('Feature annotation value is required.');
        }

        $this->feature = (string) $values['value'];
        $this->enabled = !isset($values['enabled']) || (bool) $values['enabled'];
    }

    /**
     * Get feature name
     *
     * @return string
     */
    public function getFeature()
    {
        return $this->feature;
    }

    /**
     * Get if feature should be enabled or not
     *
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
}
