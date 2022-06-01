<?php

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\App\TestBundle\Controller;

use Novaway\Bundle\FeatureFlagBundle\Annotation\Feature;
use Novaway\Bundle\FeatureFlagBundle\Storage\StorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    /**
     * @var StorageInterface
     */
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function features()
    {
        return $this->render('default/features.html.twig', [
            'feature_foo_enabled'  => $this->storage->isEnabled('foo'),
            'feature_bar_enabled'  => $this->storage->isEnabled('bar'),
            'feature_foo_disabled' => $this->storage->isDisabled('foo'),
            'feature_bar_disabled' => $this->storage->isDisabled('bar'),
        ]);
    }

    public function requestFooEnabled()
    {
        return new Response('DefaultController::requestFooEnabledAction');
    }

    public function requestFooDisabled()
    {
        return new Response('DefaultController::requestFooDisabledAction');
    }

    /**
     * @Feature("foo")
     */
    public function annotationFooEnabled()
    {
        return new Response('DefaultController::annotationFooEnabledAction');
    }

    /**
     * @Feature("foo", enabled=false)
     */
    public function annotationFooDisabled()
    {
        return new Response('DefaultController::annotationFooDisabledAction');
    }

     #[Feature(name: "foo")]
    public function attributeFooEnabled()
    {
        return new Response('DefaultController::attributeFooEnabledAction');
    }

     #[Feature(name: "foo", enabled: false)]
    public function attributeFooDisabled()
    {
        return new Response('DefaultController::attributeFooDisabledAction');
    }
}
