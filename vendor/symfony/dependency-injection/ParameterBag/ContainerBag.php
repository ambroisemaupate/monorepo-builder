<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20211202\Symfony\Component\DependencyInjection\ParameterBag;

use MonorepoBuilder20211202\Symfony\Component\DependencyInjection\Container;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class ContainerBag extends \MonorepoBuilder20211202\Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag implements \MonorepoBuilder20211202\Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    private $container;
    public function __construct(\MonorepoBuilder20211202\Symfony\Component\DependencyInjection\Container $container)
    {
        $this->container = $container;
    }
    /**
     * {@inheritdoc}
     */
    public function all() : array
    {
        return $this->container->getParameterBag()->all();
    }
    /**
     * {@inheritdoc}
     *
     * @return array|bool|string|int|float|null
     * @param string $name
     */
    public function get($name)
    {
        return $this->container->getParameter($name);
    }
    /**
     * {@inheritdoc}
     * @param string $name
     */
    public function has($name) : bool
    {
        return $this->container->hasParameter($name);
    }
}
