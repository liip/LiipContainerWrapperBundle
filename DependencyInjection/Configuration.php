<?php

namespace Liip\ContainerWrapperBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @author Lukas Kahwe Smith <smith@pooteeweet.org>
 */
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
                ->booleanNode('disable_optimization')->defaultValue('%kernel.debug%')->end()
            ->end()
        ->end();

        return $treeBuilder;
    }

}
