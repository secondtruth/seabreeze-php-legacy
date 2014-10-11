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
 * The Synchronizer settings
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class SynchronizerSettings
{
    protected $mode;

    protected $source = array();

    protected $targets = array();

    protected $excludes = array();

    protected $environment;

    public function __construct($mode, array $settings, Environment $environment)
    {
        if (!isset($settings['from']) || !is_array($settings['from']))
            throw new \InvalidArgumentException(sprintf('Synchronizer settings for "%s" do not contain "from" key.', $this->mode));

        if (!isset($settings['to']) || !is_array($settings['to']))
            throw new \InvalidArgumentException(sprintf('Synchronizer settings for "%s" do not contain "to" key.', $this->mode));

        $this->mode = strtolower($mode);
        $this->environment = $environment;
        $this->source = $settings['from'];
        $this->targets = $settings['to'];

        if (isset($settings['exclude']) && !empty($settings['exclude']))
            $this->excludes = (array) $settings['exclude'];
    }

    public function export()
    {
        $data = array(
            'from' => $this->source,
            'to' => $this->targets
        );

        if (!empty($this->excludes))
            $data['exclude'] = $this->excludes;

        return $data;
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSource($source)
    {
        $this->source = $source;
    }

    public function getTargets()
    {
        return $this->targets;
    }

    public function addTarget(array $target)
    {
        $this->targets[] = $target;
    }

    public function getExcludes()
    {
        return $this->excludes;
    }

    public function setExcludes(array $excludes)
    {
        $this->excludes = $excludes;
    }

    public function exclude($exclude)
    {
        $this->excludes[] = $exclude;
    }

    public function getEnvironment()
    {
        return $this->environment;
    }
}
