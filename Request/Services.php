<?php

namespace Devmachine\Bundle\ServicesInjectorBundle\Request;

use Devmachine\Bundle\ServicesInjectorBundle\ServiceLocator\ServiceLocator;

final class Services
{
    private $aliases = [];
    private $locator;

    /**
     * @param array          $aliases
     * @param ServiceLocator $locator
     */
    public function __construct(array $aliases, ServiceLocator $locator)
    {
        $this->aliases = $aliases;
        $this->locator = $locator;
    }

    /**
     * @param string $alias
     *
     * @return object
     *
     * @throws \InvalidArgumentException
     */
    public function get($alias)
    {
        if (!isset($this->aliases[$alias])) {
            throw new \InvalidArgumentException(sprintf('Service with alias "%s" is not registered.', $alias));
        }

        $serviceId = $this->aliases[$alias];

        return $this->locator->get($serviceId);
    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return object
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, array $args)
    {
        if (substr($method, 0, 3) !== 'get') {
            throw new \BadMethodCallException(sprintf('Invalid method call %s::%s', get_class($this), $method));
        }

        // https://github.com/doctrine/inflector/blob/master/lib/Doctrine/Common/Inflector/Inflector.php
        $alias = strtolower(preg_replace('~(?<=\\w)([A-Z])~', '_$1', substr($method, 3)));

        return $this->get($alias);
    }
}
