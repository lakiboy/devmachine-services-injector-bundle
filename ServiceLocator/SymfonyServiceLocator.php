<?php

namespace Devmachine\Bundle\ServicesInjectorBundle\ServiceLocator;

use Symfony\Component\DependencyInjection\ContainerInterface;

class SymfonyServiceLocator extends CallableServiceLocator
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct([$container, 'get']);
    }
}
