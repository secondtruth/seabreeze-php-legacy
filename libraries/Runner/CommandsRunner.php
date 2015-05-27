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
use Symfony\Component\Process\Process;

/**
 * The abstract CommandsRunner class
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
abstract class CommandsRunner extends AbstractRunner
{
    /**
     * {@inheritdoc}
     */
    protected function doRun(Environment $environment)
    {
        $commands = $this->getCommands($environment);

        $this->onRunnerStart($commands);

        foreach ($commands as $name => $command) {
            $this->onCommandStart($name);

            $result = $this->execucte($command);

            $this->onCommandFinish($result);

            $this->results[$name] = $result;
        }

        $this->onRunnerFinish();
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

    /**
     * @param \FlameCore\Seabreeze\Manifest\Environment $environment
     * @return array
     */
    abstract protected function getCommands(Environment $environment);

    /**
     * The Runner Start hook.
     *
     * @param array $commands
     */
    abstract protected function onRunnerStart($commands);

    /**
     * The Runner Finish hook.
     */
    abstract protected function onRunnerFinish();

    /**
     * The Command Start hook.
     *
     * @param string $name
     */
    abstract protected function onCommandStart($name);

    /**
     * The Command Finish hook.
     *
     * @param array $result
     */
    abstract protected function onCommandFinish(array $result);
}
