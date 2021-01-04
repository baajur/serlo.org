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
namespace Discussion;

use Common\ObjectManager\Flushable;
use Discussion\Entity\CommentInterface;
use Instance\Entity\InstanceInterface;
use Uuid\Entity\UuidInterface;
use Zend\Form\FormInterface;
use Doctrine\Common\Collections\Collection;

interface DiscussionManagerInterface extends Flushable
{
    /**
     * @param FormInterface $form
     * @return CommentInterface
     */
    public function commentDiscussion(FormInterface $form);

    /**
     * @param InstanceInterface $instance
     * @param int               $page
     * @param int               $limit
     * @return CommentInterface[]|Collection
     */
    public function findDiscussionsByInstance(
        InstanceInterface $instance,
        $page,
        $limit = 20
    );

    /**
     * Finds discussions on a uuid
     *
     * @param UuidInterface $uuid
     * @return CommentInterface[]|Collection
     */
    public function findDiscussionsOn(UuidInterface $uuid);

    /**
     * @param Collection $collection
     * @return Collection
     */
    public function sortDiscussions(Collection $collection);

    /**
     * Returns a comment
     *
     * @param int $id
     * @return CommentInterface
     */
    public function getComment($id);

    /**
     * @param FormInterface $form
     * @return CommentInterface
     */
    public function startDiscussion(FormInterface $form);

    /**
     * @param int $commentId
     * @return void
     */
    public function toggleArchived($commentId);
}
