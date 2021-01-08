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

namespace Entity\Form;

use Entity\Form\Element\Changes;
use Csrf\Form\Element\CsrfToken;
use Common\Form\Element\EditorState;
use Common\Form\Element\Title;
use License\Entity\LicenseInterface;
use License\Form\AgreementFieldset;
use Zend\Form\Element\Textarea;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Regex;

class AppletForm extends Form
{
    public function __construct(LicenseInterface $license)
    {
        parent::__construct('applet');
        $this->add(new CsrfToken());

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'clearfix');

        $this->add(new Title());
        $this->add(
            (new Text('url'))
                ->setAttribute('id', 'url')
                ->setLabel('Applet Url:')
        );
        $this->add((new EditorState('content'))->setLabel('Description:'));
        $this->add((new EditorState('reasoning'))->setLabel('Reasoning:'));
        $this->add(new Changes());
        $this->add(new Element\MetaTitle());
        $this->add(new Element\MetaDescription());
        $this->add(new AgreementFieldset($license));
        $this->add(new Controls());

        $inputFilter = new InputFilter('applet');
        $inputFilter->add([
            'name' => 'url',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags',
                ],
            ],
            'validators' => [
                [
                    'name' => 'Regex',
                    'options' => [
                        'pattern' =>
                            '/^(https:\/\/www\.geogebra\.org\/m\/)?[a-zA-Z0-9]+$/',
                        'messages' => [
                            Regex::NOT_MATCH =>
                                'Applet-URL invalid. Use the form https://www.geogebra.org/m/<id>',
                        ],
                    ],
                ],
            ],
        ]);
        $inputFilter->add(['name' => 'content', 'required' => true]);
        $this->setInputFilter($inputFilter);
    }
}
