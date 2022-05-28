<?php

namespace Novaway\Bundle\FeatureFlagBundle\EventListener;

use Novaway\Bundle\FeatureFlagBundle\Storage\StorageInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class FeatureListener implements EventSubscriberInterface
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
     * Check if a feature requirement is defined
     *
     * @param ControllerEvent $event
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        if (!is_iterable($features = $request->attributes->get('_features'))) {
            return;
        }

        foreach ($features as $featureConfiguration) {
            if ($featureConfiguration['enabled'] !== $this->storage->check($featureConfiguration['feature'])) {
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
