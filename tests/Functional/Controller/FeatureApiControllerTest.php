<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FeatureApiControllerTest extends WebTestCase
{
    public function testApiGetAllFeatures(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/features');

        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertTrue($client->getResponse()->isSuccessful());
        static::assertJsonStringEqualsJsonString(<<<JSON
{
    "default": {
        "bar": {
            "description": "Bar feature description",
            "enabled": false,
            "key": "bar",
            "options": []
        },
        "env_var": {
            "description": "",
            "enabled": false,
            "key": "env_var",
            "options": []
        },
        "foo": {
            "description": "",
            "enabled": true,
            "key": "foo",
            "options": []
        },
        "override": {
            "description": "",
            "enabled": true,
            "key": "override",
            "options": []
        }
    }
}
JSON, $client->getResponse()->getContent());
    }
}
