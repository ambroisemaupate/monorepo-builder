<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20220403\Symfony\Component\Console\Event;

use MonorepoBuilder20220403\Symfony\Component\Console\Command\Command;
use MonorepoBuilder20220403\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilder20220403\Symfony\Component\Console\Output\OutputInterface;
/**
 * @author marie <marie@users.noreply.github.com>
 */
final class ConsoleSignalEvent extends \MonorepoBuilder20220403\Symfony\Component\Console\Event\ConsoleEvent
{
    /**
     * @var int
     */
    private $handlingSignal;
    public function __construct(\MonorepoBuilder20220403\Symfony\Component\Console\Command\Command $command, \MonorepoBuilder20220403\Symfony\Component\Console\Input\InputInterface $input, \MonorepoBuilder20220403\Symfony\Component\Console\Output\OutputInterface $output, int $handlingSignal)
    {
        parent::__construct($command, $input, $output);
        $this->handlingSignal = $handlingSignal;
    }
    public function getHandlingSignal() : int
    {
        return $this->handlingSignal;
    }
}
