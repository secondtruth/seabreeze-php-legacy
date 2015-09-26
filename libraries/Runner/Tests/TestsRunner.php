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

namespace FlameCore\Seabreeze\Runner\Tests;

use FlameCore\Seabreeze\Manifest\Environment;
use FlameCore\Seabreeze\Runner\CommandsRunner;

/**
 * The TestsRunner class
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class TestsRunner extends CommandsRunner
{
    /**
     * {@inheritdoc}
     */
    protected function getCommands(Environment $environment)
    {
        return $environment->getTests();
    }

    /**
     * {@inheritdoc}
     */
    protected function onRunnerStart($commands)
    {
        if ($this->observer) {
            $this->observer->notify('tests.start', ['total' => count($commands)]);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function onRunnerFinish()
    {
        if ($this->observer) {
            $this->observer->notify('tests.finish', ['results' => $this->results]);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function onCommandStart($name)
    {
        if ($this->observer) {
            $this->observer->notify('test.start', ['test' => $name]);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function onCommandFinish(array $result)
    {
        if ($this->observer) {
            $this->observer->notify('test.finish', $result);
        }
    }
}
