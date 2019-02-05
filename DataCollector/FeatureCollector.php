<?php

namespace Novaway\Bundle\FeatureFlagBundle\DataCollector;

use Novaway\Bundle\FeatureFlagBundle\Model\Feature;
use Novaway\Bundle\FeatureFlagBundle\Storage\StorageInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class FeatureCollector extends DataCollector
{
    /** @var StorageInterface */
    private $storage;

    /**
     * Constructor
     *
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'features' => $this->storage->all(),
        ];
    }

    /**
     * Get collected features
     *
     * @return array
     */
    public function getFeatures()
    {
        return $this->data['features'];
    }

    /**
     * Get collected features
     *
     * @return array
     */
    public function getActiveFeatureCount()
    {
        return count(
            array_filter(
                $this->data['features'],
                function (Feature $feature) {
                    return $feature->isEnabled();
                })
        );
    }

    /**
     * Get collected features
     *
     * @return array
     */
    public function getFeatureCount()
    {
        return count($this->data['features']);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'novaway_feature_flag.feature_collector';
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->data = [];
    }
}
