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
namespace Uuid\Manager;

use Common\ObjectManager\Flushable;
use Doctrine\Common\Collections\Collection;
use Uuid\Entity\UuidInterface;
use Zend\Paginator\Paginator;

interface UuidManagerInterface extends Flushable
{
    /**
     * Finds all Uuids
     * <code>
     *    $collection = $um->findAll();
     * </code>
     *
     * @return UuidInterface[]|Collection
     */
    public function findAll();

    /**
     * Finds trashed Uuids together with the date of the trash event
     * for a given instance.
     * <code>
     * $elements = $um->findTrashed($page, $instance);
     * foreach($elements as $element)
     * {
     *    echo $element["entity"]->getId();
     *    echo $element["date"];
     * }
     * </code>
     *
     * @param int $page
     * @param int $instance
     * @return Paginator
     */
    public function findTrashed($page, $instance);

    /**
     * Get an Uuid.
     * <code>
     * $um->getUuid('1');
     * $um->getUuid('someH4ash');
     * $um->getUuid($uuidEntity);
     * </code>
     *
     * @param int|string|UuidInterface $key
     * @param bool                     $bypassIsolation
     * @param bool                     $instanceAware
     * @return UuidInterface $uuid
     */
    public function getUuid(
        $key,
        $bypassIsolation = false,
        $instanceAware = true
    );

    /**
     * @param int $id
     * @return void
     */
    public function purgeUuid($id);

    /**
     * @return void
     */
    public function clearDeadUuids();

    /**
     * @param int $id
     * @return void
     */
    public function restoreUuid($id);

    /**
     * @param int $id
     * @return void
     */
    public function trashUuid($id);
}
