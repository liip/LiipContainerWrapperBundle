<?php

namespace Liip\ContainerWrapperBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ScopeInterface;

/**
 * This is a wrapper around a Container instance to provide instances for specific services only
 * 
 * @author Lukas Kahwe Smith <smith@pooteeweet.org>
 * @license MIT
 */
class ContainerWrapper extends ContainerAware implements ContainerInterface
{
    protected $serviceIds;
    protected $container;

    /**
     * Constructor.
     *
     * @param array $serviceIds A list of service ids
     * @param array $parameterNames A list of parameter name
     * @param array $defaultServiceIds A second list of service ids, usually a list of default services
     * @param array $defaultServiceIds A second list of parameter names, usually a list of default parameters
     */
    public function __construct(array $serviceIds, array $parameterNames = array(), array $defaultServiceIds = array(), array $defaultParameterNames = array())
    {
        foreach ($serviceIds as $serviceId => $mappedServiceId) {
            if ($mappedServiceId === true) {
                $serviceIds[$serviceId] = $serviceId;
            }
        }

        foreach ($defaultServiceIds as $serviceId => $mappedServiceId) {
            if (empty($serviceIds[$serviceId])) {
                $serviceIds[$serviceId] = $mappedServiceId === true ? $serviceId : $mappedServiceId;
            }
        }

        foreach ($parameterNames as $name => $mappedName) {
            if ($mappedName === true) {
                $parameterNames[$name] = $name;
            }
        }

        foreach ($defaultParameterNames as $name => $mappedName) {
            if (empty($parameterNames[$name])) {
                $parameterNames[$name] = $mappedName === true ? $name : $mappedName;
            }
        }

        $this->parameterNames = $parameterNames;
        $this->serviceIds = $serviceIds;
    }

    /**
     * @inheritDoc
     */
    public function getParameterBag()
    {
        throw new \LogicException('getParameterBag() is not supported by the service container wrapper.');
    }

    /**
     * @inheritDoc
     */
    public function getParameter($name)
    {
        if (!array_key_exists($name, $this->parameterNames)) {
            throw new \InvalidArgumentException(sprintf('The parameter "%s" must be defined.', $name));
        }

        return $this->container->getParameter($this->parameterNames[$name]);
    }

    /**
     * @inheritDoc
     */
    public function hasParameter($name)
    {
        if (!array_key_exists($name, $this->parameterNames)) {
            return false;
        }

        return $this->container->hasParameter($this->parameterNames[$name]);
    }

    /**
     * @inheritDoc
     */
    public function setParameter($name, $value)
    {
        if (!array_key_exists($name, $this->parameterNames)) {
            $msg = "Parameter '%s' not supported. The following parameters are supported: %s";
            throw new \InvalidArgumentException(sprintf($msg, $name, implode(', ', $this->parameterNames)));
        }

        return $this->container->getParameter($this->parameterNames[$name], $value);
    }

    /**
     * @inheritDoc
     */
    public function set($id, $service, $scope = self::SCOPE_CONTAINER)
    {
        if (self::SCOPE_PROTOTYPE === $scope) {
            throw new \InvalidArgumentException('You cannot set services of scope "prototype".');
        }

        $id = strtolower($id);

        if (!array_key_exists($id, $this->serviceIds)) {
            $msg = "Service '%s' not supported. The following services are supported: %s";
            throw new \InvalidArgumentException(sprintf($msg, $id, implode(', ', $this->serviceIds)));
        }

        return $this->container->set($this->serviceIds[$id], $service, $scope);
    }

    /**
     * @inheritDoc
     */
    public function has($id)
    {
        if (!array_key_exists($id, $this->serviceIds)) {
            return false;
        }

        return $this->container->has($this->serviceIds[$id]);
    }

    /**
     * @inheritDoc
     */
    public function get($id, $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE)
    {
        if (!array_key_exists($id, $this->serviceIds)) {
            if ($invalidBehavior) {
                $msg = "Service '%s' not supported. The following services are supported: %s";
                throw new \InvalidArgumentException(sprintf($msg, $id, implode(', ', $this->serviceIds)));
            }

            return;
        }

        return $this->container->get($this->serviceIds[$id], $invalidBehavior);
    }

    /**
     * @inheritDoc
     */
    public function getServiceIds()
    {
        return $this->serviceIds;
    }

    /**
     * @inheritDoc
     */
    public function enterScope($name)
    {
        return $this->container->enterScope($name);
    }

    /**
     * @inheritDoc
     */
    public function leaveScope($name)
    {
        return $this->container->leaveScope($name);
    }

    /**
     * @inheritDoc
     */
    public function addScope(ScopeInterface $scope)
    {
        return $this->container->addScope($scope);
    }

    /**
     * @inheritDoc
     */
    public function hasScope($name)
    {
        return $this->container->hasScope($name);
    }

    /**
     * @inheritDoc
     */
    public function isScopeActive($name)
    {
        return $this->container->isScopeActive($name);
    }
}
