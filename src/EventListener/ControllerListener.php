<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\EventListener;

use Novaway\Bundle\FeatureFlagBundle\Attribute\Feature;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ControllerListener implements EventSubscriberInterface
{
    /**
     * Update the request object to apply attributes configuration
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();
        if (is_object($controller) && method_exists($controller, '__invoke')) {
            $controller = [$controller, '__invoke'];
        }

        if (!is_array($controller)) {
            return;
        }

        /** @var class-string $className */
        $className = $controller[0]::class;
        $class = new \ReflectionClass($className);
        $method = $class->getMethod($controller[1]);

        $features = [];
        foreach ($this->resolveFeatures($class, $method) as $key => $feature) {
            if (isset($features[$key])) {
                throw new \UnexpectedValueException(sprintf('Feature "%s" is defined more than once in %s::%s', $key, $className, $controller[1]));
            }

            $features[$key] = $feature;
        }

        $request = $event->getRequest();
        $request->attributes->set('_features', array_merge(
            (array) $request->attributes->get('_features', []),
            $features
        ));
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

    /**
     * @return array<string, array{feature: string, enabled: bool}>
     */
    private function resolveFeatures(\ReflectionClass $class, \ReflectionMethod $method): iterable
    {
        foreach ($class->getAttributes(Feature::class) as $attribute) {
            /** @var Feature $feature */
            $feature = $attribute->newInstance();

            yield $feature->name => $feature->toArray();
        }

        foreach ($method->getAttributes(Feature::class) as $attribute) {
            /** @var Feature $feature */
            $feature = $attribute->newInstance();

            yield $feature->name => $feature->toArray();
        }
    }
}
