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
use License\Hydrator\LicenseHydrator;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Element\Url;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class LicenseForm extends Form
{
    public function __construct()
    {
        parent::__construct('license');
        $this->add(new CsrfToken());

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'clearfix');
        $this->setHydrator(new LicenseHydrator());
        $inputFilter = new InputFilter('license');
        $this->setInputFilter($inputFilter);

        $this->add(
            (new Text('title'))->setLabel('Title:')->setAttribute('id', 'title')
        );
        $this->add(
            (new Textarea('content'))
                ->setLabel('Content:')
                ->setAttribute('id', 'content')
        );
        $this->add(
            (new Url('url'))
                ->setLabel('License url:')
                ->setAttribute('id', 'url')
        );
        $this->add(
            (new Textarea('agreement'))
                ->setLabel('Agreement:')
                ->setAttribute('id', 'agreement')
                ->setAttribute('class', 'plain')
        );
        $this->add(
            (new Url('iconHref'))
                ->setLabel('Icon url:')
                ->setAttribute('id', 'iconHref')
        );
        $this->add(
            (new Checkbox('default'))
                ->setLabel('This is the default license for this instance.')
                ->setAttribute('id', 'default')
        );

        $this->add(
            (new Submit('submit'))
                ->setValue('Save')
                ->setAttribute('class', 'btn btn-success pull-right')
        );

        $inputFilter->add([
            'name' => 'title',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags',
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'content',
            'required' => false,
            'filters' => [
                [
                    'name' => 'StripTags',
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'iconHref',
            'required' => false,
            'filters' => [
                [
                    'name' => 'StripTags',
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'url',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags',
                ],
            ],
        ]);
    }
}
