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

use FlameCore\Seabreeze\Manifest\Environment;

/**
 * The TasksRunner class
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class LocalTasksRunner extends CommandsRunner
{
    /**
     * {@inheritdoc}
     */
    protected function getCommands(Environment $environment)
    {
        return $environment->getTasks('before');
    }

    /**
     * {@inheritdoc}
     */
    protected function onRunnerStart($commands)
    {
        if ($this->observer) {
            $this->observer->notify('tasks.start', ['total' => count($commands), 'type' => 'local']);
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
}
