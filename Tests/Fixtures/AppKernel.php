<?php

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures;

use Novaway\Bundle\FeatureFlagBundle\NovawayFeatureFlagBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new NovawayFeatureFlagBundle(),
            new TestBundle\TestBundle(),
            new \atoum\AtoumBundle\AtoumAtoumBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function ($container) {
            $container->loadFromExtension('framework', [
                'router'     => ['resource' => '%kernel.root_dir%/config/routing.yml'],
                'secret'     => '$ecret',
                'test'       => true,
                'templating' => [
                    'engines' => ['twig'],
                ],
            ]);

            $container->loadFromExtension('novaway_feature_flag', [
                'features' => [
                    'foo' => true,
                    'bar' => false,
                ],
            ]);

            $container->loadFromExtension('atoum', [
                'bundles' => [
                    'TestBundle' => [
                        'directories' => ['Tests/Functional'],
                    ],
                ],
            ]);
        });
    }

    public function getCacheDir()
    {
        return sprintf('%s/logs/cache/%s', $this->getBasePath(), $this->environment);
    }

    public function getLogDir()
    {
        return sprintf('%s/logs', $this->getBasePath());
    }

    public function getBasePath()
    {
        return sprintf('%s/%s/AppKernel', sys_get_temp_dir(), Kernel::VERSION);
    }
}
