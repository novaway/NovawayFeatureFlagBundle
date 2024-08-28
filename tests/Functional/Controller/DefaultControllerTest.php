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

    /**
     * @requires PHP >= 8.0
     */
    public function testTwoAnnotationsFooEnabledBarEnabledHasNoAccessBecauseFeatureFooIsEnabledButFeatureBarIsDisabled()
    {
        static::$client->request('GET', '/annotation/bar/enabled/foo/enabled');

        static::assertSame(404, static::$client->getResponse()->getStatusCode());
    }

    /**
     * @requires PHP >= 8.0
     */
    public function testTwoAnnotationsFooEnabledBarDisabledHasAccessBecauseFeatureFooIsEnabledAndFeatureBarIsDisabled()
    {
        $crawler = static::$client->request('GET', '/annotation/bar/enabled/foo/disabled');

        static::assertSame(200, static::$client->getResponse()->getStatusCode());
        static::assertGreaterThan(
            0,
            $crawler->filter('html:contains("DefaultController::annotationFooEnabledBarDisabledAction")')->count(),
        );
    }

    /**
     * @requires PHP >= 8.0
     */
    public function testAttributeFooEnabledAction()
    {
        $crawler = static::$client->request('GET', '/attribute/enabled');

        static::assertSame(200, static::$client->getResponse()->getStatusCode());
        static::assertGreaterThan(
            0,
            $crawler->filter('html:contains("DefaultController::attributeFooEnabledAction")')->count(),
        );
    }

    /**
     * @requires PHP >= 8.0
     */
    public function testAttributeFooDisabledAction()
    {
        static::$client->request('GET', '/attribute/disabled');

        static::assertSame(404, static::$client->getResponse()->getStatusCode());
    }

    /**
     * @requires PHP >= 8.0
     */
    public function testTwoAttributesFooEnabledBarEnabledHasNoAccessBecauseFeatureFooIsEnabledButFeatureBarIsDisabled()
    {
        static::$client->request('GET', '/attribute/bar/enabled/foo/enabled');

        static::assertSame(404, static::$client->getResponse()->getStatusCode());
    }

    /**
     * @requires PHP >= 8.0
     */
    public function testTwoAttributesFooEnabledBarDisabledHasAccessBecauseFeatureFooIsEnabledAndFeatureBarIsDisabled()
    {
        $crawler = static::$client->request('GET', '/attribute/bar/enabled/foo/disabled');

        static::assertSame(200, static::$client->getResponse()->getStatusCode());
        static::assertGreaterThan(
            0,
            $crawler->filter('html:contains("DefaultController::attributeFooEnabledBarDisabledAction")')->count(),
        );
    }
}
