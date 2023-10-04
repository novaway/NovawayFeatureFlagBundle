<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\DataCollector;

use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Model\FeatureInterface;
use Novaway\Bundle\FeatureFlagBundle\Storage\StorageInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class FeatureCollector extends DataCollector
{
    /** @var FeatureManager */
    private $manager;
    /** @var StorageInterface */
    private $storage;

    /**
     * Constructor
     */
    public function __construct(FeatureManager $manager, StorageInterface $storage)
    {
        $this->manager = $manager;
        $this->storage = $storage;
    }

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $features = $this->storage->all();
        $activeFeatureCount = count(
            array_filter(
                $features,
                function (FeatureInterface $feature): bool {
                    return $this->manager->isEnabled($feature->getKey());
                }
            )
        );

        $this->data = [
            'features' => $features,
            'activeFeaturesCount' => $activeFeatureCount,
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
        return $this->data['activeFeaturesCount'];
    }

    /**
     * Get collected features
     */
    public function getFeatureCount(): int
    {
        return count($this->data['features']);
    }

    public function getName(): string
    {
        return 'novaway_feature_flag.feature_collector';
    }

    public function reset(): void
    {
        $this->data = [];
    }
}
