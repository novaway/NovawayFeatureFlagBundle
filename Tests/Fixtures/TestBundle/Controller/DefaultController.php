<?php

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}
