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
namespace Normalizer\Adapter;

use User\Entity\UserInterface;

class UserAdapter extends AbstractAdapter
{
    /** @var UserInterface */
    protected $object;

    protected function getContent()
    {
        return $this->object->getUsername();
    }

    protected function getId()
    {
        return $this->object->getId();
    }

    protected function getKeywords()
    {
        return [];
    }

    protected function getPreview()
    {
        return $this->object->getUsername();
    }

    protected function getRouteName()
    {
        return 'user/profile';
    }

    protected function getRouteParams()
    {
        return ['id' => $this->object->getId()];
    }

    protected function getCreationDate()
    {
        return $this->object->getDate();
    }

    protected function getTitle()
    {
        return $this->object->getUsername();
    }

    protected function getType()
    {
        return 'user';
    }
    protected function isTrashed()
    {
        return $this->object->isTrashed();
    }
}
