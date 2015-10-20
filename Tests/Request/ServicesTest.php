<?php

namespace Devmachine\Bundle\ServicesInjectorBundle\Tests\Request;

use Devmachine\Bundle\ServicesInjectorBundle\Request\Services;
use Devmachine\Bundle\ServicesInjectorBundle\ServiceLocator\CallableServiceLocator;

class ServicesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function it_throws_exception_on_missing_service()
    {
        $services = new Services([], new CallableServiceLocator(function ($id) {}));
        $services->get('twig');
    }

    /**
     * @test
     */
    public function it_proxies_mapped_service_id()
    {
        $locator = $this->getMock('Devmachine\Bundle\ServicesInjectorBundle\ServiceLocator\ServiceLocator');
        $locator
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('router'))
        ;

        (new Services(['url_generator' => 'router'], $locator))->get('url_generator');
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Service with alias "foo" is not registered.
     */
    public function it_throws_exception_on_aliased_method_call_with_invalid_service()
    {
        $services = new Services([], new CallableServiceLocator(function ($id) {}));
        $services->getFoo();
    }

    /**
     * @test
     */
    public function it_maps_method_call_service_retrieval()
    {
        $ids = [];
        $map = ['url_generator' => 'router', 'template' => 'twig'];

        $services = new Services($map, new CallableServiceLocator(function ($id) use (&$ids) { $ids[] = $id; }));
        $services->getUrlGenerator();
        $services->getTemplate();

        $this->assertSame(['router', 'twig'], $ids);
    }
}
