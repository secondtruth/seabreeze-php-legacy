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

namespace FlameCore\Seabreeze\Console;

use Symfony\Component\Console\Application as BaseApplication;

/**
 * The Application class
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class Application extends BaseApplication
{
    const TITLE = 'Seabreeze Deployment';
    const VERSION = '0.1';

    protected $workingDir;

    public function __construct($workingDir)
    {
        if (function_exists('ini_set') && extension_loaded('xdebug')) {
            ini_set('xdebug.show_exception_trace', false);
            ini_set('xdebug.scream', false);
        }

        if (function_exists('date_default_timezone_set') && function_exists('date_default_timezone_get'))
            date_default_timezone_set(@date_default_timezone_get());

        $this->setWorkingDir($workingDir);

        parent::__construct(self::TITLE, self::VERSION);
    }

    public function getWorkingDir()
    {
        return $this->workingDir;
    }

    public function setWorkingDir($workingDir)
    {
        $this->workingDir = realpath($workingDir);
    }

    /**
     * {@inheritDoc}
     */
    public function renderException($exception, $output)
    {
        $message = $exception->getMessage();

        $output->writeln(sprintf('<error>ERROR: %s</error>', $message));
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();
        $commands[] = new Command\InitializeCommand();

        return $commands;
    }
}
