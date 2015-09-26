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

use FlameCore\Seabreeze\Manifest\Environment;
use FlameCore\EventObserver\ObserverInterface;

/**
 * The Runner interface
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
interface RunnerInterface
{
    /**
     * @param \FlameCore\Seabreeze\Manifest\Environment $environment
     * @return bool
     */
    public function run(Environment $environment);

    /**
     * @param \FlameCore\EventObserver\ObserverInterface $observer
     */
    public function observe(ObserverInterface $observer);

    /**
     * @return bool
     */
    public function isSuccessful();

    /**
     * @return array
     */
    public function getResults();

    /**
     * @return int
     */
    public function getSucceeded();

    /**
     * @return int
     */
    public function getFailed();
}
