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

use Attachment\Entity\ContainerInterface;
use Authorization\Service\AuthorizationAssertionTrait;
use Blog\Entity\PostInterface;
use ClassResolver\ClassResolverAwareTrait;
use ClassResolver\ClassResolverInterface;
use Common\Traits\FlushableTrait;
use Discussion\Entity\CommentInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Util\ClassUtils;
use Entity\Entity\EntityInterface;
use Entity\Entity\RevisionInterface;
use Instance\Manager\InstanceAwareEntityManager;
use Instance\Manager\InstanceAwareObjectManagerAwareTrait;
use Page\Entity\PageRepositoryInterface;
use Page\Entity\PageRevisionInterface;
use Taxonomy\Entity\TaxonomyTermInterface;
use User\Entity\UserInterface;
use Uuid\Entity\UuidInterface;
use Uuid\Exception;
use Uuid\Exception\NotFoundException;
use Uuid\Options\ModuleOptions;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use ZfcRbac\Service\AuthorizationService;

class UuidManager implements UuidManagerInterface
{
    use InstanceAwareObjectManagerAwareTrait, ClassResolverAwareTrait;
    use EventManagerAwareTrait, FlushableTrait;
    use AuthorizationAssertionTrait;

    /**
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     * @param AuthorizationService $authorizationService
     * @param ClassResolverInterface $classResolver
     * @param ModuleOptions $moduleOptions
     * @param InstanceAwareEntityManager $objectManager
     */
    public function __construct(
        AuthorizationService $authorizationService,
        ClassResolverInterface $classResolver,
        ModuleOptions $moduleOptions,
        InstanceAwareEntityManager $objectManager
    ) {
        $this->authorizationService = $authorizationService;
        $this->classResolver = $classResolver;
        $this->objectManager = $objectManager;
        $this->moduleOptions = $moduleOptions;
    }

    /**
     * {@inheritDoc}
     */
    public function findAll()
    {
        $className = $this->getClassResolver()->resolveClassName(
            'Uuid\Entity\UuidInterface'
        );
        $entities = $this->getObjectManager()
            ->getRepository($className)
            ->findAll();
        return new ArrayCollection($entities);
    }

    /**
     * {@inheritDoc}
     */
    public function findTrashed($page, $instance)
    {
        $className = $this->getClassResolver()->resolveClassName(
            'Uuid\Entity\UuidInterface'
        );
        $eventLogClassName = $this->getClassResolver()->resolveClassName(
            'Event\Entity\EventLogInterface'
        );
        $eventTypeClassName = $this->getClassResolver()->resolveClassName(
            'Event\Entity\EventInterface'
        );
        $entityTypeClassName = $this->getClassResolver()->resolveClassName(
            'Entity\Entity\EntityInterface'
        );
        $taxonomyTypeClassName = $this->getClassResolver()->resolveClassName(
            'Taxonomy\Entity\TaxonomyInterface'
        );
        $taxonomyTermTypeClassName = $this->getClassResolver()->resolveClassName(
            'Taxonomy\Entity\TaxonomyTermInterface'
        );
        $results = $this->objectManager
            ->createQueryBuilder()
            ->select('u')
            ->addSelect('MAX(e.date) AS date')
            ->from($className, 'u')
            ->leftJoin($eventLogClassName, 'e', 'WITH', 'e.uuid = u')
            ->leftJoin($eventTypeClassName, 't', 'WITH', 'e.event = t')
            ->leftJoin($entityTypeClassName, 'ent', 'WITH', 'u.id = ent.id')
            ->leftJoin($taxonomyTermTypeClassName, 'tt', 'WITH', 'u = tt')
            ->leftJoin(
                $taxonomyTypeClassName,
                'tax',
                'WITH',
                'tt.taxonomy = tax'
            )
            ->where('u.trashed = :trashed')
            ->andWhere('t.name = :type')
            ->andWhere('ent.instance = :instance OR ent.instance IS NULL')
            ->andWhere('tax.instance = :instance OR tax.instance IS NULL')
            ->groupBy('u')
            ->orderBy('date', 'DESC')
            ->setParameter('trashed', true)
            ->setParameter('type', 'uuid/trash')
            ->setParameter('instance', $instance)
            ->getQuery()
            ->getResult();

        $purified = [];
        foreach ($results as $result) {
            $purified[] = [
                'entity' => $result[0],
                'date' => new \DateTime($result['date']),
            ];
        }
        $paginator = new Paginator(new ArrayAdapter($purified));
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(100);
        return $paginator;
    }

