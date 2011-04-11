<?php

namespace Liip\ContainerWrapperBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class RemoveContainerWrapperPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->getParameter('liip_container_wrapper.disable_optimization')) {
            return;
        }

        $class = $container->getDefinition('liip_container_wrapper.service')->getClass();

        foreach ($container->getDefinitions() as $id => $definition) {
            if ($definition->isAbstract() || $definition->getClass() != $class) {
                continue;
            }

            $arguments = $definition->getArguments();
            if (!$this->containsMappings($container, $arguments[0])
                && !$this->containsMappings($container, $arguments[1])
                && !$this->containsMappings($container, $arguments[2])
                && !$this->containsMappings($container, $arguments[3])
            ) {
                $container->setAlias('service_container', $id);
            }
        }
    }

    private function containsMappings(ContainerBuilder $container, $list)
    {
        if (!is_array($list)) {
            $list = $container->getParameterBag()->resolveValue($list);
        }

        foreach ($list as $item) {
            if ($item !== true) {
                return true;
            }
        }

        return false;
    }
}
