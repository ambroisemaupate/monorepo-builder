<?php

declare (strict_types=1);
namespace MonorepoBuilder20211101\Symplify\SymplifyKernel\HttpKernel;

use MonorepoBuilder20211101\Symfony\Component\DependencyInjection\Container;
use MonorepoBuilder20211101\Symfony\Component\DependencyInjection\ContainerInterface;
use MonorepoBuilder20211101\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use MonorepoBuilder20211101\Symplify\SymfonyContainerBuilder\ContainerBuilderFactory;
use MonorepoBuilder20211101\Symplify\SymplifyKernel\Contract\LightKernelInterface;
use MonorepoBuilder20211101\Symplify\SymplifyKernel\DependencyInjection\Extension\SymplifyKernelExtension;
use MonorepoBuilder20211101\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
/**
 * @api
 */
abstract class AbstractSymplifyKernel implements \MonorepoBuilder20211101\Symplify\SymplifyKernel\Contract\LightKernelInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container|null
     */
    private $container = null;
    /**
     * @param string[] $configFiles
     * @param mixed[] $extensions
     * @param mixed[] $compilerPasses
     */
    public function create($extensions, $compilerPasses, $configFiles) : \MonorepoBuilder20211101\Symfony\Component\DependencyInjection\ContainerInterface
    {
        $containerBuilderFactory = new \MonorepoBuilder20211101\Symplify\SymfonyContainerBuilder\ContainerBuilderFactory();
        $extensions[] = new \MonorepoBuilder20211101\Symplify\SymplifyKernel\DependencyInjection\Extension\SymplifyKernelExtension();
        $compilerPasses[] = new \MonorepoBuilder20211101\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass();
        $containerBuilder = $containerBuilderFactory->create($extensions, $compilerPasses, $configFiles);
        $containerBuilder->compile();
        $this->container = $containerBuilder;
        return $containerBuilder;
    }
    public function getContainer() : \MonorepoBuilder20211101\Psr\Container\ContainerInterface
    {
        if (!$this->container instanceof \MonorepoBuilder20211101\Symfony\Component\DependencyInjection\Container) {
            throw new \MonorepoBuilder20211101\Symplify\SymplifyKernel\Exception\ShouldNotHappenException();
        }
        return $this->container;
    }
}
