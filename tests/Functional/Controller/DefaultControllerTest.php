<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DefaultControllerTest extends WebTestCase
{
    public function testFeatureEnabled(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/features');
        static::assertTrue($client->getResponse()->isSuccessful());

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
        $client = static::createClient();
        $crawler = $client->request('GET', '/features');
        static::assertTrue($client->getResponse()->isSuccessful());

        static::assertSame(
            0,
            $crawler->filter('html:contains("Foo feature is disabled from controller")')->count(),
        );

        static::assertGreaterThan(
            0,
            $crawler->filter('html:contains("Bar feature is disabled from controller")')->count(),
        );
    }

    public function testRequestFooEnabled(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/request/enabled');

        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertGreaterThan(
            0,
            $crawler->filter('html:contains("DefaultController::requestFooEnabledAction")')->count(),
        );
    }

    public function testRequestFooDisabled(): void
    {
        $client = static::createClient();
        $client->request('GET', '/request/disabled');

        static::assertSame(404, $client->getResponse()->getStatusCode());
    }

    public function testAttributeFooEnabledAction(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/attribute/enabled');

        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertGreaterThan(
            0,
            $crawler->filter('html:contains("DefaultController::attributeRouteIsAccessibleIfFeatureIsEnabled")')->count(),
        );
    }

    public function testAttributeFooDisabledAction(): void
    {
        $client = static::createClient();
        $client->request('GET', '/attribute/disabled');

        static::assertSame(404, $client->getResponse()->getStatusCode());
    }
}
