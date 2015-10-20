<?php

namespace Devmachine\Bundle\ServicesInjectorBundle\Tests\Functional;

use Devmachine\Bundle\ServicesInjectorBundle\DevmachineServicesInjectorBundle;
use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    public function __construct()
    {
        parent::__construct('test', true);

        $this->tempDir = __DIR__.'/temp';
    }

    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new SensioFrameworkExtraBundle(),
            new DevmachineServicesInjectorBundle(),
        ];
    }

    public function getCacheDir()
    {
        return $this->tempDir.'/cache';
    }

    public function getLogDir()
    {
        return $this->tempDir.'/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config.yml');
    }
}
