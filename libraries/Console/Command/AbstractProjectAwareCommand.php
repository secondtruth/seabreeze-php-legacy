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

/**
 * The AbstractProjectAwareCommand class
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
abstract class AbstractProjectAwareCommand extends Command
{
    protected function getProject()
    {
        try {
            $directory = $this->getApplication()->getWorkingDir();
            $project = Project::fromDirectory($directory);
        } catch (\Exception $e) {
            throw new \DomainException('Missing or unreadable manifest file in working directory');
        }

        return $project;
    }
}
