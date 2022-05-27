<?php

declare(strict_types=1);

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functional;

use Novaway\Bundle\FeatureFlagBundle\Tests\FixturePath;
use Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\App\AppKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

abstract class WebTestCase extends BaseWebTestCase
{
    protected static function getKernelClass()
    {
        require_once FixturePath::APP_TEST_KERNEL_FILE;

        return AppKernel::class;
    }
}