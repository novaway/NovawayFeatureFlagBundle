<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functional\Command;

use Novaway\Bundle\FeatureFlagBundle\Tests\Functional\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;

final class ListFeatureCommandTest extends WebTestCase
{
    public function testCommandIsAvailable(): void
    {
        $application = new Application(ListFeatureCommandTest::bootKernel());
        $command = $application->find('novaway:feature-flag:list');

        ListFeatureCommandTest::assertInstanceOf(Command::class, $command);
    }
}
