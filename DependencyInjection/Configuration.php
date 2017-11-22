<?php

namespace AOS\Security\Crud\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('crud_checker');

        $rootNode
        ->children()
            ->arrayNode('entity')
                ->prototype('array')
                    ->children()
                        ->arrayNode('roles')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('permissions')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end()
        ;

        return $treeBuilder;
    }
}
