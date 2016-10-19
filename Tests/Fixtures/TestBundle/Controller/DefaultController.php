<?php

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\TestBundle\Controller;

use Novaway\Bundle\FeatureFlagBundle\Annotation\Feature;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function featuresAction()
    {
        $featureManager = $this->get('novaway_feature_flag.manager.feature');

        return $this->render('default/features.html.twig', [
            'feature_foo_enabled'  => $featureManager->isEnabled('foo'),
            'feature_bar_enabled'  => $featureManager->isEnabled('bar'),
            'feature_foo_disabled' => $featureManager->isDisabled('foo'),
            'feature_bar_disabled' => $featureManager->isDisabled('bar'),
        ]);
    }

    public function requestFooEnabledAction()
    {
        return new Response('DefaultController::requestFooEnabledAction');
    }

    public function requestFooDisabledAction()
    {
        return new Response('DefaultController::requestFooDisabledAction');
    }

    /**
     * @Feature("foo")
     */
    public function annotationFooEnabledAction()
    {
        return new Response('DefaultController::annotationFooEnabledAction');
    }

    /**
     * @Feature("foo", enabled=false)
     */
    public function annotationFooDisabledAction()
    {
        return new Response('DefaultController::annotationFooDisabledAction');
    }
}
