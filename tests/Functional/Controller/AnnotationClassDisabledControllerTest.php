<?php

declare(strict_types=1);

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functional\Controller;

use Novaway\Bundle\FeatureFlagBundle\Tests\Functional\WebTestCase;

final class AnnotationClassDisabledControllerTest extends WebTestCase
{
    public function testAnnotationFooDisabledAction(): void
    {
        static::$client->request('GET', '/annotation/class/disabled');
        $response = static::$client->getResponse();

        static::assertFalse($response->isSuccessful());
        static::assertSame(404, $response->getStatusCode());
    }
}