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
namespace Discussion\Controller;

use Instance\Manager\InstanceManagerAwareTrait;
use Taxonomy\Exception\TermNotFoundException;
use Taxonomy\Manager\TaxonomyManagerAwareTrait;
use User\Manager\UserManagerAwareTrait;
use Zend\View\Model\ViewModel;

class DiscussionsController extends AbstractController
{
    use TaxonomyManagerAwareTrait,
        InstanceManagerAwareTrait,
        UserManagerAwareTrait;

    public function indexAction()
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $page = $this->params()->fromQuery('page', 1);
        $discussions = $this->getDiscussionManager()->findDiscussionsByInstance(
            $instance,
            $page
        );

        $view = new ViewModel([
            'discussions' => $discussions,
            'user' => $this->getUserManager()->getUserFromAuthenticator(),
        ]);

        $view->setTemplate('discussion/discussions/index');
        return $view;
    }

    public function redirectAction()
    {
        $r = $this->redirect()->toRoute('discussion/discussions');
        $this->getResponse()->setStatusCode(301);
        return $r;
    }
}
