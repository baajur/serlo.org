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

namespace Uuid;

return [
    'service_manager' => [
        'factories' => [
            __NAMESPACE__ . '\Options\ModuleOptions' =>
                __NAMESPACE__ . '\Factory\ModuleOptionsFactory',
            __NAMESPACE__ . '\Manager\UuidManager' =>
                __NAMESPACE__ . '\Factory\UuidManagerFactory',
        ],
    ],
    'class_resolver' => [
        __NAMESPACE__ . '\Entity\UuidInterface' =>
            __NAMESPACE__ . '\Entity\Uuid',
    ],
    'view_helpers' => [
        'invokables' => [
            'uuidForm' => __NAMESPACE__ . '\View\Helper\FormHelper',
        ],
        'factories' => [
            'uuid' => __NAMESPACE__ . '\Factory\UuidHelperFactory',
        ],
    ],
    'uuid' => [],
    'router' => [
        'routes' => [
            'uuid' => [
                'type' => 'segment',
                'options' => [
                    // Do not change this or /:id refs wont work (see normalizer route config)
                    'route' => '',
                ],
                'child_routes' => [
                    'trash' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/uuid/trash/:id',
                            'defaults' => [
                                'controller' =>
                                    __NAMESPACE__ .
                                    '\Controller\UuidController',
                                'action' => 'trash',
                            ],
                        ],
                    ],
                    'recycle-bin' => [
                        'type' => 'literal',
                        'options' => [
                            'route' => '/uuid/recycle-bin',
                            'defaults' => [
                                'controller' =>
                                    __NAMESPACE__ .
                                    '\Controller\UuidController',
                                'action' => 'recycleBin',
                            ],
                        ],
                    ],
                    'restore' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/uuid/restore/:id',
                            'defaults' => [
                                'controller' =>
                                    __NAMESPACE__ .
                                    '\Controller\UuidController',
                                'action' => 'restore',
                            ],
                        ],
                    ],
                    'purge' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/uuid/purge/:id',
                            'defaults' => [
                                'controller' =>
                                    __NAMESPACE__ .
                                    '\Controller\UuidController',
                                'action' => 'purge',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'di' => [
        'allowed_controllers' => [__NAMESPACE__ . '\Controller\UuidController'],
        'definition' => [
            'class' => [
                __NAMESPACE__ . '\Controller\UuidController' => [
                    'setUuidManager' => [
                        'required' => true,
                    ],
                    'setInstanceManager' => [
                        'required' => true,
                    ],
                ],
            ],
        ],
        'instance' => [
            'preferences' => [
                __NAMESPACE__ . '\Manager\UuidManagerInterface' =>
                    __NAMESPACE__ . '\Manager\UuidManager',
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
