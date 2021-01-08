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
namespace Attachment\Entity;

use Instance\Entity\InstanceProviderInterface;

interface FileInterface extends InstanceProviderInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return \DateTime
     */
    public function getDateTime();

    /**
     * @return string
     */
    public function getLocation();

    /**
     * @return int
     */
    public function getSize();

    /**
     * @return string
     */
    public function getFilename();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return ContainerInterface
     */
    public function getAttachment();

    /**
     * @param ContainerInterface $attachment
     * @return void
     */
    public function setAttachment(ContainerInterface $attachment);

    /**
     * @param string $location
     * @return void
     */
    public function setLocation($location);

    /**
     * @param int $size
     * @return void
     */
    public function setSize($size);

    /**
     * @param string $filename
     * @return void
     */
    public function setFilename($filename);

    /**
     * @param string $type
     * @return void
     */
    public function setType($type);
}
