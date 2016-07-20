<?php

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Units\DependencyInjection;

use atoum;
use Novaway\Bundle\FeatureFlagBundle\DependencyInjection\NovawayFeatureFlagExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Configuration extends atoum
{
    public function testConfigTreeBuilder()
    {
        $this
            ->given($configuration = $this->getConfiguration('config.yml'))
            ->array($configuration)->hasKey('features')
            ->array($configuration['features'])
                ->boolean['foo']->isTrue()
                ->boolean['bar']->isFalse()
        ;
    }

    private function getConfiguration($file)
    {
        $extension = new NovawayFeatureFlagExtension();

        $container = new ContainerBuilder();
        $container->registerExtension($extension);

        $loader = new YamlFileLoader($container, new FileLocator());
        $loader->load(sprintf('%s/Data/%s', __DIR__, $file));

        $configuration = $container->getExtensionConfig($extension->getAlias());

        return reset($configuration);
    }
}
