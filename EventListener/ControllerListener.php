<?php

namespace Novaway\Bundle\FeatureFlagBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Novaway\Bundle\FeatureFlagBundle\Annotation\Feature;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ControllerListener implements EventSubscriberInterface
{
    /** @var Reader */
    private $annotationReader;

    /**
     * Constructor
     */
    public function __construct(Reader $reader)
    {
        $this->annotationReader = $reader;
    }

    /**
     * Update the request object to apply annotation configuration
     */
    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();
        if (!is_array($controller) && method_exists($controller, '__invoke')) {
            $controller = [$controller, '__invoke'];
        }

        if (!is_array($controller)) {
            return;
        }

        $className = get_class($controller[0]);
        $object = new \ReflectionClass($className);
        $method = $object->getMethod($controller[1]);

        $features = [];
        foreach ($this->resolveFeatures($method) as $key => $feature) {
            if (isset($features[$key])) {
                throw new \UnexpectedValueException(sprintf('Feature "%s" is defined more than once in %s::%s', $key, $className, $controller[1]));
            }

            $features[$key] = $feature;
        }

        $request = $event->getRequest();
        $request->attributes->set('_features', array_merge(
            $request->attributes->get('_features', []),
            $features
        ));
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    /**
     * @return iterable<array<string, array{feature: string, enabled: bool}>>
     */
    private function resolveFeatures(\ReflectionMethod $method): iterable
    {
        yield from $this->featuresFromAttributes($method);

        yield from $this->featuresFromAnnotations($method);
    }

    /**
     * @return iterable<array<string, array{feature: string, enabled: bool}>>
     */
    private function featuresFromAttributes(\ReflectionMethod $method): iterable
    {
        if (\PHP_VERSION_ID < 80000) {
            return [];
        }

        foreach ($method->getAttributes(Feature::class) as $attribute) {
            /** @var Feature $feature */
            $feature = $attribute->newInstance();

            yield $feature->getFeature() => $feature->toArray();
        }
    }

    /**
     * @return iterable<array<string, array{feature: string, enabled: bool}>>
     */
    private function featuresFromAnnotations(\ReflectionMethod $method): iterable
    {
        foreach ($this->annotationReader->getMethodAnnotations($method) as $annotation) {
            if (!$annotation instanceof Feature) {
                continue;
            }

            yield $annotation->getFeature() => $annotation->toArray();
        }
    }
}
