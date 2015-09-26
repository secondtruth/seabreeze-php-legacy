<?php
/**
 * Seabreeze
 * Copyright (C) 2015 IceFlame.net
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 *
 * @package  FlameCore\Seabreeze
 * @version  0.1-dev
 * @link     http://www.flamecore.org
 * @license  ISC License <http://opensource.org/licenses/ISC>
 */

namespace FlameCore\Seabreeze\EventObserver\Provider;

use FlameCore\Seabreeze\EventObserver\Responder\Console\ConsoleMessageResponder;
use FlameCore\Seabreeze\EventObserver\Responder\Console\ConsoleProcessResponder;
use FlameCore\Seabreeze\EventObserver\Responder\Console\ConsoleProgressResponder;
use FlameCore\EventObserver\Provider\Provider;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The ConsoleProvider class
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class ConsoleProvider extends Provider
{
    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $options
     */
    public function __construct(OutputInterface $output, array $options = [])
    {
        $responder = new ConsoleMessageResponder($output, isset($options['message']) ? $options['message'] : []);
        $this->setResponder('message', $responder);

        $responder = new ConsoleProcessResponder($output, isset($options['process']) ? $options['process'] : []);
        $this->setResponder('process', $responder);

        $responder = new ConsoleProgressResponder($output, isset($options['progress']) ? $options['progress'] : []);
        $this->setResponder('progress', $responder);
    }
}
