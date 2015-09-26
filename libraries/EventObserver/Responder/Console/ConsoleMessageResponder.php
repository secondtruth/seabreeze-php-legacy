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

namespace FlameCore\Seabreeze\EventObserver\Responder\Console;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * This Responder writes messages to the Console
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class ConsoleMessageResponder extends AbstractConsoleResponder
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $options
     */
    public function __construct(OutputInterface $output, array $options = [])
    {
        parent::__construct($output);

        $this->options = $options;

        $this->setListener('*.notice',  [$this, 'onNotice']);
        $this->setListener('*.warning', [$this, 'onWarning']);
        $this->setListener('*.error',   [$this, 'onError']);
    }

    /**
     * @param array $data
     */
    protected function onNotice(array $data)
    {
        if (!isset($data['message'])) {
            return;
        }

        $string = $this->format($data['message'], 'notice', '%message%');
        $this->output->writeln($string);
    }

    /**
     * @param array $data
     */
    protected function onWarning(array $data)
    {
        if (!isset($data['message'])) {
            return;
        }

        $string = $this->format($data['message'], 'warning', '<comment>%message%</comment>');
        $this->output->writeln($string);
    }

    /**
     * @param array $data
     */
    protected function onError(array $data)
    {
        if (!isset($data['message'])) {
            return;
        }

        $string = $this->format($data['message'], 'error', '<error>%message%</error>');
        $this->output->writeln($string);
    }

    /**
     * @param string $message
     * @param string $type
     * @param string $default
     * @return string
     */
    protected function format($message, $type, $default)
    {
        $format = isset($this->options[$type.'.format']) ? $this->options[$type.'.format'] : $default;

        return strtr($format, [
            '%message%' => $message
        ]);
    }
}
