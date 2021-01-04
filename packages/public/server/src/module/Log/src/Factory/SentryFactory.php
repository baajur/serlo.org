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

namespace Log\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SentryFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Raven_Client
     * @throws \Raven_Exception
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $sentryConfig = $config['sentry_options'];

        $appRoot = dirname(__DIR__, 5);
        $client = new \Raven_Client(
            // Deactivate sentry if no DSN is given
            isset($sentryConfig['dsn']) ? $sentryConfig['dsn'] : null,
            [
                'excluded_app_paths' => [$appRoot . '/src/data'],
                'release' => 'serlo-org-server@' . $sentryConfig['version'],
                'tags' => [
                    'php_version' => phpversion(),
                ],
            ]
        );

        $client->install();

        return $client;
    }
}
