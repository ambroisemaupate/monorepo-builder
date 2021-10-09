<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20211009\Symfony\Component\ErrorHandler\ErrorRenderer;

use MonorepoBuilder20211009\Symfony\Component\ErrorHandler\Exception\FlattenException;
use MonorepoBuilder20211009\Symfony\Component\HttpFoundation\Request;
use MonorepoBuilder20211009\Symfony\Component\HttpFoundation\RequestStack;
use MonorepoBuilder20211009\Symfony\Component\Serializer\Exception\NotEncodableValueException;
use MonorepoBuilder20211009\Symfony\Component\Serializer\SerializerInterface;
/**
 * Formats an exception using Serializer for rendering.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class SerializerErrorRenderer implements \MonorepoBuilder20211009\Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface
{
    private $serializer;
    private $format;
    private $fallbackErrorRenderer;
    private $debug;
    /**
     * @param string|callable(FlattenException) $format The format as a string or a callable that should return it
     *                                                  formats not supported by Request::getMimeTypes() should be given as mime types
     * @param bool|callable                     $debug  The debugging mode as a boolean or a callable that should return it
     */
    public function __construct(\MonorepoBuilder20211009\Symfony\Component\Serializer\SerializerInterface $serializer, $format, \MonorepoBuilder20211009\Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface $fallbackErrorRenderer = null, $debug = \false)
    {
        if (!\is_string($format) && !\is_callable($format)) {
            throw new \TypeError(\sprintf('Argument 2 passed to "%s()" must be a string or a callable, "%s" given.', __METHOD__, \gettype($format)));
        }
        if (!\is_bool($debug) && !\is_callable($debug)) {
            throw new \TypeError(\sprintf('Argument 4 passed to "%s()" must be a boolean or a callable, "%s" given.', __METHOD__, \gettype($debug)));
        }
        $this->serializer = $serializer;
        $this->format = $format;
        $this->fallbackErrorRenderer = $fallbackErrorRenderer ?? new \MonorepoBuilder20211009\Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer();
        $this->debug = $debug;
    }
    /**
     * {@inheritdoc}
     * @param \Throwable $exception
     */
    public function render($exception) : \MonorepoBuilder20211009\Symfony\Component\ErrorHandler\Exception\FlattenException
    {
        $headers = [];
        $debug = \is_bool($this->debug) ? $this->debug : ($this->debug)($exception);
        if ($debug) {
            $headers['X-Debug-Exception'] = \rawurlencode($exception->getMessage());
            $headers['X-Debug-Exception-File'] = \rawurlencode($exception->getFile()) . ':' . $exception->getLine();
        }
        $flattenException = \MonorepoBuilder20211009\Symfony\Component\ErrorHandler\Exception\FlattenException::createFromThrowable($exception, null, $headers);
        try {
            $format = \is_string($this->format) ? $this->format : ($this->format)($flattenException);
            $headers = ['Content-Type' => \MonorepoBuilder20211009\Symfony\Component\HttpFoundation\Request::getMimeTypes($format)[0] ?? $format, 'Vary' => 'Accept'];
            return $flattenException->setAsString($this->serializer->serialize($flattenException, $format, ['exception' => $exception, 'debug' => $debug]))->setHeaders($flattenException->getHeaders() + $headers);
        } catch (\MonorepoBuilder20211009\Symfony\Component\Serializer\Exception\NotEncodableValueException $e) {
            return $this->fallbackErrorRenderer->render($exception);
        }
    }
    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    public static function getPreferredFormat($requestStack) : \Closure
    {
        return static function () use($requestStack) {
            if (!($request = $requestStack->getCurrentRequest())) {
                throw new \MonorepoBuilder20211009\Symfony\Component\Serializer\Exception\NotEncodableValueException();
            }
            return $request->getPreferredFormat();
        };
    }
}
