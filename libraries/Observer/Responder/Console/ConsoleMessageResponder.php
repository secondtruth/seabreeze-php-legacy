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

namespace FlameCore\Seabreeze\Observer\Responder\Console;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * This Responder writes messages to the Console
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class ConsoleMessageResponder extends AbstractConsoleResponder
{
    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        parent::__construct($output);

        $this->setListener('*.notice',  [$this, 'onNotice']);
        $this->setListener('*.warning', [$this, 'onWarning']);
        $this->setListener('*.error',   [$this, 'onError']);
    }

    /**
     * @param array $data
     */
    protected function onNotice(array $data)
    {
        if (!isset($data['message']))
            return;

        $this->output->writeln($data['message']);
    }

    /**
     * @param array $data
     */
    protected function onWarning(array $data)
    {
        if (!isset($data['message']))
            return;

        $this->output->writeln(sprintf('<comment>%s</comment>', $data['message']));
    }

    /**
     * @param array $data
     */
    protected function onError(array $data)
    {
        if (!isset($data['message']))
            return;

        $this->output->writeln(sprintf('<error>%s</error>', $data['message']));
    }
}
