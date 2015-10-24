<?php

namespace Liip\ContainerWrapperBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class LiipContainerWrapperExtension extends Extension
{
    /**
     * Loads the services based on your application configuration.
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $loader = $this->getFileLoader($container);
        $loader->load('container_wrapper.xml');

        $container->setParameter($this->getAlias().'.disable_optimization', $config['disable_optimization']);

        if (!empty($config['services'])) {
            $container->setParameter($this->getAlias().'.default_service_map', $config['services']);
        }

        if (!empty($config['parameters'])) {
            $container->setParameter($this->getAlias().'.default_parameter_map', $config['parameters']);
        }
    }

    /**
     * Get File Loader.
     *
     * @param ContainerBuilder $container
     */
    public function getFileLoader($container)
    {
        return new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
    }
}
