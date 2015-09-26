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

namespace FlameCore\Seabreeze\Console\Command;

use FlameCore\Seabreeze\Manifest\Project;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The "info" Console Command
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class InfoCommand extends AbstractProjectAwareCommand
{
    protected function configure()
    {
        $this->setName('info')
             ->setDescription('Displays project information');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = $this->getProject();

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
