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
namespace Common\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container;

class RefererProvider extends AbstractPlugin
{
    protected $container;

    public function toUrl($default = '/')
    {
        $referer = $this->getController()
            ->getRequest()
            ->getHeader('Referer');
        $referer = $referer ? $referer->getUri() : $default;

        return $referer;
    }

    public function store($id = 'default')
    {
        if (!$this->container) {
            $this->container = new Container($this->normalizeId($id));
        }
        $this->container->ref = $this->toUrl();

        return $this;
    }

    public function fromStorage($default = '/', $id = 'default')
    {
        $container = new Container($this->normalizeId($id));

        return isset($container->ref)
            ? $container->ref
            : $this->toUrl($default);
    }

    protected function normalizeId($id)
    {
        return 'Ref\\' . str_replace('-', '\\', $id);
    }
}
