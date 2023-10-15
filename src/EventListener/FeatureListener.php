<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\EventListener;

use Novaway\Bundle\FeatureFlagBundle\Factory\ExceptionFactory;
use Novaway\Bundle\FeatureFlagBundle\Manager\ChainedFeatureManager;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class FeatureListener implements EventSubscriberInterface
{
    /**
     * @param ServiceLocator<ExceptionFactory> $factories
     */
    public function __construct(
        private readonly ChainedFeatureManager $manager,
        #[TaggedLocator(ExceptionFactory::class)]
        private readonly ServiceLocator $factories,
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
                throw $this->createFeatureException($featureConfiguration);
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    /**
     * @param array{
     *     feature: string,
     *     enabled: bool,
     *     exceptionClass: class-string<\Throwable>|null,
     *     exceptionFactory: class-string<ExceptionFactory>|null,
     * } $featureConfiguration
     */
    public function createFeatureException(array $featureConfiguration): \Throwable
    {
        if (($featureConfiguration['exceptionClass'] ?? null) !== null) {
            return new $featureConfiguration['exceptionClass']();
        }

        if (($featureConfiguration['exceptionFactory'] ?? null) !== null) {
            $factory = $this->factories->get($featureConfiguration['exceptionFactory']);

            return $factory->create();
        }

        return new NotFoundHttpException();
    }
}
