<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\Attribute;

use Novaway\Bundle\FeatureFlagBundle\Attribute\Feature;
use PHPUnit\Framework\TestCase;

final class FeatureTest extends TestCase
{
    public function testToArrayResult(): void
    {
        $feature = new Feature('foo', false);

        $this->assertSame([
            'feature' => 'foo',
            'enabled' => false,
        ], $feature->toArray());
    }

    public function testFeatureIsEnabledByDefault(): void
    {
        $feature = new Feature('bar');

        $this->assertSame([
            'feature' => 'bar',
            'enabled' => true,
        ], $feature->toArray());
    }
}
