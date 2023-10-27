<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CustomExceptionControllerTest extends WebTestCase
{
    public function testFoo(): void
    {
        $client = static::createClient();
        $client->request('GET', '/configuration/custom_exception/disabled');

        static::assertSame(410, $client->getResponse()->getStatusCode());
    }

    public function testIsFeatureDisableAttributeWithCustomException(): void
    {
        $client = static::createClient();
        $client->request('GET', '/attribute/custom_exception/disabled');

        static::assertSame(400, $client->getResponse()->getStatusCode());
    }

    public function testIsFeatureEnableAttributeWithCustomException(): void
    {
        $client = static::createClient();
        $client->request('GET', '/attribute/custom_exception/enabled');

        static::assertSame(409, $client->getResponse()->getStatusCode());
    }

    public function testIsFeatureDisableAttributeWithCustomExceptionFactory(): void
    {
        $client = static::createClient();
        $client->request('GET', '/attribute/custom_exception_factory/disabled');

        static::assertSame(423, $client->getResponse()->getStatusCode());
    }

    public function testIsFeatureEnableAttributeWithCustomExceptionFactory(): void
    {
        $client = static::createClient();
        $client->request('GET', '/attribute/custom_exception_factory/enabled');

        static::assertSame(411, $client->getResponse()->getStatusCode());
    }
}
