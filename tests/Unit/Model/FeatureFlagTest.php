<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\Model;

use Novaway\Bundle\FeatureFlagBundle\Model\FeatureFlag;
use PHPUnit\Framework\TestCase;

final class FeatureFlagTest extends TestCase
{
    public function testToArrayResult(): void
    {
        $feature = new FeatureFlag('foo', true, 'bar', [
            'foo' => 'bar',
            'parray' => [
                'key1' => 'value1',
                'key2' => 'value2',
            ],
        ]);

        static::assertSame([
            'key' => 'foo',
            'enabled' => true,
            'description' => 'bar',
            'options' => [
                'foo' => 'bar',
                'parray' => [
                    'key1' => 'value1',
                    'key2' => 'value2',
                ],
            ],
        ], $feature->toArray());
    }
}
