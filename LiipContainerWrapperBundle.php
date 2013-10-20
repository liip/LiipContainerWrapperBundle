<?php

namespace Liip\ContainerWrapperBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Liip\ContainerWrapperBundle\DependencyInjection\Compiler\RemoveContainerWrapperPass;

class LiipContainerWrapperBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RemoveContainerWrapperPass(), PassConfig::TYPE_OPTIMIZE);
    }
}
