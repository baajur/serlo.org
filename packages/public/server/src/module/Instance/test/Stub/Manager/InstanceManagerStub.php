<?php

namespace InstanceTest\Stub\Manager;

use Instance\Entity\InstanceInterface;
use Instance\Exception\InstanceNotFoundException;
use Instance\Manager\InstanceManagerInterface;
use InstanceTest\Fixture\Entity\EnglishInstance;
use InstanceTest\Fixture\Entity\GermanInstance;

class InstanceManagerStub implements InstanceManagerInterface
{
    /** @var InstanceInterface */
    public $germanInstance;
    /** @var InstanceInterface */
    public $englishInstance;
    /** @var InstanceInterface */
    protected $instance;

    public function __construct()
    {
        $this->germanInstance = new GermanInstance();
        $this->englishInstance = new EnglishInstance();
        $this->instance = $this->germanInstance;
    }

    public function useGermanInstance()
    {
        $this->instance = $this->germanInstance;
    }

    public function useEnglishInstance()
    {
        $this->instance = $this->englishInstance;
    }

    public function findInstanceByName($name)
    {
        $result = array_values(array_filter($this->findAllInstances(), function ($instance) use ($name) {
            return $instance->getName() === $name;
        }));
        if (count($result) === 0) {
            throw new InstanceNotFoundException(sprintf('Instance %s could not be found', $name));
        }
        return $result[0];
    }

    public function findAllInstances()
    {
        return [
            $this->germanInstance,
            $this->englishInstance,
        ];
    }

    public function findInstanceBySubDomain($subDomain)
    {
        $result = array_filter(array_filter($this->findAllInstances(), function ($instance) use ($subDomain) {
            return $instance->getSubdomain() === $subDomain;
        }));
        if (count($result) === 0) {
            throw new InstanceNotFoundException(sprintf('Instance %s could not be found', $subDomain));
        }
        return $result[0];
    }

    public function getDefaultInstance()
    {
        return $this->germanInstance;
    }

    public function getInstanceFromRequest()
    {
        return $this->instance;
    }

    public function switchInstance($id)
    {
        $this->instance = $this->getInstance($id);
    }

    public function getInstance($id)
    {
        $result = array_filter(array_filter($this->findAllInstances(), function ($instance) use ($id) {
            return $instance->getId() === $id;
        }));
        if (count($result) === 0) {
            throw new InstanceNotFoundException(sprintf('Instance %s could not be found', $id));
        }
        return $result[0];
    }
}