    /**
     * {@inheritDoc}
     */
    public function getUuid(
        $key,
        $bypassIsolation = false,
        $instanceAware = true
    ) {
        $previous = $this->objectManager->getBypassIsolation();
        $this->objectManager->setBypassIsolation($bypassIsolation);

        $className = $this->getClassResolver()->resolveClassName(
            'Uuid\Entity\UuidInterface'
        );
        $entity = $this->getObjectManager()->find(
            $className,
            $key,
            $instanceAware
        );
        $this->objectManager->setBypassIsolation($previous);

        if (!is_object($entity)) {
            throw new NotFoundException(sprintf('Could not find %s', $key));
        }

        return $entity;
    }

    /**
     * {@inheritDoc}
     */
    public function purgeUuid($id)
    {
        $uuid = $this->getUuid($id);
        $class = ClassUtils::getClass($uuid);
        $permission = $this->moduleOptions->getPermission($class, 'purge');
        $this->assertGranted($permission, $uuid);
        $this->getObjectManager()->remove($uuid);
        $this->getEventManager()->trigger('purge', $this, ['object' => $uuid]);
    }

    public function clearDeadUuids()
    {
        $classes = [
            'taxonomyTerm' => TaxonomyTermInterface::class,
            'user' => UserInterface::class,
            'attachment' => ContainerInterface::class,
            'blogPost' => PostInterface::class,
            'entity' => EntityInterface::class,
            'entityRevision' => RevisionInterface::class,
            'page' => PageRepositoryInterface::class,
            'pageRevision' => PageRevisionInterface::class,
            'comment' => CommentInterface::class,
        ];
        $uuidClass = $this->getClassResolver()->resolveClassName(
            UuidInterface::class
        );

        foreach ($classes as $discriminator => $className) {
            $subClass = $this->getClassResolver()->resolveClassName($className);
            $qb = $this->objectManager->createQueryBuilder();
            $toDelete = $qb
                ->select('u.id')
                ->from($uuidClass, 'u')
                ->leftJoin($subClass, 's', 'WITH', 's = u')
                ->where($qb->expr()->isInstanceOf('u', $subClass))
                ->andWhere($qb->expr()->isNull('s.id'))
                ->getQuery()
                ->getResult();
            if (count($toDelete) > 0) {
                foreach ($toDelete as $u) {
                    $this->getEventManager()->trigger('remove', $this, [
                        'id' => $u['id'],
                    ]);
                }
                $this->objectManager
                    ->createQueryBuilder()
                    ->delete($uuidClass, 'u')
                    ->where(
                        $qb->expr()->in(
                            'u.id',
                            array_map(function ($u) {
                                return $u['id'];
                            }, $toDelete)
                        )
                    )
                    ->getQuery()
                    ->getResult();
            }
        }

        $this->clearDeadEvents();
    }

    private function clearDeadEvents()
    {
        // Delete events that have a parameter that doesn't point to a string or uuid.
        $sql =
            'DELETE FROM event_log WHERE EXISTS (' .
            '  SELECT ep.id FROM event_parameter ep' .
            '    WHERE ep.log_id = event_log.id' .
            '    AND NOT EXISTS (SELECT id FROM event_parameter_string eps WHERE eps.event_parameter_id = ep.id)' .
            '    AND NOT EXISTS (SELECT id FROM event_parameter_uuid epu WHERE epu.event_parameter_id = ep.id)' .
            ')';
        $this->getObjectManager()
            ->getConnection()
            ->prepare($sql)
            ->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function restoreUuid($id)
    {
        $uuid = $this->getUuid($id);
        $class = ClassUtils::getClass($uuid);
        $permission = $this->moduleOptions->getPermission($class, 'restore');
        $this->assertGranted($permission, $uuid);

        if (!$uuid->isTrashed()) {
            return;
        }

        $uuid->setTrashed(false);
        $this->getObjectManager()->persist($uuid);

        $this->getEventManager()->trigger('restore', $this, [
            'object' => $uuid,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function trashUuid($id)
    {
        $uuid = $this->getUuid($id);
        $class = ClassUtils::getClass($uuid);
        $permission = $this->moduleOptions->getPermission($class, 'trash');
        $this->assertGranted($permission, $uuid);

        if ($uuid->isTrashed()) {
            return;
        }

        $uuid->setTrashed(true);
        $this->getObjectManager()->persist($uuid);

        $this->getEventManager()->trigger('trash', $this, ['object' => $uuid]);
    }

    /**
     * @param int|UuidInterface $idOrObject
     * @return UuidInterface
     * @throws \Uuid\Exception\InvalidArgumentException
     */
    protected function ambiguousToUuid($idOrObject)
    {
        $uuid = null;

        if (is_int($idOrObject)) {
            $uuid = $this->getUuid($idOrObject);
        } elseif ($idOrObject instanceof UuidInterface) {
            $uuid = $idOrObject;
        } else {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'Expected int, UuidInterface or UuidInterface but got "%s"',
                    is_object($idOrObject)
                        ? get_class($idOrObject)
                        : gettype($idOrObject)
                )
            );
        }

        return $uuid;
    }
}
