<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Compiler;

use MonorepoBuilder20210707\Symfony\Component\Config\Definition\BaseNode;
use MonorepoBuilder20210707\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Exception\LogicException;
use MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Exception\RuntimeException;
use MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Extension\ConfigurationExtensionInterface;
use MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Extension\Extension;
use MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use MonorepoBuilder20210707\Symfony\Component\DependencyInjection\ParameterBag\EnvPlaceholderParameterBag;
use MonorepoBuilder20210707\Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
/**
 * Merges extension configs into the container builder.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class MergeExtensionConfigurationPass implements \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(\MonorepoBuilder20210707\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        $parameters = $container->getParameterBag()->all();
        $definitions = $container->getDefinitions();
        $aliases = $container->getAliases();
        $exprLangProviders = $container->getExpressionLanguageProviders();
        $configAvailable = \class_exists(\MonorepoBuilder20210707\Symfony\Component\Config\Definition\BaseNode::class);
        foreach ($container->getExtensions() as $extension) {
            if ($extension instanceof \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface) {
                $extension->prepend($container);
            }
        }
        foreach ($container->getExtensions() as $name => $extension) {
            if (!($config = $container->getExtensionConfig($name))) {
                // this extension was not called
                continue;
            }
            $resolvingBag = $container->getParameterBag();
            if ($resolvingBag instanceof \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\ParameterBag\EnvPlaceholderParameterBag && $extension instanceof \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Extension\Extension) {
                // create a dedicated bag so that we can track env vars per-extension
                $resolvingBag = new \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationParameterBag($resolvingBag);
                if ($configAvailable) {
                    \MonorepoBuilder20210707\Symfony\Component\Config\Definition\BaseNode::setPlaceholderUniquePrefix($resolvingBag->getEnvPlaceholderUniquePrefix());
                }
            }
            $config = $resolvingBag->resolveValue($config);
            try {
                $tmpContainer = new \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationContainerBuilder($extension, $resolvingBag);
                $tmpContainer->setResourceTracking($container->isTrackingResources());
                $tmpContainer->addObjectResource($extension);
                if ($extension instanceof \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Extension\ConfigurationExtensionInterface && null !== ($configuration = $extension->getConfiguration($config, $tmpContainer))) {
                    $tmpContainer->addObjectResource($configuration);
                }
                foreach ($exprLangProviders as $provider) {
                    $tmpContainer->addExpressionLanguageProvider($provider);
                }
                $extension->load($config, $tmpContainer);
            } catch (\Exception $e) {
                if ($resolvingBag instanceof \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationParameterBag) {
                    $container->getParameterBag()->mergeEnvPlaceholders($resolvingBag);
                }
                if ($configAvailable) {
                    \MonorepoBuilder20210707\Symfony\Component\Config\Definition\BaseNode::resetPlaceholders();
                }
                throw $e;
            }
            if ($resolvingBag instanceof \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationParameterBag) {
                // don't keep track of env vars that are *overridden* when configs are merged
                $resolvingBag->freezeAfterProcessing($extension, $tmpContainer);
            }
            $container->merge($tmpContainer);
            $container->getParameterBag()->add($parameters);
        }
        if ($configAvailable) {
            \MonorepoBuilder20210707\Symfony\Component\Config\Definition\BaseNode::resetPlaceholders();
        }
        $container->addDefinitions($definitions);
        $container->addAliases($aliases);
    }
}
/**
 * @internal
 */
class MergeExtensionConfigurationParameterBag extends \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\ParameterBag\EnvPlaceholderParameterBag
{
    private $processedEnvPlaceholders;
    /**
     * @param mixed $parameterBag
     */
    public function __construct($parameterBag)
    {
        parent::__construct($parameterBag->all());
        $this->mergeEnvPlaceholders($parameterBag);
    }
    public function freezeAfterProcessing(\MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Extension\Extension $extension, \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        if (!($config = $extension->getProcessedConfigs())) {
            // Extension::processConfiguration() wasn't called, we cannot know how configs were merged
            return;
        }
        $this->processedEnvPlaceholders = [];
        // serialize config and container to catch env vars nested in object graphs
        $config = \serialize($config) . \serialize($container->getDefinitions()) . \serialize($container->getAliases()) . \serialize($container->getParameterBag()->all());
        foreach (parent::getEnvPlaceholders() as $env => $placeholders) {
            foreach ($placeholders as $placeholder) {
                if (\false !== \stripos($config, $placeholder)) {
                    $this->processedEnvPlaceholders[$env] = $placeholders;
                    break;
                }
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getEnvPlaceholders() : array
    {
        return null !== $this->processedEnvPlaceholders ? $this->processedEnvPlaceholders : parent::getEnvPlaceholders();
    }
    public function getUnusedEnvPlaceholders() : array
    {
        return null === $this->processedEnvPlaceholders ? [] : \array_diff_key(parent::getEnvPlaceholders(), $this->processedEnvPlaceholders);
    }
}
/**
 * A container builder preventing using methods that wouldn't have any effect from extensions.
 *
 * @internal
 */
class MergeExtensionConfigurationContainerBuilder extends \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\ContainerBuilder
{
    private $extensionClass;
    public function __construct(\MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Extension\ExtensionInterface $extension, \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface $parameterBag = null)
    {
        parent::__construct($parameterBag);
        $this->extensionClass = \get_class($extension);
    }
    /**
     * {@inheritdoc}
     * @return $this
     */
    public function addCompilerPass(\MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface $pass, string $type = \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Compiler\PassConfig::TYPE_BEFORE_OPTIMIZATION, int $priority = 0)
    {
        throw new \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Exception\LogicException(\sprintf('You cannot add compiler pass "%s" from extension "%s". Compiler passes must be registered before the container is compiled.', \get_debug_type($pass), $this->extensionClass));
    }
    /**
     * {@inheritdoc}
     */
    public function registerExtension(\MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Extension\ExtensionInterface $extension)
    {
        throw new \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Exception\LogicException(\sprintf('You cannot register extension "%s" from "%s". Extensions must be registered before the container is compiled.', \get_debug_type($extension), $this->extensionClass));
    }
    /**
     * {@inheritdoc}
     * @param bool $resolveEnvPlaceholders
     */
    public function compile($resolveEnvPlaceholders = \false)
    {
        throw new \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Exception\LogicException(\sprintf('Cannot compile the container in extension "%s".', $this->extensionClass));
    }
    /**
     * {@inheritdoc}
     */
    public function resolveEnvPlaceholders($value, $format = null, array &$usedEnvs = null)
    {
        if (\true !== $format || !\is_string($value)) {
            return parent::resolveEnvPlaceholders($value, $format, $usedEnvs);
        }
        $bag = $this->getParameterBag();
        $value = $bag->resolveValue($value);
        if (!$bag instanceof \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\ParameterBag\EnvPlaceholderParameterBag) {
            return parent::resolveEnvPlaceholders($value, $format, $usedEnvs);
        }
        foreach ($bag->getEnvPlaceholders() as $env => $placeholders) {
            if (\false === \strpos($env, ':')) {
                continue;
            }
            foreach ($placeholders as $placeholder) {
                if (\false !== \stripos($value, $placeholder)) {
                    throw new \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Exception\RuntimeException(\sprintf('Using a cast in "env(%s)" is incompatible with resolution at compile time in "%s". The logic in the extension should be moved to a compiler pass, or an env parameter with no cast should be used instead.', $env, $this->extensionClass));
                }
            }
        }
        return parent::resolveEnvPlaceholders($value, $format, $usedEnvs);
    }
}