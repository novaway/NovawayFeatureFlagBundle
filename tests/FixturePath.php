<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests;

final class FixturePath
{
    public const APP_TEST_KERNEL_FILE = __DIR__.'/Fixtures/App/AppKernel.php';
    public const CONFIG_FILE = __DIR__.'/Fixtures/config_sample.yml';

    private function __construct()
    {
    }
}
