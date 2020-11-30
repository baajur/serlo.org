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
namespace Normalizer\Adapter;

use DateTime;
use Page\Entity\PageRepositoryInterface;
use Page\Entity\PageRevisionInterface;
use Zend\Mvc\Router\RouteMatch;
use Zend\Navigation\Page\Mvc;

class PageRepositoryAdapter extends AbstractAdapter
{
    /** @var PageRepositoryInterface */
    protected $object;

    protected function getContent()
    {
        $revision = $this->getRevision();
        if ($revision) {
            /** @var PageRevisionInterface $revision */
            return $revision->getContent();
        }
        return '';
    }

    protected function getCreationDate()
    {
        $revision = $this->getRevision();
        if ($revision) {
            /** @var PageRevisionInterface $revision */
            return $revision->getDate();
        }
        return new DateTime();
    }

    protected function getId()
    {
        return $this->object->getId();
    }

    protected function getKeywords()
    {
        return explode(' ', $this->getTitle());
    }

    protected function getPreview()
    {
        $revision = $this->getRevision();
        if ($revision) {
            /** @var PageRevisionInterface $revision */
            return $revision->getContent();
        }
        return '';
    }

    protected function getRevision()
    {
        $revision = $this->object->getCurrentRevision();
        if (!$revision) {
            $revision = $this->object->getRevisions()->current();
        }
        return $revision;
    }

    protected function getRouteName()
    {
        return 'page/view';
    }

    protected function getRouteParams()
    {
        return [
            'page' => $this->object->getId(),
        ];
    }

    protected function getTitle()
    {
        $revision = $this->getRevision();
        if ($revision) {
            /** @var PageRevisionInterface $revision */
            return $revision->getTitle();
        }
        return '';
    }

    protected function getType()
    {
        return 'Page';
    }

    protected function isTrashed()
    {
        return $this->object->isTrashed();
    }
}
