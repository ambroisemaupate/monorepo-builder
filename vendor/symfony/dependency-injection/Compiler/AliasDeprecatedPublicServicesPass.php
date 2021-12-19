<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20211219\Symfony\Component\DependencyInjection\Compiler;

use MonorepoBuilder20211219\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20211219\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use MonorepoBuilder20211219\Symfony\Component\DependencyInjection\Reference;
final class AliasDeprecatedPublicServicesPass extends \MonorepoBuilder20211219\Symfony\Component\DependencyInjection\Compiler\AbstractRecursivePass
{
    /**
     * @var mixed[]
     */
    private $aliases = [];
    /**
     * {@inheritdoc}
     * @param mixed $value
     * @return mixed
     */
    protected function processValue($value, bool $isRoot = \false)
    {
        if ($value instanceof \MonorepoBuilder20211219\Symfony\Component\DependencyInjection\Reference && isset($this->aliases[$id = (string) $value])) {
            return new \MonorepoBuilder20211219\Symfony\Component\DependencyInjection\Reference($this->aliases[$id], $value->getInvalidBehavior());
        }
        return parent::processValue($value, $isRoot);
    }
    /**
     * {@inheritdoc}
     */
    public function process(\MonorepoBuilder20211219\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('container.private') as $id => $tags) {
            if (null === ($package = $tags[0]['package'] ?? null)) {
                throw new \MonorepoBuilder20211219\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException(\sprintf('The "package" attribute is mandatory for the "container.private" tag on the "%s" service.', $id));
            }
            if (null === ($version = $tags[0]['version'] ?? null)) {
                throw new \MonorepoBuilder20211219\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException(\sprintf('The "version" attribute is mandatory for the "container.private" tag on the "%s" service.', $id));
            }
            $definition = $container->getDefinition($id);
            if (!$definition->isPublic() || $definition->isPrivate()) {
                continue;
            }
            $container->setAlias($id, $aliasId = '.container.private.' . $id)->setPublic(\true)->setDeprecated($package, $version, 'Accessing the "%alias_id%" service directly from the container is deprecated, use dependency injection instead.');
            $container->setDefinition($aliasId, $definition);
            $this->aliases[$id] = $aliasId;
        }
        parent::process($container);
    }
}
