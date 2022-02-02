<?php

declare (strict_types=1);
namespace MonorepoBuilder20220202;

use MonorepoBuilder20220202\SebastianBergmann\Diff\Differ;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20220202\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('MonorepoBuilder20220202\Symplify\ConsoleColorDiff\\', __DIR__ . '/../src');
    $services->set(\MonorepoBuilder20220202\SebastianBergmann\Diff\Differ::class);
    $services->set(\MonorepoBuilder20220202\Symplify\PackageBuilder\Reflection\PrivatesAccessor::class);
};
