<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\EventListener;

use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class FeatureListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly FeatureManager $manager,
    ) {
    }

    /**
     * Check if a feature requirement is defined
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        if (!is_iterable($features = $request->attributes->get('_features'))) {
            return;
        }

        foreach ($features as $featureConfiguration) {
            if ($featureConfiguration['enabled'] !== $this->manager->isEnabled($featureConfiguration['feature'])) {
                throw new NotFoundHttpException();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
