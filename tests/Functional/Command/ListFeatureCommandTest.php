<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functional\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;

final class ListFeatureCommandTest extends KernelTestCase
{
    public function testCommandIsAvailable(): void
    {
        $application = new Application(static::bootKernel());
        $command = $application->find('novaway:feature-flag:list');

        static::assertInstanceOf(Command::class, $command);
    }
}
