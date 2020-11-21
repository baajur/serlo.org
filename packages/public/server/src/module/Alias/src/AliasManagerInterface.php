<?php
/**
 * This file is part of Serlo.org.
 *
 * Copyright (c) 2013-2020 Serlo Education e.V.
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
 * @copyright Copyright (c) 2013-2020 Serlo Education e.V.
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://github.com/serlo-org/serlo.org for the canonical source repository
 */
namespace Alias;

use Alias\Entity\AliasInterface;
use Common\ObjectManager\Flushable;
use Instance\Entity\InstanceInterface;
use Uuid\Entity\UuidInterface;

interface AliasManagerInterface extends Flushable
{
    /**
     * @param UuidInterface $uuid
     * @param bool $instanceAware
     * @return AliasInterface
     */
    public function findAliasByObject(
        UuidInterface $uuid,
        $instanceAware = true
    );

    /**
     * @param string            $source
     * @param InstanceInterface $instance
     * @return string
     */
    public function findAliasBySource($source, InstanceInterface $instance);

    /**
     * @param                   $alias
     * @param InstanceInterface $instance
     * @return mixed
     */
    public function findCanonicalAlias($alias, InstanceInterface $instance);

    /**
     * @param string $alias
     * @param InstanceInterface $instance
     * @param bool   $useCache
     * @return string
     */
    public function findSourceByAlias(
        $alias,
        InstanceInterface $instance,
        $useCache = false
    );

    /**
     * @param $alias,
     * @param InstanceInterface $instance
     * @return Entity\AliasInterface[]
     */
    public function findAliases($alias, InstanceInterface $instance);
}
