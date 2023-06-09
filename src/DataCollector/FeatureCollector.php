<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\DataCollector;

use Novaway\Bundle\FeatureFlagBundle\Manager\ChainedFeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Model\Feature;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class FeatureCollector extends DataCollector
{
    public function __construct(
        private readonly ChainedFeatureManager $manager,
    ) {
    }

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $totalFeatureCount = 0;
        $activeFeatureCount = 0;

        $features = [];
        foreach ($this->manager->getManagers() as $manager) {
            $features[$manager->getName()] = [];
            foreach ($manager->all() as $feature) {
                $features[$manager->getName()][] = $feature->toArray();

                if ($feature->isEnabled()) {
                    ++$activeFeatureCount;
                }
            }

            $totalFeatureCount += count($features[$manager->getName()]);
        }

        $this->data = [
            'features' => $features,
            'totalFeatureCount' => $totalFeatureCount,
            'activeFeaturesCount' => $activeFeatureCount,
        ];
    }

    /**
     * Get collected features
     *
     * @return Feature[]
     */
    public function getFeatures(): array
    {
        return $this->data['features'];
    }

    /**
     * Get collected active features
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
        return $this->data['totalFeatureCount'];
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
