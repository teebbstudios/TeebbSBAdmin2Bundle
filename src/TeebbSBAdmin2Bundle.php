<?php

namespace Teebb\SBAdmin2Bundle;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Teebb\SBAdmin2Bundle\DependencyInjection\CompilerPass\GlobalVariablesCompilerPass;

class TeebbSBAdmin2Bundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new GlobalVariablesCompilerPass());
    }

}