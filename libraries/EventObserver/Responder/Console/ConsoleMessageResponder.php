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

        $format = isset($this->options['notice.format']) ? $this->options['notice.format'] : '%message%';
        $string = $this->format($data['message'], $format);

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

        $format = isset($this->options['warning.format']) ? $this->options['warning.format'] : '<comment>%message%</comment>';
        $string = $this->format($data['message'], $format);

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

        $format = isset($this->options['error.format']) ? $this->options['error.format'] : '<error>%message%</error>';
        $string = $this->format($data['message'], $format);

        $this->output->writeln($string);
    }

    /**
     * @param string $message
     * @param string $format
     * @return string
     */
    protected function format($message, $format)
    {
        return strtr($format, [
            '%message%' => $message
        ]);
    }
}
