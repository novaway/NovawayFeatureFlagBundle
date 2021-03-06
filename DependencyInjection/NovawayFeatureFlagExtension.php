<?php

namespace Novaway\Bundle\FeatureFlagBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NovawayFeatureFlagExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('novaway_feature_flag.features', $config['features']);

        $container->setAlias('novaway_feature_flag.manager.feature', $config['storage']);
        $container->getAlias('novaway_feature_flag.manager.feature')->setPublic(true);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (class_exists('Twig_Extension')) {
            $loader->load('twig.yml');
        }

        if ($container->getParameter('kernel.debug')) {
            $loader->load('debug.yml');
        }
    }
}
