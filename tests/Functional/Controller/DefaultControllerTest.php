<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functional\Controller;

use Novaway\Bundle\FeatureFlagBundle\Tests\Functional\WebTestCase;

final class DefaultControllerTest extends WebTestCase
{
    public function testFeatureEnabled(): void
    {
        $crawler = self::$client->request('GET', '/features');
        $this->assertTrue(self::$client->getResponse()->isSuccessful());

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Foo feature is enabled from controller")')->count(),
        );

        $this->assertSame(
            0,
            $crawler->filter('html:contains("Bar feature is enabled from controller")')->count(),
        );
    }

    public function testFeatureDisabled(): void
    {
        $crawler = self::$client->request('GET', '/features');
        $this->assertTrue(self::$client->getResponse()->isSuccessful());

        $this->assertSame(
            0,
            $crawler->filter('html:contains("Foo feature is disabled from controller")')->count(),
        );

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Bar feature is disabled from controller")')->count(),
        );
    }

    public function testRequestFooEnabled()
    {
        $crawler = self::$client->request('GET', '/request/enabled');

        $this->assertSame(200, self::$client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("DefaultController::requestFooEnabledAction")')->count(),
        );
    }

    public function testRequestFooDisabled()
    {
        self::$client->request('GET', '/request/disabled');

        $this->assertSame(404, self::$client->getResponse()->getStatusCode());
    }

    public function testAttributeFooErrorAction()
    {
        $crawler = self::$client->request('GET', '/attribute/error');

        $this->assertSame(500, self::$client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("DefaultController::attributeFooEnabledAction")')->count(),
        );
    }

    public function testAttributeFooEnabledAction()
    {
        $crawler = self::$client->request('GET', '/attribute/enabled');

        $this->assertSame(200, self::$client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("DefaultController::attributeFooEnabledAction")')->count(),
        );
    }

    public function testAttributeFooDisabledAction()
    {
        self::$client->request('GET', '/attribute/disabled');

        $this->assertSame(404, self::$client->getResponse()->getStatusCode());
    }
}
