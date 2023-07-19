<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\DependencyInjection;

use Novaway\Bundle\FeatureFlagBundle\Manager\DefaultFeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Storage\Storage;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Twig\Environment;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NovawayFeatureFlagExtension extends Extension
{
    /**
     * @param mixed[] $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('commands.php');
        $loader->load('services.php');

        if (class_exists(Environment::class)) {
            $loader->load('twig.php');
        }

        if ($container->getParameter('kernel.debug')) {
            $loader->load('debug.php');
        }

        foreach ($config['managers'] as $name => $managerConfiguration) {
            $container
                ->register('novaway_feature_flag.storage.'.$name, Storage::class)
                ->setFactory([new Reference($managerConfiguration['factory']), 'createStorage'])
                ->addArgument($name)
                ->addArgument($managerConfiguration['options'])
                ->setPublic(false)
            ;

            $container
                ->register(sprintf('novaway_feature_flag.manager.%s', $name), DefaultFeatureManager::class)
                ->addArgument($name)
                ->addArgument(new Reference('novaway_feature_flag.storage.'.$name))
                ->addTag('novaway_feature_flag.manager')
            ;
        }
    }
}
