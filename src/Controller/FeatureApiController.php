<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Controller;

use Novaway\Bundle\FeatureFlagBundle\Model\FeatureInterface;
use Novaway\Bundle\FeatureFlagBundle\Storage\FeatureUndefinedException;
use Novaway\Bundle\FeatureFlagBundle\Storage\StorageInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FeatureApiController
{
    /** @var StorageInterface */
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function all(): Response
    {
        /** @var array<string, array<string, mixed>> $features */
        $features = [];

        foreach ($this->storage->all() as $feature) {
            $features[$feature->getKey()] = $this->featureAsArray($feature);
        }

        return new JsonResponse($features);
    }

    public function get(string $key): Response
    {
        try {
            $feature = $this->storage->get($key);
        } catch (FeatureUndefinedException $e) {
            return new JsonResponse(
                [
                    'type' => 'undefined-feature',
                    'title' => $e->getMessage(),
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse($this->featureAsArray($feature));
    }

    /**
     * @return array<string, mixed>
     */
    private function featureAsArray(FeatureInterface $feature): array
    {
        if (method_exists($feature, 'toArray')) {
            return $feature->toArray();
        }

        return [
            'key' => $feature->getKey(),
            'description' => $feature->getDescription(),
            'enabled' => $feature->isEnabled(),
        ];
    }
}
