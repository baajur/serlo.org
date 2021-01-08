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
namespace Event\Factory;

use Event\Listener\AbstractListener;
use Instance\Factory\InstanceManagerFactoryTrait;
use User\Factory\UserManagerFactoryTrait;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractListenerFactory implements FactoryInterface
{
    use UserManagerFactoryTrait, InstanceManagerFactoryTrait;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $listener = $this->getListenerClassName();
        $eventManager = $serviceLocator->get('Event\EventManager');
        $userManager = $this->getUserManager($serviceLocator);
        $instanceManager = $this->getInstanceManager($serviceLocator);

        /* @var $listener AbstractListener */
        $listener = new $listener(
            $eventManager,
            $instanceManager,
            $userManager
        );

        return $listener;
    }

    abstract protected function getListenerClassName();
}
