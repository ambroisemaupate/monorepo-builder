<?php

declare (strict_types=1);
namespace MonorepoBuilder20210831\Symplify\ConsolePackageBuilder\Bundle;

use MonorepoBuilder20210831\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20210831\Symfony\Component\HttpKernel\Bundle\Bundle;
use MonorepoBuilder20210831\Symplify\ConsolePackageBuilder\DependencyInjection\CompilerPass\NamelessConsoleCommandCompilerPass;
final class NamelessConsoleCommandBundle extends \MonorepoBuilder20210831\Symfony\Component\HttpKernel\Bundle\Bundle
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder
     */
    public function build($containerBuilder) : void
    {
        $containerBuilder->addCompilerPass(new \MonorepoBuilder20210831\Symplify\ConsolePackageBuilder\DependencyInjection\CompilerPass\NamelessConsoleCommandCompilerPass());
    }
}
