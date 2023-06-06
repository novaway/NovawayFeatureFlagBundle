<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\DataCollector;

use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Model\Feature;
use Novaway\Bundle\FeatureFlagBundle\Storage\Storage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class FeatureCollector extends DataCollector
{
    public function __construct(
        private readonly FeatureManager $manager,
        private readonly Storage $storage,
    ) {
    }

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $features = $this->storage->all();
        $activeFeatureCount = count(
            array_filter(
                $features,
                fn (Feature $feature): bool => $this->manager->isEnabled($feature->getKey())
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
     * @return Feature[]
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
        return is_countable($this->data['features']) ? count($this->data['features']) : 0;
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
