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

namespace FlameCore\Seabreeze\EventObserver;

use FlameCore\EventObserver\Observer;
use FlameCore\EventObserver\Provider\ProviderInterface;

/**
 * The DeploymentObserver class
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class DeploymentObserver extends Observer
{
    /**
     * @param \FlameCore\EventObserver\Provider\ProviderInterface $provider
     */
    public function __construct(ProviderInterface $provider)
    {
        $responder = $provider->getResponder('process');
        $this->addResponder('backup', $responder, ['action' => 'Backing up']);
        $this->addResponder('sync', $responder, ['action' => 'Synchronizing']);

        $responder = $provider->getResponder('progress');
        $this->addResponder('backup', $responder);
        $this->addResponder('sync', $responder);

        $responder = $provider->getResponder('message');
        $this->addResponder('deploy', $responder);
    }
}
