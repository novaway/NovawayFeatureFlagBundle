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

final class FeatureApiControllerTest extends WebTestCase
{
    public function testApiGetAllFeatures(): void
    {
        self::$client->request('GET', '/api/features');

        static::assertTrue(static::$client->getResponse()->isSuccessful());
        static::assertJsonStringEqualsJsonString(<<<JSON
{
    "bar": {
        "description": "Bar feature description",
        "enabled": false,
        "key": "bar",
        "options": {
            "foo": "bar",
            "tableau": {
                "test": "zaza"
            }
        }
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
        "enabled": false,
        "key": "override",
        "options": []
    }
}
JSON, static::$client->getResponse()->getContent());
    }

    public function testApiGetOneFeature(): void
    {
        self::$client->request('GET', '/api/features/foo');

        static::assertTrue(static::$client->getResponse()->isSuccessful());
        static::assertJsonStringEqualsJsonString(<<<JSON
{
    "description": "",
    "enabled": true,
    "key": "foo",
    "options": []
}
JSON, static::$client->getResponse()->getContent());
    }

    public function testApiGetUnknownFeatureReturnAnError(): void
    {
        self::$client->request('GET', '/api/features/unknow-feature');

        static::assertSame(404, static::$client->getResponse()->getStatusCode());
        static::assertJsonStringEqualsJsonString(<<<JSON
{
    "type": "undefined-feature",
    "title": "Feature 'unknow-feature' not exists."
}
JSON, static::$client->getResponse()->getContent());
    }
}
