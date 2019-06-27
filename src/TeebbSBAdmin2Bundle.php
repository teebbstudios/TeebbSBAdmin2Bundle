<?php

namespace Teebb\SBAdmin2Bundle;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Teebb\SBAdmin2Bundle\DependencyInjection\Compiler\AdminServicesCompilePass;
use Teebb\SBAdmin2Bundle\DependencyInjection\Compiler\GlobalVariablesCompilerPass;

class TeebbSBAdmin2Bundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new GlobalVariablesCompilerPass());
        $container->addCompilerPass(new AdminServicesCompilePass());

        parent::build($container);
    }

}