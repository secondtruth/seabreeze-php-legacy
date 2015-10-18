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
    protected $environments = array();

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
        $filename = self::makeManifestPath($this->path, "$object.json");
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

        self::writeJson($filename, $object->export());
    }

    /**
     * @param string $directory
     * @return \FlameCore\Seabreeze\Manifest\Project
     */
    public static function fromDirectory($directory)
    {
        $configuration = self::readJson(self::makeManifestPath($directory, 'config.json'));

        $project = new self($directory);
        $project->import($configuration);

        $iterator = new \DirectoryIterator(self::makeManifestPath($directory, 'environments'));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() == 'json') {
                $name = $file->getBasename('.json');
                $settings = self::readJson($file->getRealPath());

                $environment = new Environment($name, $project);
                $environment->import($settings);
                $project->addEnvironment($environment);
            }
        }

        return $project;
    }

    /**
     * @param string $directory
     * @param string $name
     * @return \FlameCore\Seabreeze\Manifest\Project
     */
    public static function create($directory, $name = null)
    {
        $manifestDir = self::makeManifestPath($directory);

        if (!is_dir($manifestDir)) {
            mkdir($manifestDir, 0777, true);
        }

        $project = new static($directory);

        if ($name) {
            $project->setName($name);
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
            self::makeManifestPath($directory, 'config.json');
            return true;
        } catch (\LogicException $e) {
            return false;
        }
    }

    /**
     * @param string $directory
     * @param string $file
     * @return string
     */
    private static function makeManifestPath($directory, $file = null)
    {
        if (!is_dir($directory)) {
            throw new \DomainException(sprintf('Directory "%s" does not exist.', $directory));
        }

        $manifestDir = $directory.DIRECTORY_SEPARATOR.'.seabreeze';

        if (!$file) {
            return $manifestDir;
        }

        if (!is_dir($manifestDir)) {
            throw new \LogicException(sprintf('Directory "%s" contains no manifest.', $directory));
        }

        return $manifestDir.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $file);
    }

    private static function readJson($file)
    {
        return json_decode(file_get_contents($file), true);
    }

    private static function writeJson($file, array $data)
    {
        file_put_contents($file, json_encode($data));
    }
}
