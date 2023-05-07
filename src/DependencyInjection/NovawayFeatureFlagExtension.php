<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\DependencyInjection;

use Novaway\Bundle\FeatureFlagBundle\Manager\DefaultFeatureManager;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
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

        $container->setParameter('novaway_feature_flag.default_manager', $config['default_manager']);

        foreach ($config['managers'] as $name => $managerConfiguration) {
            $storageDefinition = new Definition($managerConfiguration['storage']);
            $storageDefinition->setFactory([null, 'create'])->setArguments([$managerConfiguration['options']]);
            $storageDefinition->addTag('novaway_feature_flag.storage');
            $container->setDefinition("novaway_feature_flag.manager.$name.storage", $storageDefinition);

            $managerDefinition = new Definition(DefaultFeatureManager::class, [new Reference("novaway_feature_flag.manager.$name.storage")]);
            $managerDefinition->addTag('novaway_feature_flag.manager');
            $container->setDefinition("novaway_feature_flag.manager.$name", $managerDefinition);
        }

        $loader = new Loader\PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.php');

        if (class_exists(Environment::class)) {
            $loader->load('twig.php');
        }

        if ($container->getParameter('kernel.debug')) {
            $loader->load('debug.php');
        }
    }
}
