<?php

namespace Novaway\Bundle\FeatureFlagBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('novaway_feature_flag');

        $rootNode
            ->children()
                ->scalarNode('storage')->defaultValue('novaway_feature_flag.storage.default')->end()
                ->arrayNode('features')
                    ->prototype('array')
                        ->beforeNormalization()
                            ->ifTrue(function($v) { return !is_array($v); })
                            ->then(function($v) { return ['enabled' => (bool) $v]; })
                        ->end()
                        ->children()
                            ->scalarNode('description')->end()
                            ->booleanNode('enabled')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
