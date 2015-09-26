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

namespace FlameCore\Seabreeze\Runner;

use FlameCore\EventObserver\ObserverInterface;
use FlameCore\Seabreeze\Manifest\Environment;
use Symfony\Component\Process\Process;

/**
 * The abstract Runner class
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
abstract class AbstractRunner implements RunnerInterface
{
    /**
     * @var \FlameCore\EventObserver\ObserverInterface
     */
    protected $observer;

    /**
     * @var int
     */
    protected $succeeded;

    /**
     * @var int
     */
    protected $failed;

    /**
     * @var bool
     */
    protected $success = true;

    /**
     * @var array
     */
    protected $results = array();

    /**
     * {@inheritdoc}
     */
    public function run(Environment $environment)
    {
        $this->doRun($environment);

        return $this->success;
    }

    /**
     * {@inheritdoc}
     */
    public function observe(ObserverInterface $observer)
    {
        $this->observer = $observer;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return $this->success;
    }

    /**
     * {@inheritdoc}
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * {@inheritdoc}
     */
    public function getSucceeded()
    {
        return $this->succeeded;
    }

    /**
     * {@inheritdoc}
     */
    public function getFailed()
    {
        return $this->failed;
    }

    /**
     * @param \FlameCore\Seabreeze\Manifest\Environment $environment
     */
    abstract protected function doRun(Environment $environment);
}
