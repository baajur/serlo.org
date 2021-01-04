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
namespace Instance\Assertion;

use Authorization\Service\AuthorizationService as StatefulAuthorizationService;
use Authorization\Service\PermissionServiceAwareTrait;
use Instance\Entity\InstanceAwareInterface;
use Instance\Exception\InvalidArgumentException;
use ZfcRbac\Assertion\AssertionInterface;
use ZfcRbac\Service\AuthorizationService;

class InstanceAssertion implements AssertionInterface
{
    use PermissionServiceAwareTrait;

    /**
     * Check if this assertion is true
     *
     * @param  AuthorizationService $authorization
     * @param  mixed                $context
     * @throws InvalidArgumentException
     * @return bool
     */
    public function assert(AuthorizationService $authorization, $context = null)
    {
        if (!$context instanceof InstanceAwareInterface) {
            throw new InvalidArgumentException();
        }
        if (!$authorization instanceof StatefulAuthorizationService) {
            throw new InvalidArgumentException();
        }
        $result = $authorization->getAuthorizationResult();
        $permission = $result->getPermission();
        $permissionToMatch = $this->getPermissionService()->findParametrizedPermission(
            (string) $permission,
            'instance',
            $context->getInstance()->getId()
        );

        foreach ($result->getRoles() as $role) {
            if ($role->hasPermission($permissionToMatch->getId())) {
                return true;
            }
        }

        return false;
    }
}
