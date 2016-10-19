<?php

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functionals\Fixtures\TestBundle\Controller;

use Novaway\Bundle\FeatureFlagBundle\Tests\Functionals\WebTestCase;

class DefaultController extends WebTestCase
{
    public function testFeatureEnabled()
    {
        $this
            ->if($client = $this->createClient())
            ->then
                ->given($crawler = $client->request('GET', '/features'))
                ->boolean($client->getResponse()->isSuccessful())
                    ->isTrue()
                ->integer($crawler->filter('html:contains("Foo feature is enabled from controller")')->count())
                    ->isGreaterThan(0)
                ->integer($crawler->filter('html:contains("Bar feature is enabled from controller")')->count())
                    ->isEqualTo(0)
        ;
    }

    public function testFeatureDisabled()
    {
        $this
            ->if($client = $this->createClient())
            ->then
                ->given($crawler = $client->request('GET', '/features'))
                ->boolean($client->getResponse()->isSuccessful())
                    ->isTrue()
                ->integer($crawler->filter('html:contains("Foo feature is disabled from controller")')->count())
                    ->isEqualTo(0)
                ->integer($crawler->filter('html:contains("Bar feature is disabled from controller")')->count())
                    ->isGreaterThan(0)
        ;
    }

    public function testRequestFooEnabled()
    {
        $this
            ->if($client = $this->createClient())
            ->then
                ->given($crawler = $client->request('GET', '/request/enabled'))
                ->boolean($client->getResponse()->isSuccessful())
                    ->isTrue()
                ->integer($crawler->filter('html:contains("DefaultController::requestFooEnabledAction")')->count())
                    ->isGreaterThan(0)
                ->integer($client->getResponse()->getStatusCode())
                    ->isEqualTo(200)
        ;
    }

    public function testRequestFooDisabled()
    {
        $this
            ->if($client = $this->createClient())
            ->then
                ->given($crawler = $client->request('GET', '/request/disabled'))
                ->boolean($client->getResponse()->isSuccessful())
                    ->isFalse()
                ->integer($client->getResponse()->getStatusCode())
                    ->isEqualTo(404)
        ;
    }
                ->boolean($client->getResponse()->isSuccessful())
                    ->isTrue()
}
