<?php

namespace Liip\ContainerWrapperBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * This class contains the configuration information for the bundle
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @author Lukas Kahwe Smith <smith@pooteeweet.org>
 */
class Configuration
{
    /**
     * Generates the configuration tree.
     *
     * @return \Symfony\Component\DependencyInjection\Configuration\NodeInterface
     */
    public function getConfigTree()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('liip_contrainer_wrapper', 'array');

        $rootNode
            ->fixXmlConfig('service', 'services')
            ->children()
                ->arrayNode('services')
                    ->useAttributeAsKey('services')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
            ->fixXmlConfig('parameter', 'parameters')
            ->children()
                ->arrayNode('parameters')
                    ->useAttributeAsKey('parameters')
                    ->prototype('scalar')->end()
                ->end()
                ->booleanNode('remove_unmapped')->defaultTrue()->end()
            ->end()
        ->end();

        return $treeBuilder->buildTree();
    }

}
