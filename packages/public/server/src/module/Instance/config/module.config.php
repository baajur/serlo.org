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
namespace Instance;

return [
    'zfc_rbac' => [
        'assertion_map' => [
            'instance.get' => 'Authorization\Assertion\InstanceAssertion',
        ],
    ],
    'doctrine_factories' => [
        'entitymanager' =>
            __NAMESPACE__ . '\Factory\InstanceAwareEntityManagerFactory',
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
    'view_helpers' => [
        'factories' => [
            'currentLanguage' =>
                __NAMESPACE__ . '\Factory\LanguageHelperFactory',
        ],
    ],
    'service_manager' => [
        'invokables' => [__NAMESPACE__ . '\Strategy\StrategyPluginManager'],
        'factories' => [
            __NAMESPACE__ . '\Manager\InstanceManager' =>
                __NAMESPACE__ . '\Factory\InstanceManagerFactory',
            __NAMESPACE__ . '\Options\InstanceOptions' =>
                __NAMESPACE__ . '\Factory\InstanceOptionsFactory',
            __NAMESPACE__ . '\Listener\IsolationBypassedListener' =>
                __NAMESPACE__ . '\Factory\IsolationBypassedListenerFactory',
            'Zend\I18n\Translator\TranslatorInterface' =>
                'Zend\I18n\Translator\TranslatorServiceFactory',
        ],
    ],
    'di' => [
        'instance' => [
            'preferences' => [
                __NAMESPACE__ . '\Manager\InstanceManagerInterface' =>
                    __NAMESPACE__ . '\Manager\InstanceManager',
            ],
        ],
    ],
    'class_resolver' => [
        __NAMESPACE__ . '\Entity\InstanceInterface' =>
            __NAMESPACE__ . '\Entity\Instance',
    ],
];
