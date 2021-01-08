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
namespace Instance\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="instance")
 */
class Instance implements InstanceInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=2)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $subdomain;

    /**
     * @ORM\ManyToOne(targetEntity="Instance\Entity\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * @var LanguageInterface
     */
    protected $language;

    public function __toString()
    {
        return $this->getName();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($code)
    {
        $this->name = $code;
    }

    public function getSubdomain()
    {
        return $this->subdomain;
    }

    public function setSubdomain($subdomain)
    {
        $this->subdomain = $subdomain;
    }
}
