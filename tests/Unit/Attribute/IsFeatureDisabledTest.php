<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\Attribute;

use Novaway\Bundle\FeatureFlagBundle\Attribute\FeatureAttribute;
use Novaway\Bundle\FeatureFlagBundle\Attribute\IsFeatureDisabled;

final class IsFeatureDisabledTest extends FeatureAttributeTestCase
{
    public function testIsFeatureDisabledToArrayTransformation(): void
    {
        $feature = $this->createAttribute('bar');

        static::assertSame([
            'feature' => 'bar',
            'enabled' => false,
            'exceptionClass' => null,
            'exceptionFactory' => null,
        ], $feature->toArray());
    }

    protected function createAttribute(string $name, ?string $exceptionClass = null): FeatureAttribute
    {
        return new IsFeatureDisabled($name, $exceptionClass);
    }
}
