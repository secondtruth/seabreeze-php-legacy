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
    protected $name;

    protected $synchronizers = array();

    protected $tasks = array();

    protected $project;

    public function __construct($name, Project $project)
    {
        $this->name = (string) $name;
        $this->project = $project;
    }

    public function __toString()
    {
        return "environments/$this->name";
    }

    public function import(array $configuration)
    {
        if (isset($configuration['synchronizers'])) {
            $synchronizers = $configuration['synchronizers'];
            foreach ($synchronizers as $mode => $settings) {
                $mode = strtolower($mode);
                $this->synchronizers[$mode] = new SynchronizerSettings($mode, (array) $settings, $this);
            }
        }

        if (isset($configuration['tasks'])) {
            $tasks = $configuration['tasks'];
            foreach ($tasks as $type => $execute) {
                $this->tasks[strtolower($type)] = (array) $execute;
            }
        }
    }

    public function export()
    {
        $data = array();

        foreach ($this->synchronizers as $type => $settings) {
            $data['synchronizers'][$type] = $settings->export();
        }

        foreach ($this->tasks as $type => $executes) {
            $data['tasks'][$type] = $executes;
        }

        return $data;
    }

    public function flush()
    {
        $project = $this->getProject();
        $project->writeManifest($this);
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = (string) $name;
    }

    public function hasSynchronizer($name)
    {
        return isset($this->synchronizers[$name]);
    }

    public function getSynchronizers()
    {
        return $this->synchronizers;
    }

    public function getSynchronizer($name)
    {
        return isset($this->synchronizers[$name]) ? $this->synchronizers[$name] : null;
    }

    public function hasTask($name)
    {
        return isset($this->tasks[$name]);
    }

    public function getTasks()
    {
        return $this->tasks;
    }

    public function getTask($name)
    {
        return isset($this->tasks[$name]) ? $this->tasks[$name] : null;
    }

    public function getProject()
    {
        return $this->project;
    }
}
