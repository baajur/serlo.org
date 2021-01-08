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

namespace Notification\Entity;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Event\Entity\EventLogInterface;
use User\Entity;

interface NotificationInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param bool $seen
     * @return self
     */
    public function setSeen($seen);

    /**
     * @return bool
     */
    public function getSeen();

    /**
     * @param bool $email
     * @return self
     */
    public function setEmail($email);

    /**
     * @return bool
     */
    public function getEmail();

    /**
     * @param bool $sent
     * @return self
     */
    public function setEmailSent($sent);

    /**
     * @return bool
     */
    public function getEmailSent();

    /**
     * @return string
     */
    public function getEventName();

    /**
     * @return Entity\User
     */
    public function getUser();

    /**
     * @param Entity\UserInterface $user
     * @return self
     */
    public function setUser(Entity\UserInterface $user);

    /**
     * @return EventLogInterface[]|Collection
     */
    public function getEvents();

    /**
     * @param NotificationEventInterface $event
     * @return self
     */
    public function addEvent(NotificationEventInterface $event);

    /**
     * @return Collection
     */
    public function getActors();

    /**
     * @return Collection
     */
    public function getObjects();

    /**
     * @return Collection
     */
    public function getParameters();

    /**
     * @return DateTime $timestamp
     */
    public function getTimestamp();

    /**
     * @param DateTime $timestamp
     * @return void
     */
    public function setTimestamp(DateTime $timestamp);
}
