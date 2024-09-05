<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\App\TestBundle\Controller;

use Novaway\Bundle\FeatureFlagBundle\Attribute\FeatureDisabled;
use Novaway\Bundle\FeatureFlagBundle\Attribute\FeatureEnabled;
use Novaway\Bundle\FeatureFlagBundle\Manager\ChainedFeatureManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function __construct(
        private readonly ChainedFeatureManager $manager,
    ) {
    }

    public function features(): Response
    {
        return $this->render('default/features.html.twig', [
            'feature_foo_enabled' => $this->manager->isEnabled('foo'),
            'feature_bar_enabled' => $this->manager->isEnabled('bar'),
            'feature_foo_disabled' => $this->manager->isDisabled('foo'),
            'feature_bar_disabled' => $this->manager->isDisabled('bar'),
        ]);
    }

    public function requestFooEnabled(): Response
    {
        return new Response('DefaultController::requestFooEnabledAction');
    }

    public function requestFooDisabled(): Response
    {
        return new Response('DefaultController::requestFooDisabledAction');
    }

    #[FeatureEnabled(name: 'foo')]
    public function attributeRouteIsAccessibleIfFeatureIsEnabled(): Response
    {
        return new Response('DefaultController::attributeRouteIsAccessibleIfFeatureIsEnabled');
    }

    #[FeatureDisabled(name: 'foo')]
    public function attributeRouteIsAccessibleIfFeatureIsDisabled(): Response
    {
        return new Response('DefaultController::attributeRouteIsAccessibleIfFeatureIsDisabled');
    }
}
