<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\Attribute;

use Novaway\Bundle\FeatureFlagBundle\Attribute\IsFeatureEnabled;
use PHPUnit\Framework\TestCase;

final class IsFeatureEnabledTest extends TestCase
{
    public function testIsFeatureEnabledToArrayTransformation()
    {
        $feature = new IsFeatureEnabled('bar');

        static::assertSame([
            'feature' => 'bar',
            'enabled' => true,
        ], $feature->toArray());
    }
}
