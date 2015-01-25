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
            $replace['%'.$key.'%'] = $value;
        }

        return strtr($message, $replace);
    }
}
