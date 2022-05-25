<?php

declare(strict_types=1);

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functionals\Fixtures\TestBundle\Controller;

use Novaway\Bundle\FeatureFlagBundle\Tests\Functionals\WebTestCase;

class AttributeClassDisabledController extends WebTestCase
{
    /**
     * @php 8.0
     */
    public function testAttributeFooDisabledAction()
    {
        $this
            ->if($client = $this->createClient())
            ->then
                ->given($client->request('GET', '/attribute/class/disabled'))
            ->dump($client->getResponse())
                ->boolean($client->getResponse()->isSuccessful())
                    ->isFalse()
                ->integer($client->getResponse()->getStatusCode())
                    ->isEqualTo(404)
        ;
    }
}