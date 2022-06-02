<?php

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

    /**
     * {@inheritdoc}
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
