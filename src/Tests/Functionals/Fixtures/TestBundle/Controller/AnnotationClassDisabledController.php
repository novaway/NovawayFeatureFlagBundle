<?php

declare(strict_types=1);

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functionals\Fixtures\TestBundle\Controller;

use Novaway\Bundle\FeatureFlagBundle\Tests\Functionals\WebTestCase;

class AnnotationClassDisabledController extends WebTestCase
{
    public function testAnnotationFooDisabledAction()
    {
        $this
            ->if($client = $this->createClient())
            ->then
                ->given($client->request('GET', '/annotation/class/disabled'))
                ->dump($client->getResponse())
                ->boolean($client->getResponse()->isSuccessful())
                    ->isFalse()
                ->integer($client->getResponse()->getStatusCode())
                    ->isEqualTo(404)
        ;
    }
}