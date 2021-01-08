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
namespace License\Form;

use Csrf\Form\Element\CsrfToken;
use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class UpdateLicenseForm extends Form
{
    public function __construct(array $licenses)
    {
        parent::__construct('context');
        $this->add(new CsrfToken());

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'clearfix');
        $inputFilter = new InputFilter('context');
        $this->setInputFilter($inputFilter);

        $values = [];
        foreach ($licenses as $license) {
            $values[$license->getId()] = $license->getTitle();
        }

        $this->add(
            (new Select('license'))
                ->setLabel('Select a license:')
                ->setValueOptions($values)
        );

        $this->add(
            (new Submit('submit'))
                ->setValue('Update')
                ->setAttribute('class', 'btn btn-success pull-right')
        );
    }
}
