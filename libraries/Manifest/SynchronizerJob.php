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

namespace FlameCore\Seabreeze\Manifest;

/**
 * The Synchronizer settings
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class SynchronizerJob
{
    /**
     * @var string
     */
    protected $mode;

    /**
     * @var array
     */
    protected $source = array();

    /**
     * @var array
     */
    protected $targets = array();

    /**
     * @var array
     */
    protected $excludes = array();

    /**
     * @var \FlameCore\Seabreeze\Manifest\Environment
     */
    protected $environment;

    /**
     * @param string $mode
     * @param array $settings
     * @param \FlameCore\Seabreeze\Manifest\Environment $environment
     */
    public function __construct($mode, array $settings, Environment $environment)
    {
        if (!isset($settings['from']) || !is_array($settings['from'])) {
            throw new \InvalidArgumentException(sprintf('Synchronizer settings for "%s" do not contain "from" key.', $this->mode));
        }

        if (!isset($settings['to']) || !is_array($settings['to'])) {
            throw new \InvalidArgumentException(sprintf('Synchronizer settings for "%s" do not contain "to" key.', $this->mode));
        }

        $this->mode = strtolower($mode);
        $this->environment = $environment;
        $this->source = $settings['from'];
        $this->targets = $settings['to'];

        if (isset($settings['exclude']) && !empty($settings['exclude'])) {
            $this->excludes = (array) $settings['exclude'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function export()
    {
        $data = array(
            'from' => $this->source,
            'to' => $this->targets
        );

        if (!empty($this->excludes)) {
            $data['exclude'] = $this->excludes;
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @return array
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return array
     */
    public function getTargets()
    {
        return $this->targets;
    }

    /**
     * @param array $target
     */
    public function addTarget(array $target)
    {
        $this->targets[] = $target;
    }

    /**
     * @return array
     */
    public function getExcludes()
    {
        return $this->excludes;
    }

    /**
     * @param array $excludes
     */
    public function setExcludes(array $excludes)
    {
        $this->excludes = $excludes;
    }

    /**
     * @param string $exclude
     */
    public function exclude($exclude)
    {
        $this->excludes[] = $exclude;
    }

    /**
     * @return \FlameCore\Seabreeze\Manifest\Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }
}
