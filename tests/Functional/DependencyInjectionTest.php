<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functional;

use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DependencyInjectionTest extends WebTestCase
{
    public function testFeatureManagerServiceExists(): void
    {
        static::assertInstanceOf(FeatureManager::class, static::getContainer()->get(FeatureManager::class));
    }
}
