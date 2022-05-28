<?php

namespace Novaway\Bundle\FeatureFlagBundle\DataCollector;

use Novaway\Bundle\FeatureFlagBundle\Model\Feature;
use Novaway\Bundle\FeatureFlagBundle\Model\FeatureInterface;
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
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $this->data = [
            'features' => $this->storage->all(),
        ];
    }

    /**
     * Get collected features
     *
     * @return FeatureInterface[]
     */
    public function getFeatures(): array
    {
        return $this->data['features'];
    }

    /**
     * Get collected features
     */
    public function getActiveFeatureCount(): int
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
     */
    public function getFeatureCount(): int
    {
        return count($this->data['features']);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'novaway_feature_flag.feature_collector';
    }

    /**
     * {@inheritdoc}
     */
    public function reset(): void
    {
        $this->data = [];
    }
}
