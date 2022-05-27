<?php

declare(strict_types=1);

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functional;

use Novaway\Bundle\FeatureFlagBundle\Tests\FixturePath;
use Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\App\AppKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\Filesystem\Filesystem;

abstract class WebTestCase extends BaseWebTestCase
{
    protected static function getKernelClass()
    {
        require_once FixturePath::APP_TEST_KERNEL_FILE;

        return AppKernel::class;
    }

    protected function skipIfNoAttributeSupport(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped('Attributes are not available for PHP < 8.0');
        }
    }
}