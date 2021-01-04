<?php
/**
 * This file is part of Serlo.org.
 *
 * Copyright (c) 2013-2021 Serlo Education e.V.
 *
 * Licensed under the Apache License, Version 2.0 (the "License")
 * you may not use this file except in compliance with the License
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @copyright Copyright (c) 2013-2021 Serlo Education e.V.
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://github.com/serlo-org/serlo.org for the canonical source repository
 */
namespace Instance\Listener;

use Common\Listener\AbstractSharedListenerAggregate;
use Instance\Entity\InstanceProviderInterface;
use Instance\Exception;
use Instance\Manager\InstanceManagerInterface;
use Zend\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;

class IsolationBypassedListener extends AbstractSharedListenerAggregate
{
    protected $instanceManager;

    /**
     * @param InstanceManagerInterface $instanceManager
     */
    public function __construct(InstanceManagerInterface $instanceManager)
    {
        $this->instanceManager = $instanceManager;
    }

    /**
     * @param SharedEventManagerInterface $events
     */
    public function attachShared(SharedEventManagerInterface $events)
    {
        $events->attach(
            $this->getMonitoredClass(),
            'isolationBypassed',
            [$this, 'onBypass'],
            -1000
        );
    }

    /**
     * @param Event $e
     * @throws \Instance\Exception\RuntimeException
     */
    public function onBypass(Event $e)
    {
        $target = $e->getTarget();
        if (!$target instanceof InstanceProviderInterface) {
            throw new Exception\RuntimeException(
                sprintf(
                    'Expected instance of InstanceProviderInterface but got %s.',
                    is_object($target) ? get_class($target) : gettype($target)
                )
            );
        }

        $this->instanceManager->switchInstance($target->getInstance());
    }

    protected function getMonitoredClass()
    {
        return 'Instance\Manager\InstanceAwareEntityManager';
    }
}
