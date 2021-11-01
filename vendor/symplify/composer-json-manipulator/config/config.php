<?php

declare (strict_types=1);
namespace MonorepoBuilder20211101;

use MonorepoBuilder20211101\Symfony\Component\Console\Style\SymfonyStyle;
use MonorepoBuilder20211101\Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20211101\Symplify\ComposerJsonManipulator\ValueObject\Option;
use MonorepoBuilder20211101\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory;
use MonorepoBuilder20211101\Symplify\PackageBuilder\Parameter\ParameterProvider;
use MonorepoBuilder20211101\Symplify\PackageBuilder\Reflection\PrivatesCaller;
use MonorepoBuilder20211101\Symplify\SmartFileSystem\SmartFileSystem;
use function MonorepoBuilder20211101\Symfony\Component\DependencyInjection\Loader\Configurator\service;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(\MonorepoBuilder20211101\Symplify\ComposerJsonManipulator\ValueObject\Option::INLINE_SECTIONS, ['keywords']);
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('MonorepoBuilder20211101\Symplify\ComposerJsonManipulator\\', __DIR__ . '/../src');
    $services->set(\MonorepoBuilder20211101\Symplify\SmartFileSystem\SmartFileSystem::class);
    $services->set(\MonorepoBuilder20211101\Symplify\PackageBuilder\Reflection\PrivatesCaller::class);
    $services->set(\MonorepoBuilder20211101\Symplify\PackageBuilder\Parameter\ParameterProvider::class)->args([\MonorepoBuilder20211101\Symfony\Component\DependencyInjection\Loader\Configurator\service(\MonorepoBuilder20211101\Symfony\Component\DependencyInjection\ContainerInterface::class)]);
    $services->set(\MonorepoBuilder20211101\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory::class);
    $services->set(\MonorepoBuilder20211101\Symfony\Component\Console\Style\SymfonyStyle::class)->factory([\MonorepoBuilder20211101\Symfony\Component\DependencyInjection\Loader\Configurator\service(\MonorepoBuilder20211101\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory::class), 'create']);
};
