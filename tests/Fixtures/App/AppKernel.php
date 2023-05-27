<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\App;

use Novaway\Bundle\FeatureFlagBundle\NovawayFeatureFlagBundle;
use Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
        $loader->load(function (ContainerBuilder $container) {
            $container->register('logger', \Psr\Log\NullLogger::class);
            $container->setParameter('env(FEATURE_ENVVAR)', 'false');

            $container->loadFromExtension('framework', [
                'assets' => [],
                'secret' => '$ecret',
                'test' => true,
                'router' => ['resource' => '%kernel.project_dir%/tests/Fixtures/App/config/routing.yml'],
            ]);

            $container->loadFromExtension('twig', [
                'paths' => [
                    __DIR__.'/Resources/views/' => '',
                ],
            ]);

            $container->loadFromExtension('novaway_feature_flag', [
                'default_manager' => 'default',
                'managers' => [
                    'default' => [
                        'storage' => ArrayStorage::class,
                        'options' => [
                            'features' => [
                                'override' => true,
                                'foo' => true,
                                'bar' => [
                                    'enabled' => false,
                                    'description' => 'Bar feature description',
                                ],
                                'env_var' => '%env(bool:FEATURE_ENVVAR)%',
                            ],
                        ],
                    ],
                ],
            ]);
        });
    }

    public function getCacheDir(): string
    {
        return sprintf('%s/cache/%s', $this->getBasePath(), $this->environment);
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
