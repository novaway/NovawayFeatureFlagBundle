<?php

declare(strict_types=1);

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functional\Controller;

use Novaway\Bundle\FeatureFlagBundle\Tests\Functional\WebTestCase;

final class AttributeClassDisabledControllerTest extends WebTestCase
{
    private static $client;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::$client = static::createClient();
    }

    /**
     * @requires PHP >= 8.0
     */
    public function testAttributeFooDisabledAction(): void
    {
        static::$client->request('GET', '/attribute/class/disabled');
        $response = static::$client->getResponse();

        static::assertFalse($response->isSuccessful());
        static::assertSame(404, $response->getStatusCode());
    }
}