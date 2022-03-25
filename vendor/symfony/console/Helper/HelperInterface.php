<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20220325\Symfony\Component\Console\Helper;

/**
 * HelperInterface is the interface all helpers must implement.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
interface HelperInterface
{
    /**
     * Sets the helper set associated with this helper.
     */
    public function setHelperSet(\MonorepoBuilder20220325\Symfony\Component\Console\Helper\HelperSet $helperSet = null);
    /**
     * Gets the helper set associated with this helper.
     */
    public function getHelperSet() : ?\MonorepoBuilder20220325\Symfony\Component\Console\Helper\HelperSet;
    /**
     * Returns the canonical name of this helper.
     *
     * @return string
     */
    public function getName();
}
