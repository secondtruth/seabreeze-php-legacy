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

namespace FlameCore\Seabreeze\Manifest;

/**
 * The Environment manifest
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class Environment implements ManifestInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \FlameCore\Seabreeze\Manifest\SynchronizerJob[]
     */
    protected $syncJobs = array();

    /**
     * @var array
     */
    protected $tasks = array();

    /**
     * @var array
     */
    protected $tests = array();

    /**
     * @var \FlameCore\Seabreeze\Manifest\Project
     */
    protected $project;

    /**
     * @param string $name
     * @param \FlameCore\Seabreeze\Manifest\Project $project
     */
    public function __construct($name, Project $project)
    {
        $this->name = (string) $name;
        $this->project = $project;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return "environments/$this->name";
    }

    /**
     * {@inheritdoc}
     */
    public function import(array $configuration)
    {
        if (isset($configuration['synchronizers'])) {
            $synchronizers = $configuration['synchronizers'];
            foreach ($synchronizers as $mode => $settings) {
                $mode = strtolower($mode);
                $this->syncJobs[$mode] = new SynchronizerJob($mode, (array) $settings, $this);
            }
        }

        if (isset($configuration['tasks'])) {
            $tasks = $configuration['tasks'];
            foreach ($tasks as $type => $execute) {
                $this->tasks[strtolower($type)] = (array) $execute;
            }
        }

        if (isset($configuration['tests'])) {
            $this->tests = (array) $configuration['tests'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function export()
    {
        $data = array();

        foreach ($this->syncJobs as $type => $settings) {
            $data['synchronizers'][$type] = $settings->export();
        }

        foreach ($this->tasks as $type => $executes) {
            $data['tasks'][$type] = $executes;
        }

        $data['tests'] = $this->tests;

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $project = $this->getProject();
        $project->writeManifest($this);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string) $name;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasSyncJob($name)
    {
        return isset($this->syncJobs[$name]);
    }

    /**
     * @return \FlameCore\Seabreeze\Manifest\SynchronizerJob[]
     */
    public function getSyncJobs()
    {
        return $this->syncJobs;
    }

    /**
     * @param string $name
     * @return \FlameCore\Seabreeze\Manifest\SynchronizerJob
     */
    public function getSyncJob($name)
    {
        return isset($this->syncJobs[$name]) ? $this->syncJobs[$name] : null;
    }

    /**
     * @param \FlameCore\Seabreeze\Manifest\SynchronizerJob $job
     */
    public function addSyncJob(SynchronizerJob $job)
    {
        $this->syncJobs[] = $job;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function hasTasks($type)
    {
        return isset($this->tasks[$type]) ? !empty($this->tasks[$type]) : false;
    }

    /**
     * @param string $type
     * @param string $name
     * @return bool
     */
    public function hasTask($type, $name)
    {
        return isset($this->tasks[$type][$name]);
    }

    /**
     * @param string $type
     * @return array
     */
    public function getTasks($type)
    {
        return isset($this->tasks[$type]) ? $this->tasks[$type] : [];
    }

    /**
     * @param string $type
     * @param string $name
     * @return array
     */
    public function getTask($type, $name)
    {
        return isset($this->tasks[$type][$name]) ? $this->tasks[$type][$name] : null;
    }

    /**
     * @param string $type
     * @param string $name
     * @param string $execute
     */
    public function addTask($type, $name, $execute)
    {
        $type = (string) $type;
        $name = (string) $name;

        if (!in_array($type, ['pre_deploy', 'post_deploy'])) {
            throw new \LogicException(sprintf('Task type "%s" is invalid. Use one of: pre_deploy, post_deploy.', $type));
        }

        $this->tasks[$type][$name] = (string) $execute;
    }

    /**
     * @return bool
     */
    public function hasTests()
    {
        return !empty($this->tests);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasTest($name)
    {
        return isset($this->tasks[$name]);
    }

    /**
     * @return array
     */
    public function getTests()
    {
        return $this->tests;
    }

    /**
     * @param string $name
     * @return array
     */
    public function getTest($name)
    {
        return isset($this->tests[$name]) ? $this->tests[$name] : null;
    }

    /**
     * @param string $name
     * @param string $command
     */
    public function addTest($name, $command)
    {
        $name = (string) $name;

        if (isset($this->tests[$name])) {
            throw new \LogicException(sprintf('A test with name "%s" already exists.', $name));
        }

        $this->tests[$name] = (string) $command;
    }

    /**
     * @return \FlameCore\Seabreeze\Manifest\Project
     */
    public function getProject()
    {
        return $this->project;
    }
}
