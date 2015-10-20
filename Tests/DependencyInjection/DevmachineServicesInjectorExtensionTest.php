<?php

namespace Devmachine\Bundle\ServicesInjectorBundle\Tests\DependencyInjection;

use Devmachine\Bundle\ServicesInjectorBundle\DependencyInjection\DevmachineServicesInjectorExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class DevmachineServicesInjectorExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     */
    public function it_registers_services()
    {
        $this->load();

        $this->assertContainerBuilderHasService('devmachine_services_injector.controller.listener.service');
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'devmachine_services_injector.controller.listener.service',
            0,
            'devmachine_services_injector.service_locator'
        );
        $this->assertContainerBuilderHasAlias(
            'devmachine_services_injector.service_locator',
            'devmachine_services_injector.service_locator.container'
        );
    }

    protected function getContainerExtensions()
    {
        return [new DevmachineServicesInjectorExtension()];
    }
}
