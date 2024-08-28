<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\Attribute;

use Novaway\Bundle\FeatureFlagBundle\Attribute\FeatureAttribute;
use PHPUnit\Framework\TestCase;

abstract class FeatureAttributeTestCase extends TestCase
{
    public function testAnExceptionThrowsIfGivenExceptionClassNotExists(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->createAttribute('foo', NotExistException::class);
    }

    public function testAnExceptionThrowsIfGivenExceptionClassIsNotAThrowableInstance(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->createAttribute('foo', \stdClass::class);
    }

    abstract protected function createAttribute(string $name, ?string $exceptionClass = null): FeatureAttribute;
}
