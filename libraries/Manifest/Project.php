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

use Symfony\Component\Yaml\Yaml;

/**
 * The Project manifest
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class Project implements ManifestInterface
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $name = 'Project';

    /**
     * @var \FlameCore\Seabreeze\Manifest\Environment[]
     */
    protected $environments;

    /**
     * @param string $directory
     */
    public function __construct($directory)
    {
        $this->path = (string) $directory;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return 'config';
    }

    /**
     * {@inheritdoc}
     */
    public function import(array $configuration)
    {
        if (isset($configuration['name']) && !empty($configuration['name'])) {
            $this->name = (string) $configuration['name'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function export()
    {
        return array(
            'name' => $this->name
        );
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $this->writeManifest($this);

        foreach ($this->environments as $environment) {
            $environment->flush();
        }
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
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
     * @param string $environment
     * @return bool
     */
    public function hasEnvironment($environment)
    {
        return isset($this->environments[$environment]);
    }

    /**
     * @return \FlameCore\Seabreeze\Manifest\Environment[]
     */
    public function getEnvironments()
    {
        return $this->environments;
    }

    /**
     * @param string $environment
     * @return \FlameCore\Seabreeze\Manifest\Environment
     */
    public function getEnvironment($environment)
    {
        return isset($this->environments[$environment]) ? $this->environments[$environment] : null;
    }

    /**
     * @param \FlameCore\Seabreeze\Manifest\Environment $environment
     */
    public function addEnvironment(Environment $environment)
    {
        $name = $environment->getName();
        $this->environments[$name] = $environment;
    }

    /**
     * @param \FlameCore\Seabreeze\Manifest\ManifestInterface $object
     * @param bool $mustExist
     */
    public function writeManifest(ManifestInterface $object, $mustExist = false)
    {
        $filename = self::makeManifestPath($this->path, "$object.yml");
        $directory = dirname($filename);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (is_file($filename)) {
            if (!is_writable($filename)) {
                throw new \DomainException(sprintf('Manifest file "%s" unwritable.', $filename));
            }
        } else {
            if ($mustExist) {
                throw new \DomainException(sprintf('Manifest file "%s" missing.', $filename));
            }
        }

        $yaml = Yaml::dump($object->export(), 4);
        file_put_contents($filename, $yaml);
    }

    /**
     * @param string $directory
     * @return \FlameCore\Seabreeze\Manifest\Project
     */
    public static function fromDirectory($directory)
    {
        $configuration = Yaml::parse(self::makeManifestPath($directory, 'config.yml'));

        $project = new self($directory);
        $project->import($configuration);

        $iterator = new \DirectoryIterator(self::makeManifestPath($directory, 'environments'));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() == 'yml') {
                $name = $file->getBasename('.yml');
                $settings = Yaml::parse($file->getRealPath());

                $environment = new Environment($name, $project);
                $environment->import($settings);
                $project->addEnvironment($environment);
            }
        }

        return $project;
    }

    /**
     * @param string $directory
     * @return bool
     */
    public static function exists($directory)
    {
        try {
            self::makeManifestPath($directory, 'config.yml');
            return true;
        } catch (\LogicException $e) {
            return false;
        }
    }

    /**
     * @param string $directory
     * @param string $manifest
     * @return string
     */
    private static function makeManifestPath($directory, $manifest)
    {
        if (!is_dir($directory)) {
            throw new \DomainException(sprintf('Directory "%s" does not exist.', $directory));
        }

        $manifestDir = $directory . DIRECTORY_SEPARATOR . '.seabreeze';

        if (!is_dir($manifestDir)) {
            throw new \LogicException(sprintf('Directory "%s" contains no manifest.', $directory));
        }

        return realpath($manifestDir . DIRECTORY_SEPARATOR . $manifest);
    }
}
