<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Controller;

use Novaway\Bundle\FeatureFlagBundle\Manager\ChainedFeatureManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FeatureApiController
{
    public function __construct(
        private readonly ChainedFeatureManager $manager,
    ) {
    }

    public function all(): Response
    {
        $storagesFeatures = [];
        foreach ($this->manager->getManagers() as $manager) {
            $storagesFeatures[$manager->getName()] = [];
            foreach ($manager->all() as $feature) {
                $storagesFeatures[$manager->getName()][$feature->getKey()] = $feature->toArray();
            }
        }

        return new JsonResponse($storagesFeatures);
    }
}
