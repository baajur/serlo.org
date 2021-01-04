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
namespace Renderer\Factory;

use FeatureFlags\Service as FeatureFlagsService;
use Instance\Manager\InstanceManager;
use Instance\Manager\InstanceManagerInterface;
use Raven_Client;
use Renderer\Renderer;
use Renderer\View\Helper\FormatHelper;
use Zend\Cache\Storage\StorageInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RendererFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var InstanceManagerInterface $instanceManager */
        $instanceManager = $serviceLocator->get(InstanceManager::class);
        /** @var StorageInterface $storage */
        $storage = $serviceLocator->get('Renderer\Storage\RendererStorage');
        /** @var Raven_Client $sentry */
        $sentry = $serviceLocator->get('Log\Sentry');
        $formatHelper = new FormatHelper();
        /** @var featureFlagsService $featureFlags */
        $featureFlags = $serviceLocator->get(FeatureFlagsService::class);
        $config = $serviceLocator->get('config');
        $editorRendererUrl = $config['services']['editor_renderer'];
        $legacyRendererUrl = $config['services']['legacy_editor_renderer'];
        $cacheEnabled = $config['renderer']['cache_enabled'];

        return new Renderer(
            $instanceManager,
            $featureFlags,
            $editorRendererUrl,
            $legacyRendererUrl,
            $formatHelper,
            $storage,
            $cacheEnabled,
            $sentry
        );
    }
}
