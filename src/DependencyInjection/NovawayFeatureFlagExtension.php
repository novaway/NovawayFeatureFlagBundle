<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
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

        $container->setParameter('novaway_feature_flag.features', $config['features']);
        $container->setAlias('novaway_feature_flag.storage', $config['storage']);
        $container->setAlias('novaway_feature_flag.manager.feature', $config['storage']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (class_exists(Environment::class)) {
            $loader->load('twig.yml');
        }

        if ($container->getParameter('kernel.debug')) {
            $loader->load('debug.yml');
        }
    }
}
