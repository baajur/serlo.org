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
namespace Uuid\Controller;

use Csrf\Form\CsrfForm;
use Uuid\Manager\UuidManagerAwareTrait;
use Instance\Manager\InstanceManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\Form;

class UuidController extends AbstractActionController
{
    use UuidManagerAwareTrait, InstanceManagerAwareTrait;

    public function recycleBinAction()
    {
        $page = $this->params()->fromQuery('page', 1);
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $elements = $this->getUuidManager()->findTrashed($page, $instance);
        $view = new ViewModel(['elements' => $elements]);
        $view->setTemplate('uuid/recycle-bin');
        return $view;
    }

    public function trashAction()
    {
        /** @var Form $form */
        $form = new CsrfForm('uuid-trash');
        $form->setData($this->getRequest()->getPost());

        if ($form->isValid()) {
            $this->getUuidManager()->trashUuid($this->params('id'));
            $this->getUuidManager()->flush();
            $this->flashMessenger()->addSuccessMessage(
                'The content has been trashed.'
            );
        } else {
            $this->flashMessenger()->addErrorMessage(
                'The content could not be trashed (validation failed)'
            );
        }

        return $this->redirect()->toReferer();
    }

    public function restoreAction()
    {
        $this->getUuidManager()->restoreUuid($this->params('id'));
        $this->getUuidManager()->flush();
        $this->flashMessenger()->addSuccessMessage(
            'The content has been restored.'
        );
        return $this->redirect()->toReferer();
    }

    public function purgeAction()
    {
        /** @var Form $form */
        $form = new CsrfForm('uuid-purge');
        $form->setData($this->getRequest()->getPost());

        if ($form->isValid()) {
            $this->getUuidManager()->purgeUuid($this->params('id'));
            $this->getUuidManager()->flush();
            // cascading deletes might result in dead uuids, so we clear them here
            $this->getUuidManager()->clearDeadUuids();
            $this->flashMessenger()->addSuccessMessage(
                'The content has been removed.'
            );
        } else {
            $this->flashMessenger()->addErrorMessage(
                'The content could not be removed (validation failed)'
            );
        }

        return $this->redirect()->toReferer();
    }
}
