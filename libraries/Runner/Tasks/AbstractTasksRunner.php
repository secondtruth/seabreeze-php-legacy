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

namespace FlameCore\Seabreeze\Runner\Tasks;

use FlameCore\Seabreeze\Manifest\Environment;
use FlameCore\Seabreeze\Runner\CommandsRunner;

/**
 * The AbstractTasksRunner class
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
abstract class AbstractTasksRunner extends CommandsRunner
{
    /**
     * {@inheritdoc}
     */
    protected function getCommands(Environment $environment)
    {
        $type = $this->getTasksType();
        return $environment->getTasks($type);
    }

    /**
     * {@inheritdoc}
     */
    protected function onRunnerStart($commands)
    {
        if ($this->observer) {
            $type = $this->getTasksType();
            $this->observer->notify('tasks.start', ['total' => count($commands), 'type' => $type]);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function onRunnerFinish()
    {
        if ($this->observer) {
            $this->observer->notify('tasks.finish', ['results' => $this->results]);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function onCommandStart($name)
    {
        if ($this->observer) {
            $this->observer->notify('task.start', ['task' => $name]);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function onCommandFinish(array $result)
    {
        if ($this->observer) {
            $this->observer->notify('task.finish', $result);
        }
    }

    /**
     * @return string
     */
    abstract protected function getTasksType();
}
