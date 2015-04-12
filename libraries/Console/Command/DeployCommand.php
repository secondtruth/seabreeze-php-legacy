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

use FlameCore\Seabreeze\Deployer\Deployer;
use FlameCore\Seabreeze\EventObserver\DeploymentObserver;
use FlameCore\Seabreeze\EventObserver\Provider\ConsoleProvider;
use FlameCore\Synchronizer\Files\FilesSynchronizerFactory;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The "deploy" Console Command
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class DeployCommand extends AbstractProjectAwareCommand
{
    protected function configure()
    {
        $this->setName('deploy')
             ->setDescription('Deploys to given environment')
             ->addArgument('environment', InputArgument::REQUIRED, 'The environment to deploy to')
             ->addOption('test', 't', InputOption::VALUE_NONE, 'Run tests before deploying');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = $this->getProject();

        $name = $input->getArgument('environment');

        if (!$project->hasEnvironment($name)) {
            throw new \DomainException(sprintf('Deployment environment "%s" does not exist', $name));
        }

        if ($input->getOption('test') && ($command = $this->getApplication()->find('test'))) {
            $arguments = array(
                'command' => 'test',
                'environment' => $name
            );

            $input2 = new ArrayInput($arguments);
            $status = $command->run($input2, $output);

            if ($status != 0) {
                $output->writeln('Deployment process aborted due to failed tests.');

                return $status;
            }
        }

        $environment = $project->getEnvironment($name);

        $output->writeln('Starting deployment process.');

        $deployer = $this->initDeployer($input, $output);
        $success = $deployer->deploy($environment);

        $output->writeln(sprintf('Deployment process finished %s.', !$success ? 'with errors' : 'successfully'));

        return $success ? 0 : 1;
    }

    protected function initDeployer(InputInterface $input, OutputInterface $output)
    {
        $observer = $this->initObserver($input, $output);

        $deployer = new Deployer();
        $deployer->observe($observer);

        $factory = new FilesSynchronizerFactory();
        $this->setupFilesSources($factory);
        $this->setupFilesTargets($factory);

        $deployer->register('files', $factory);

        return $deployer;
    }

    protected function initObserver(InputInterface $input, OutputInterface $output)
    {
        $format = '- %task%... %percent:3s%%';

        if ($output->isVerbose()) {
            $format .= ' (%elapsed:7s% / %estimated:7s% estimated)';
        }

        $options = [
            'process' => [
                'start.message' => '%action% <info>%engine%</info>...'
            ],
            'progress' => [
                'format' => $format
            ]
        ];

        $provider = new ConsoleProvider($output, $options);
        return new DeploymentObserver($provider);
    }

    protected function setupFilesSources(FilesSynchronizerFactory $factory)
    {
        $factory->registerSource('local', 'FlameCore\Synchronizer\Files\Source\LocalFilesSource');
    }

    protected function setupFilesTargets(FilesSynchronizerFactory $factory)
    {
        $factory->registerTarget('local', 'FlameCore\Synchronizer\Files\Target\LocalFilesTarget');
    }
}
