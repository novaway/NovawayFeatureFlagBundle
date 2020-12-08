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
    const ROOT_NODE = 'novaway_feature_flag';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(static::ROOT_NODE);

        if (method_exists($treeBuilder, 'getRootNode')) {
            // Symfony 4.2 +
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // Symfony 4.1 and below
            $rootNode = $treeBuilder->root(static::ROOT_NODE);
        }
        
        $rootNode
            ->children()
                ->scalarNode('storage')->defaultValue('novaway_feature_flag.storage.default')->end()
                ->arrayNode('features')
                    ->useAttributeAsKey('name')
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
