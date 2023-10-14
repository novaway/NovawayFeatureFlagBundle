<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Attribute;

use Attribute;

#[Attribute]
final class IsFeatureEnabled extends FeatureAttribute
{
    protected function shouldBeEnabled(): bool
    {
        return true;
    }
}
