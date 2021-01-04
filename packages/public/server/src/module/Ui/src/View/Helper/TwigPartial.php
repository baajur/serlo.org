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
namespace Ui\View\Helper;

use Zend\View\Exception;
use Zend\View\Helper\Partial;
use Zend\View\Model\ModelInterface;
use ZfcTwig\View\TwigRenderer;

class TwigPartial extends Partial
{
    /**
     * @var TwigRenderer
     */
    protected $twigRenderer;

    /**
     * @param TwigRenderer $twigRenderer
     */
    public function __construct(TwigRenderer $twigRenderer)
    {
        $this->twigRenderer = $twigRenderer;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke($name = null, $values = null)
    {
        if (0 == func_num_args()) {
            return $this;
        }

        // If we were passed only a view model, just render it.
        if ($name instanceof ModelInterface) {
            return $this->getView()->render($name);
        }

        if (is_scalar($values)) {
            $values = [];
        } elseif ($values instanceof ModelInterface) {
            $values = $values->getVariables();
        } elseif (is_object($values)) {
            if (null !== ($objectKey = $this->getObjectKey())) {
                $values = [$objectKey => $values];
            } elseif (method_exists($values, 'toArray')) {
                $values = $values->toArray();
            } else {
                $values = get_object_vars($values);
            }
        }

        return $this->twigRenderer->render($name, $values);
    }
}
