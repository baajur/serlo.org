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
namespace Common\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class AbstractAPIAwareActionController extends AbstractActionController
{
    protected function setupAPI()
    {
        $fullWidth = $this->setupAPIQueryParam('fullWidth');
        $hideTopbar = $this->setupAPIQueryParam('hideTopbar');
        $hideLeftSidebar = $this->setupAPIQueryParam('hideLeftSidebar');
        $hideRightSidebar = $this->setupAPIQueryParam('hideRightSidebar');
        $hideBreadcrumbs = $this->setupAPIQueryParam('hideBreadcrumbs');
        $hideDiscussions = $this->setupAPIQueryParam('hideDiscussions');
        $hideBanner = $this->setupAPIQueryParam('hideBanner');
        $hideHorizon = $this->setupAPIQueryParam('hideHorizon');
        $hideFooter = $this->setupAPIQueryParam('hideFooter');
        $contentOnly = $this->setupAPIQueryParam('contentOnly');

        $this->layout()->fullWidth = $fullWidth || $contentOnly;
        $this->layout()->hideTopbar = $hideTopbar || $contentOnly;
        $this->layout()->hideLeftSidebar = $hideLeftSidebar || $contentOnly;
        $this->layout()->hideRightSidebar = $hideRightSidebar || $contentOnly;
        $this->layout()->hideBreadcrumbs = $hideBreadcrumbs;
        $this->layout()->hideDiscussions = $hideDiscussions || $contentOnly;
        $this->layout()->hideBanner = $hideBanner || $contentOnly;
        $this->layout()->hideHorizon = $hideHorizon || $contentOnly;
        $this->layout()->hideFooter = $hideFooter || $contentOnly;

        $this->layout()->usingAPI =
            $fullWidth ||
            $hideTopbar ||
            $hideLeftSidebar ||
            $hideRightSidebar ||
            $hideBreadcrumbs ||
            $hideDiscussions ||
            $hideBanner ||
            $hideHorizon ||
            $hideFooter ||
            $contentOnly;
    }

    private function setupAPIQueryParam(string $key)
    {
        return is_string($this->params()->fromQuery($key));
    }
}
