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
 * The Manifest interface
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
interface ManifestInterface
{
    /**
     * @return string
     */
    public function __toString();

    /**
     * @param array $configuration
     */
    public function import(array $configuration);

    /**
     * @return array
     */
    public function export();

    /**
     * @return void
     */
    public function flush();
}
