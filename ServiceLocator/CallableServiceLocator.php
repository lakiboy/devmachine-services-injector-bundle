<?php

namespace Devmachine\Bundle\ServicesInjectorBundle\ServiceLocator;

class CallableServiceLocator implements ServiceLocator
{
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function get($id)
    {
        return call_user_func($this->callable, $id);
    }
}
