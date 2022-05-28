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
        $className = get_class($controller[0]);
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
        yield from $this->featuresFromAttributes($class, $method);

        yield from $this->featuresFromAnnotations($class, $method);
    }

    /**
     * @return array<string, array{feature: string, enabled: bool}>
     */
    private function featuresFromAttributes(\ReflectionClass $class, \ReflectionMethod $method): iterable
    {
        if (\PHP_VERSION_ID < 80000) {
            return [];
        }

        foreach ($class->getAttributes(Feature::class) as $attribute) {
            /** @var Feature $feature */
            $feature = $attribute->newInstance();

            yield $feature->getFeature() => $feature->toArray();
        }

        foreach ($method->getAttributes(Feature::class) as $attribute) {
            /** @var Feature $feature */
            $feature = $attribute->newInstance();

            yield $feature->getFeature() => $feature->toArray();
        }
    }

    /**
     * @return array<string, array{feature: string, enabled: bool}>
     */
    private function featuresFromAnnotations(\ReflectionClass $class, \ReflectionMethod $method): iterable
    {
        foreach ($this->annotationReader->getClassAnnotations($class) as $annotation) {
            if (!$annotation instanceof Feature) {
                continue;
            }

            yield $annotation->getFeature() => $annotation->toArray();
        }

        foreach ($this->annotationReader->getMethodAnnotations($method) as $annotation) {
            if (!$annotation instanceof Feature) {
                continue;
            }

            yield $annotation->getFeature() => $annotation->toArray();
        }
    }
}
