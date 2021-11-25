<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20211125\Symfony\Component\String;

if (!\function_exists(\MonorepoBuilder20211125\Symfony\Component\String\u::class)) {
    function u(?string $string = '') : \MonorepoBuilder20211125\Symfony\Component\String\UnicodeString
    {
        return new \MonorepoBuilder20211125\Symfony\Component\String\UnicodeString($string ?? '');
    }
}
if (!\function_exists(\MonorepoBuilder20211125\Symfony\Component\String\b::class)) {
    function b(?string $string = '') : \MonorepoBuilder20211125\Symfony\Component\String\ByteString
    {
        return new \MonorepoBuilder20211125\Symfony\Component\String\ByteString($string ?? '');
    }
}
if (!\function_exists(\MonorepoBuilder20211125\Symfony\Component\String\s::class)) {
    /**
     * @return UnicodeString|ByteString
     */
    function s(?string $string = '') : \MonorepoBuilder20211125\Symfony\Component\String\AbstractString
    {
        $string = $string ?? '';
        return \preg_match('//u', $string) ? new \MonorepoBuilder20211125\Symfony\Component\String\UnicodeString($string) : new \MonorepoBuilder20211125\Symfony\Component\String\ByteString($string);
    }
}
