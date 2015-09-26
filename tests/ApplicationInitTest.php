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

namespace FlameCore\Seabreeze\Tests;

use FlameCore\Seabreeze\Console\Application;
use FlameCore\Seabreeze\Console\Command\InitializeCommand;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Test class for 'deploy' command of Application
 */
class ApplicationInitTest extends ApplicationTestCase
{
    public function testExecute()
    {
        chdir($this->workspace);

        $application = new Application($this->workspace);
        $application->add(new InitializeCommand());

        $command = $application->find('init');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), '--name' => 'foo']);

        $this->assertRegExp('/New project "foo" initialized./', $commandTester->getDisplay());

        $this->assertManifestCreated();
    }

    protected function assertManifestCreated()
    {
        $dir = $this->workspace.DIRECTORY_SEPARATOR.'.seabreeze';

        $this->assertFileExists($dir);
        $this->assertFileExists($dir.DIRECTORY_SEPARATOR.'config.yml');
    }
}
