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
namespace Link;

return [
    'di' => [
        'definition' => [
            'class' => [
                __NAMESPACE__ . '\Listener\EntityManagerListener' => [
                    'setLinkService' => [
                        'required' => true,
                    ],
                    'setEntityManager' => [
                        'required' => true,
                    ],
                    'setModuleOptions' => [
                        'required' => true,
                    ],
                ],
                __NAMESPACE__ . '\Service\LinkService' => [
                    'setObjectManager' => [
                        'required' => true,
                    ],
                    'setTypeManager' => [
                        'required' => true,
                    ],
                    'setAuthorizationService' => [
                        'required' => true,
                    ],
                ],
            ],
        ],
        'instance' => [
            'preferences' => [
                'Link\Service\LinkServiceInterface' =>
                    'Link\Service\LinkService',
            ],
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity'],
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                ],
            ],
        ],
    ],
];
