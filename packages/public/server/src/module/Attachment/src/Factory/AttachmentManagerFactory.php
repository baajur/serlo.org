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

namespace Attachment\Factory;

use Attachment\Manager\AttachmentManager;
use ClassResolver\ClassResolverFactoryTrait;
use Common\Factory\AuthorizationServiceFactoryTrait;
use Common\Factory\EntityManagerFactoryTrait;
use Instance\Factory\InstanceManagerFactoryTrait;
use Type\Factory\TypeManagerFactoryTrait;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AttachmentManagerFactory implements FactoryInterface
{
    use ClassResolverFactoryTrait, EntityManagerFactoryTrait;
    use InstanceManagerFactoryTrait;
    use TypeManagerFactoryTrait, AuthorizationServiceFactoryTrait;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return AttachmentManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $moduleOptions */
        $moduleOptions = $serviceLocator->get(
            'Attachment\Options\ModuleOptions'
        );
        $authService = $this->getAuthorizationService($serviceLocator);
        $classResolver = $this->getClassResolver($serviceLocator);
        $entityManager = $this->getEntityManager($serviceLocator);
        $instanceManager = $this->getInstanceManager($serviceLocator);
        $typeManager = $this->getTypeManager($serviceLocator);
        $uploadSecret = $serviceLocator->get('config')['upload_secret'];

        $instance = new AttachmentManager(
            $authService,
            $classResolver,
            $instanceManager,
            $moduleOptions,
            $uploadSecret,
            $typeManager,
            $entityManager
        );

        return $instance;
    }
}
