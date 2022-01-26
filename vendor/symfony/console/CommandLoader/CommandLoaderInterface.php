<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20220126\Symfony\Component\Console\CommandLoader;

use MonorepoBuilder20220126\Symfony\Component\Console\Command\Command;
use MonorepoBuilder20220126\Symfony\Component\Console\Exception\CommandNotFoundException;
/**
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
interface CommandLoaderInterface
{
    /**
     * Loads a command.
     *
     * @throws CommandNotFoundException
     */
    public function get(string $name) : \MonorepoBuilder20220126\Symfony\Component\Console\Command\Command;
    /**
     * Checks if a command exists.
     */
    public function has(string $name) : bool;
    /**
     * @return string[]
     */
    public function getNames() : array;
}
