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

use FlameCore\Seabreeze\Manifest\Environment;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The "add:environment" Console Command
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class AddEnvironmentCommand extends AbstractProjectAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('add:environment')
             ->setDescription('Adds environment to project')
             ->addArgument('name', InputArgument::REQUIRED, 'The name of the environment');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = $this->getProject();

        $name = $input->getArgument('name');

        if ($project->hasEnvironment($name)) {
            throw new \LogicException(sprintf('Deployment environment "%s" does already exist.', $name));
        }

        $environment = new Environment($name, $project);
        $project->addEnvironment($environment);
        
        $project->flush();

        $output->writeln(sprintf('New environment "%s" created successfully.', $name));
    }
}
