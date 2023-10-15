<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functional\Controller;

use Novaway\Bundle\FeatureFlagBundle\Tests\Functional\WebTestCase;

final class CustomExceptionControllerTest extends WebTestCase
{
    public function testFoo(): void
    {
        static::$client->request('GET', '/configuration/custom_exception/disabled');

        static::assertSame(410, static::$client->getResponse()->getStatusCode());
    }

    public function testIsFeatureDisableAttributeWithCustomException(): void
    {
        static::$client->request('GET', '/attribute/custom_exception/disabled');

        static::assertSame(400, static::$client->getResponse()->getStatusCode());
    }

    public function testIsFeatureEnableAttributeWithCustomException(): void
    {
        static::$client->request('GET', '/attribute/custom_exception/enabled');

        static::assertSame(409, static::$client->getResponse()->getStatusCode());
    }

    public function testIsFeatureDisableAttributeWithCustomExceptionFactory(): void
    {
        static::$client->request('GET', '/attribute/custom_exception_factory/disabled');

        static::assertSame(423, static::$client->getResponse()->getStatusCode());
    }

    public function testIsFeatureEnableAttributeWithCustomExceptionFactory(): void
    {
        static::$client->request('GET', '/attribute/custom_exception_factory/enabled');

        static::assertSame(411, static::$client->getResponse()->getStatusCode());
    }
}
