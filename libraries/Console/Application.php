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

    /**
     * @var string
     */
    protected $workingDir;

    /**
     * @param string $workingDir
     */
    public function __construct($workingDir)
    {
        if (function_exists('ini_set') && extension_loaded('xdebug')) {
            ini_set('xdebug.show_exception_trace', false);
            ini_set('xdebug.scream', false);
        }

        if (function_exists('date_default_timezone_set') && function_exists('date_default_timezone_get')) {
            date_default_timezone_set(@date_default_timezone_get());
        }

        $this->setWorkingDir($workingDir);

        parent::__construct(self::TITLE, self::VERSION);
    }

    /**
     * @return string
     */
    public function getWorkingDir()
    {
        return $this->workingDir;
    }

    /**
     * @param string $workingDir
     */
    public function setWorkingDir($workingDir)
    {
        $this->workingDir = realpath($workingDir);
    }

    /**
     * {@inheritdoc}
     */
    public function renderException($exception, $output)
    {
        $message = $exception->getMessage();

        $output->writeln(sprintf('<error>ERROR: %s</error>', $message));
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();
        $commands[] = new Command\InitializeCommand();
        $commands[] = new Command\InfoCommand();
        $commands[] = new Command\AddEnvironmentCommand();
        $commands[] = new Command\DeployCommand();
        $commands[] = new Command\TestCommand();

        return $commands;
    }
}
