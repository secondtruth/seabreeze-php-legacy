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

namespace FlameCore\Seabreeze\Tests;

/**
 * Test Case class for Application commands
 */
abstract class ApplicationTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $workspace = null;

    /**
     * @var int
     */
    private $umask;

    public function setUp()
    {
        $this->umask = umask(0);
        $this->workspace = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.time().rand(0, 1000);
        mkdir($this->workspace, 0777, true);
        $this->workspace = realpath($this->workspace);
    }

    public function tearDown()
    {
        $this->clean($this->workspace);
        umask($this->umask);
    }

    protected function copyFixtures()
    {
        shell_exec(sprintf('cp -r %s %s 2>&1', __DIR__.'/fixtures/.seabreeze', $this->workspace));
    }

    /**
     * @param string $file
     */
    protected function clean($file)
    {
        if (is_dir($file) && !is_link($file)) {
            $dir = new \FilesystemIterator($file);
            foreach ($dir as $childFile) {
                $this->clean($childFile);
            }

            rmdir($file);
        } else {
            unlink($file);
        }
    }
}
