<?php

declare(strict_types=1);

namespace Novaway\Bundle\FeatureFlagBundle\Tests;

final class FixturePath
{
    public const APP_TEST_KERNEL_FILE = __DIR__.'/Fixtures/App/AppKernel.php';
    public const CONFIG_FILE = __DIR__ . '/Fixtures/config_sample.yml';

    private function __construct()
    {
    }
}