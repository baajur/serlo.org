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

namespace Admin\Controller;

use Admin\Form\DebuggerForm;
use Authorization\Permission;
use Authorization\Service\AssertGrantedServiceInterface;
use Ui\View\Helper\Encrypt;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class DebuggerController extends AbstractActionController
{
    /** @var AssertGrantedServiceInterface */
    protected $assertGrantedService;

    public function __construct(
        AssertGrantedServiceInterface $assertGrantedService
    ) {
        $this->assertGrantedService = $assertGrantedService;
    }

    public function indexAction()
    {
        $this->assertGrantedService->assert(Permission::ADMIN_DEBUGGER_USE);

        $form = new DebuggerForm();
        $message = false;

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $helper = new Encrypt();
                $message = $helper->decrypt($form->get('message')->getValue());
            }
        }

        $view = new ViewModel(['form' => $form, 'message' => $message]);
        $view->setTemplate('admin/debugger');
        return $view;
    }
}
