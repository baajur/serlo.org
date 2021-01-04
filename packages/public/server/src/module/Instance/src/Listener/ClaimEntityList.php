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
namespace Instance\Listener;

use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;
use Instance\Entity\InstanceInterface;

class ClaimEntityListener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return ['prePersist'];
    }

    /**
     * @param EventArgs $args
     */
    public function prePersist(EventArgs $args)
    {
        $em = $args->getEntityManager();
        $entity = $args->getEntity();

        if (
            $entity instanceof InstanceInterface &&
            !$entity->getInstance() &&
            ($tenant = $em->getTenant())
        ) {
            $entity->setTenant($tenant);
        }
    }
}
