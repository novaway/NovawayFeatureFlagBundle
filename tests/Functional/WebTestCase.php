<?php

declare(strict_types=1);

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functional;

use Novaway\Bundle\FeatureFlagBundle\Tests\FixturePath;
use Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\App\AppKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class WebTestCase extends BaseWebTestCase
{
    protected static $client;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::initializeClient();
    }

    protected static function getKernelClass(): string
    {
        require_once FixturePath::APP_TEST_KERNEL_FILE;

        return AppKernel::class;
    }

    protected static function initializeClient(): void
    {
        if (!static::$client || !self::$kernel) {
            static::$client = static::createClient();
        }
    }

    protected static function getContainer(): ContainerInterface
    {
        if (method_exists(BaseWebTestCase::class, 'getContainer')) {
            return parent::getContainer();
        }

        static::initializeClient();

        return static::$kernel->getContainer();
    }
}