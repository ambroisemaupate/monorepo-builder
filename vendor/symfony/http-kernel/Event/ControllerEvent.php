<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20210831\Symfony\Component\HttpKernel\Event;

use MonorepoBuilder20210831\Symfony\Component\HttpFoundation\Request;
use MonorepoBuilder20210831\Symfony\Component\HttpKernel\HttpKernelInterface;
/**
 * Allows filtering of a controller callable.
 *
 * You can call getController() to retrieve the current controller. With
 * setController() you can set a new controller that is used in the processing
 * of the request.
 *
 * Controllers should be callables.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
final class ControllerEvent extends \MonorepoBuilder20210831\Symfony\Component\HttpKernel\Event\KernelEvent
{
    private $controller;
    public function __construct(\MonorepoBuilder20210831\Symfony\Component\HttpKernel\HttpKernelInterface $kernel, callable $controller, \MonorepoBuilder20210831\Symfony\Component\HttpFoundation\Request $request, ?int $requestType)
    {
        parent::__construct($kernel, $request, $requestType);
        $this->setController($controller);
    }
    public function getController() : callable
    {
        return $this->controller;
    }
    /**
     * @param callable $controller
     */
    public function setController($controller) : void
    {
        $this->controller = $controller;
    }
}
