<?php

namespace Devmachine\Bundle\ServicesInjectorBundle\Tests\EventListener;

use Devmachine\Bundle\ServicesInjectorBundle\Configuration\Service;
use Devmachine\Bundle\ServicesInjectorBundle\EventListener\ServicesListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;

class ServicesListenerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $serviceLocator;

    /** @var EventDispatcher */
    private $dispatcher;

    public function setUp()
    {
        $this->serviceLocator = $this->getMock('Devmachine\Bundle\ServicesInjectorBundle\ServiceLocator\ServiceLocator');
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber(new ServicesListener($this->serviceLocator));
    }

    /**
     * @test
     */
    public function it_should_not_register_services_attribute()
    {
        $event = $this->getFilterEvent($request = new Request());

        $this->dispatcher->dispatch(KernelEvents::CONTROLLER, $event);

        $this->assertFalse($request->attributes->has('services'));
    }

    /**
     * @test
     */
    public function it_registers_services_attribute()
    {
        $services = new Service(['twig', 'translator', 'url_generator' => 'router', 'foo' => 'bar']);

        $event = $this->getFilterEvent($request = new Request([], [], ['_services' => [$services]]));

        $this->dispatcher->dispatch(KernelEvents::CONTROLLER, $event);

        $this->assertTrue($request->attributes->has('services'));
        $this->assertInstanceOf(
            'Devmachine\Bundle\ServicesInjectorBundle\Request\Services',
            $services = $request->attributes->get('services')
        );

        $this->serviceLocator
            ->expects($this->exactly(4))
            ->method('get')
            ->withConsecutive(
                [$this->equalTo('twig')],
                [$this->equalTo('translator')],
                [$this->equalTo('router')],
                [$this->equalTo('bar')]
            )
        ;

        $services->get('twig');
        $services->get('translator');
        $services->get('url_generator');
        $services->get('foo');
    }

    private function getFilterEvent(Request $request)
    {
        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\FilterControllerEvent')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $event
            ->expects($this->once())
            ->method('getRequest')
            ->willReturn($request)
        ;
        $event
            ->expects($this->once())
            ->method('getController')
            ->willReturn([])
        ;

        return $event;
    }
}
