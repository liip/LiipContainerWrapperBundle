<?php

namespace Liip\ContainerWrapperBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle,
    Symfony\Component\DependencyInjection\ContainerInterface,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Compiler\PassConfig,
    Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Liip\ContainerWrapperBundle\DependencyInjection\Compiler\RemoveContainerWrapperPass;

class LiipContainerWrapperBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RemoveContainerWrapperPass(), PassConfig::TYPE_OPTIMIZE);
    }
}
