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

namespace FlameCore\Seabreeze\Console\Command;

use FlameCore\Seabreeze\Manifest\Project;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The "info" Console Command
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class InfoCommand extends Command
{
    protected function configure()
    {
        $this->setName('info')
             ->setDescription('Displays project information');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $directory = $this->getApplication()->getWorkingDir();
            $project = Project::fromDirectory($directory);
        } catch (\Exception $e) {
            throw new \DomainException('Missing or unreadable manifest file in working directory');
        }

        $output->writeln('<comment>Project Details:</comment>');

        $details = $this->getDetails($project);
        foreach ($details as $name => $value) {
            $output->writeln(sprintf(' %-10s %s', $name.':', $value));
        }

        $output->writeln(PHP_EOL.'<comment>Environments:</comment>');

        foreach ($project->getEnvironments() as $environment) {
            $output->writeln(sprintf(" - %s", $environment->getName()));
        }
    }

    protected function getDetails(Project $project)
    {
        return array(
            'Name' => $project->getName()
        );
    }
}
