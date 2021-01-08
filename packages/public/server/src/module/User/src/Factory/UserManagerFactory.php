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

namespace User\Factory;

use User\Manager\UserManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserManagerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $instance = new UserManager();
        $objectManager = $serviceLocator->get(
            'doctrine.entitymanager.orm_default'
        );
        $authService = $serviceLocator->get(
            'Zend\Authentication\AuthenticationService'
        );
        $classResolver = $serviceLocator->get('ClassResolver\ClassResolver');
        $hydrator = $serviceLocator->get('User\Hydrator\UserHydrator');
        $authorizationService = $serviceLocator->get(
            'ZfcRbac\Service\AuthorizationService'
        );

        $instance->setObjectManager($objectManager);
        $instance->setClassResolver($classResolver);
        $instance->setHydrator($hydrator);
        $instance->setAuthenticationService($authService);
        $instance->setAuthorizationService($authorizationService);
        $instance->setMysqlTimestampForActiveCommunity(
            $serviceLocator->get('config')[
                'mysql_timestamp_for_active_community'
            ]
        );

        return $instance;
    }
}
