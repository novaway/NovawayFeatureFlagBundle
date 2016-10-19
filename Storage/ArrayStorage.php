<?php

namespace Novaway\Bundle\FeatureFlagBundle\Storage;

use Novaway\Bundle\FeatureFlagBundle\Model\Feature;
use Novaway\Bundle\FeatureFlagBundle\Model\FeatureInterface;

class ArrayStorage extends AbstractStorage
{
    /** @var FeatureInterface[] */
    private $features;

    /**
     * Constructor
     *
     * @param FeatureInterface[] $features
     */
    public function __construct(array $features = [])
    {
        $this->features = [];
        foreach ($features as $key => $feature) {
            $obj = new Feature($key, $feature['enabled']);
            if (isset($feature['description'])) {
                $obj->setDescription($feature['description']);
            }

            $this->features[$key] = $obj;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->features;
    }

    /**
     * {@inheritdoc}
     */
    public function check($feature)
    {
        if (!isset($this->features[$feature])) {
            return false;
        }

        return $this->features[$feature]->isEnabled();
    }
}
