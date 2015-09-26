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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The "init" Console Command
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class InitializeCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('init')
             ->setDescription('Initializes new project')
             ->addOption('name', null, InputOption::VALUE_REQUIRED, 'The name of the project');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $directory = $this->getApplication()->getWorkingDir();

        if (Project::exists($directory)) {
            throw new \DomainException(sprintf('Directory does already contain a manifest.', $directory));
        }

        $name = $input->getOption('name');

        $project = Project::create($directory, $name);
        $project->flush();

        $output->writeln(sprintf('New %s initialized.', $name ? 'project "'.$name.'"' : 'project'));
    }
}
