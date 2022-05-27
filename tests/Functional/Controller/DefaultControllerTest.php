<?php

declare(strict_types=1);

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functional\Controller;

use Novaway\Bundle\FeatureFlagBundle\Tests\Functional\WebTestCase;

final class DefaultControllerTest extends WebTestCase
{
    private static $client;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::$client = static::createClient();
    }

    public function testFeatureEnabled(): void
    {
        $crawler = static::$client->request('GET', '/features');
        static::assertTrue(static::$client->getResponse()->isSuccessful());

        static::assertGreaterThan(
            0,
            $crawler->filter('html:contains("Foo feature is enabled from controller")')->count(),
        );

        static::assertSame(
            0,
            $crawler->filter('html:contains("Bar feature is enabled from controller")')->count(),
        );
    }

    public function testFeatureDisabled(): void
    {
        $crawler = static::$client->request('GET', '/features');
        static::assertTrue(static::$client->getResponse()->isSuccessful());

        static::assertSame(
            0,
            $crawler->filter('html:contains("Foo feature is disabled from controller")')->count(),
        );

        static::assertGreaterThan(
            0,
            $crawler->filter('html:contains("Bar feature is disabled from controller")')->count(),
        );
    }

    public function testRequestFooEnabled()
    {
        $crawler = static::$client->request('GET', '/request/enabled');

        static::assertSame(200, static::$client->getResponse()->getStatusCode());
        static::assertGreaterThan(
            0,
            $crawler->filter('html:contains("DefaultController::requestFooEnabledAction")')->count(),
        );
    }

    public function testRequestFooDisabled()
    {
        static::$client->request('GET', '/request/disabled');

        static::assertSame(404, static::$client->getResponse()->getStatusCode());
    }

    public function testAnnotationFooEnabledAction()
    {
        $crawler = static::$client->request('GET', '/annotation/enabled');

        static::assertSame(200, static::$client->getResponse()->getStatusCode());
        static::assertGreaterThan(
            0,
            $crawler->filter('html:contains("DefaultController::annotationFooEnabledAction")')->count(),
        );
    }

    public function testAnnotationFooDisabledAction()
    {
        static::$client->request('GET', '/annotation/disabled');

        static::assertSame(404, static::$client->getResponse()->getStatusCode());
    }

    public function testAttributeFooEnabledAction()
    {
        $this->skipIfNoAttributeSupport();

        $crawler = static::$client->request('GET', '/attribute/enabled');

        static::assertSame(200, static::$client->getResponse()->getStatusCode());
        static::assertGreaterThan(
            0,
            $crawler->filter('html:contains("DefaultController::attributeFooEnabledAction")')->count(),
        );
    }

    public function testAttributeFooDisabledAction()
    {
        $this->skipIfNoAttributeSupport();

        static::$client->request('GET', '/attribute/disabled');

        static::assertSame(404, static::$client->getResponse()->getStatusCode());
    }
}