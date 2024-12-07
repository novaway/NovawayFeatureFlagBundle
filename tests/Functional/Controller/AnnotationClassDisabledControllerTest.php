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
use Symfony\Component\HttpKernel\Kernel;

final class AnnotationClassDisabledControllerTest extends WebTestCase
{
    public function testAnnotationFooDisabledAction(): void
    {
        if (Kernel::MAJOR_VERSION >= 7) {
            static::markTestSkipped('This test is not compatible with Symfony > 7');
        }

        static::$client->request('GET', '/annotation/class/disabled');
        $response = static::$client->getResponse();

        static::assertFalse($response->isSuccessful());
        static::assertSame(404, $response->getStatusCode());
    }
}
