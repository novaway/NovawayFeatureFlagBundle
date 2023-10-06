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

final class AttributeClassDisabledControllerTest extends WebTestCase
{
    public function testAttributeFooDisabledAction(): void
    {
        static::$client->request('GET', '/attribute/class/disabled');
        $response = static::$client->getResponse();

        static::assertFalse($response->isSuccessful());
        static::assertSame(404, $response->getStatusCode());
    }
}
