<?php
/**
 * Seabreeze
 * Copyright (C) 2014 IceFlame.net
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE
 * FOR ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY
 * DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER
 * IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING
 * OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 *
 * @package  FlameCore\Seabreeze
 * @version  0.1-dev
 * @link     http://www.flamecore.org
 * @license  ISC License <http://opensource.org/licenses/ISC>
 */

namespace FlameCore\Seabreeze\Runner;

use FlameCore\EventObserver\ObserverInterface;
use Symfony\Component\Process\Process;

/**
 * The abstract Runner class
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
abstract class AbstractRunner implements RunnerInterface
{
    /**
     * @var \FlameCore\EventObserver\ObserverInterface
     */
    protected $observer;

    /**
     * @var int
     */
    protected $succeeded;

    /**
     * @var int
     */
    protected $failed;

    /**
     * @var bool
     */
    protected $success = true;

    /**
     * @var array
     */
    protected $results = array();

    /**
     * {@inheritdoc}
     */
    public function observe(ObserverInterface $observer)
    {
        $this->observer = $observer;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return $this->success;
    }

    /**
     * {@inheritdoc}
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * {@inheritdoc}
     */
    public function getSucceeded()
    {
        return $this->succeeded;
    }

    /**
     * {@inheritdoc}
     */
    public function getFailed()
    {
        return $this->failed;
    }

    /**
     * @param string $command
     * @return array
     */
    protected function execucte($command)
    {
        $command = (string) $command;

        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->failed++;
            $this->success = false;
        } else {
            $this->succeeded++;
        }

        return array(
            'success' => $process->isSuccessful(),
            'output' => $process->getOutput()
        );
    }
}
