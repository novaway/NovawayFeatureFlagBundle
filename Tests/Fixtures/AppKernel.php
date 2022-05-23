<?php

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures;

use Novaway\Bundle\FeatureFlagBundle\NovawayFeatureFlagBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new NovawayFeatureFlagBundle(),
            new TestBundle\TestBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(function ($container) {
            $container->register('logger', \Psr\Log\NullLogger::class);

            $container->loadFromExtension('framework', [
                'assets'     => [],
                'secret'     => '$ecret',
                'test'       => true,
                'router' => ['resource' => '%kernel.project_dir%/Tests/Fixtures/config/routing.yml']
            ]);

            $container->loadFromExtension('twig', [
                'paths' => [
                    __DIR__ . '/Resources/views/' => ''
                ]
            ]);

            $container->loadFromExtension('novaway_feature_flag', [
                'features' => [
                    'override' => true,
                    'foo' => true,
                    'bar' => [
                        'enabled' => false,
                        'description' => 'Bar feature description',
                    ],
                ],
            ]);

            // override some previous features
            $container->loadFromExtension('novaway_feature_flag', [
                'features' => [
                    'override' => false,
                ],
            ]);
        });
    }

    public function getCacheDir(): string
    {
        return sprintf('%s/logs/cache/%s', $this->getBasePath(), $this->environment);
    }

    public function getLogDir(): string
    {
        return sprintf('%s/logs', $this->getBasePath());
    }

    public function getBasePath(): string
    {
        return sprintf('%s/%s/AppKernel', sys_get_temp_dir(), Kernel::VERSION);
    }
}
