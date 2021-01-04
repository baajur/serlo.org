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

use License\Entity\LicenseInterface;
use Zend\Form\Element\Checkbox;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class AgreementFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(LicenseInterface $license)
    {
        parent::__construct('license');
        $agreement = $license->getAgreement() ?: $license->getTitle();
        $checkbox = new Checkbox('agreement');
        $checkbox->setOptions([
            'use_hidden_element' => false,
        ]);
        $checkbox->setLabel($agreement);
        $checkbox->setLabelOptions(['disable_html_escape' => true]);
        $checkbox->setAttribute('class', 'control');
        $this->add($checkbox);
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            [
                'name' => 'agreement',
                'required' => true,
            ],
        ];
    }
}
