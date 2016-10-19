<?php

namespace Novaway\Bundle\FeatureFlagBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Util\ClassUtils;
use Novaway\Bundle\FeatureFlagBundle\Annotation\Feature;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ControllerListener implements EventSubscriberInterface
{
    /** @var Reader */
    private $annotationReader;

    /**
     * Constructor
     *
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->annotationReader = $reader;
    }

    /**
     * Update the request object to apply annotation configuration
     *
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        if (!is_array($controller) && method_exists($controller, '__invoke')) {
            $controller = [$controller, '__invoke'];
        }

        if (!is_array($controller)) {
            return;
        }

        $className = class_exists('Doctrine\Common\Util\ClassUtils') ? ClassUtils::getClass($controller[0]) : get_class($controller[0]);
        $object    = new \ReflectionClass($className);
        $method    = $object->getMethod($controller[1]);

        $features = [];
        foreach ($this->annotationReader->getMethodAnnotations($method) as $annotation) {
            if ($annotation instanceof Feature) {
                $key = $annotation->getFeature();
                if (isset($features[$key])) {
                    throw new \UnexpectedValueException(sprintf('Feature "%s" is defined more than once for %s::%s', $key, $className, $controller[1]));
                }

                $features[$key] = [
                    'feature' => $annotation->getFeature(),
                    'enabled' => $annotation->getEnabled(),
                ];
            }
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
}
