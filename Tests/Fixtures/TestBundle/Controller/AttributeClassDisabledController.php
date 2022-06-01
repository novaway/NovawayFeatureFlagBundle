<?php

declare(strict_types=1);

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\TestBundle\Controller;

use Novaway\Bundle\FeatureFlagBundle\Annotation\Feature;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

#[Feature(name: "foo", enabled: false)]
class AttributeClassDisabledController extends AbstractController
{
    public function __invoke()
    {
        return new Response('AttributeClassDisabledController::response');
    }
}