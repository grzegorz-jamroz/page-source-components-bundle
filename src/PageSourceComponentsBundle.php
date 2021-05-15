<?php

declare(strict_types=1);

namespace Ifrost\Bundle\PageSourceComponentsBundle;

use Ifrost\Bundle\PageSourceComponentsBundle\DependencyInjection\ComponentPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PageSourceComponentsBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ComponentPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
