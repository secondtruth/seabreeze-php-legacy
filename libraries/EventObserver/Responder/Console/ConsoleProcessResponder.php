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
 * This Responder writes messages to the Console on process start/finish
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class ConsoleProcessResponder extends AbstractConsoleResponder
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

        $this->setListener('*.start',  [$this, 'onStart']);
        $this->setListener('*.finish', [$this, 'onFinish']);
    }

    /**
     * @param array $data
     */
    protected function onStart(array $data)
    {
        $message = isset($this->options['start.message']) ? $this->options['start.message'] : false;

        if ($message) {
            $string = $this->interpolate($message, $data);
            $this->output->writeln($string);
        }
    }

    /**
     * @param array $data
     */
    protected function onFinish(array $data)
    {
        $message = isset($this->options['finish.message']) ? $this->options['finish.message'] : false;

        if ($message) {
            $string = $this->interpolate($message, $data);
            $this->output->writeln($string);
        }
    }

    /**
     * @param string $message
     * @param array $context
     * @return string
     */
    protected function interpolate($message, array $context = [])
    {
        $replace = array();
        foreach ($context as $key => $value) {
            $replace['%'.$key.'%'] = $this->transform($value, $key);
        }

        return strtr($message, $replace);
    }

    /**
     * @param string $value
     * @param string $key
     * @return bool
     */
    protected function transform($value, $key)
    {
        return isset($this->options['transform.'.$key][$value]) ? $this->options['transform.'.$key][$value] : $value;
    }
}
