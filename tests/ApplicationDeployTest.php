<?php
/**
 * Seabreeze
 * Copyright (C) 2015 IceFlame.net
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

namespace FlameCore\Seabreeze\Tests;

use FlameCore\Seabreeze\Console\Application;
use FlameCore\Seabreeze\Console\Command\DeployCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Test class for 'deploy' command of Application
 */
class ApplicationDeployTest extends ApplicationTestCase
{
    /**
     * @var string
     */
    protected $sourcePath;

    /**
     * @var string
     */
    protected $targetPath;

    public function setUp()
    {
        parent::setUp();

        $this->fillWorkspace();
    }

    public function testExecute()
    {
        chdir($this->workspace);

        $application = new Application($this->workspace);
        $application->add(new DeployCommand());

        $command = $application->find('deploy');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'environment' => 'test']);

        $this->assertRegExp('/Deployment process finished successfully/', $commandTester->getDisplay());

        $this->assertNewFileCreated();
        $this->assertFileModified();
    }

    protected function fillWorkspace()
    {
        // Create source
        $sourcePath = $this->workspace.DIRECTORY_SEPARATOR.'source';
        mkdir($sourcePath);

        file_put_contents($sourcePath.DIRECTORY_SEPARATOR.'new.txt', 'CONTENT');
        file_put_contents($sourcePath.DIRECTORY_SEPARATOR.'modified.txt', 'MODIFIED CONTENT');

        $this->sourcePath = $sourcePath;

        // Create target
        $targetPath = $this->workspace.DIRECTORY_SEPARATOR.'target';
        mkdir($targetPath);

        file_put_contents($targetPath.DIRECTORY_SEPARATOR.'modified.txt', 'OLD CONTENT');
        file_put_contents($targetPath.DIRECTORY_SEPARATOR.'obsolete.txt', 'CONTENT');

        $this->targetPath = $targetPath;
    }

    protected function assertNewFileCreated()
    {
        $file = $this->targetPath.DIRECTORY_SEPARATOR.'new.txt';

        $this->assertFileExists($file);
        $this->assertEquals('CONTENT', file_get_contents($file));
    }

    protected function assertFileModified()
    {
        $file = $this->targetPath.DIRECTORY_SEPARATOR.'modified.txt';

        $this->assertFileExists($file);
        $this->assertEquals('MODIFIED CONTENT', file_get_contents($file));
    }

    protected function assertObsoleteFileDeleted()
    {
        $file = $this->targetPath.DIRECTORY_SEPARATOR.'obsolete.txt';

        $this->assertFileNotExists($file);
    }
}
