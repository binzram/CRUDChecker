<?php

namespace AOS\Security\Crud\DependencyInjection;

use AOS\Security\Crud\Permission\Entity\Operation;
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
                            ->enumPrototype('scalar')
                                ->values([
                                    Operation::CREATE,
                                    Operation::READ,
                                    Operation::UPDATE,
                                    Operation::DELETE,
                                ])
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end()
        ;

        return $treeBuilder;
    }
}
