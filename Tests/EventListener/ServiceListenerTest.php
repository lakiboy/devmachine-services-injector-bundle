<?php

namespace LB\FrameworkExtraBundle\Tests\EventListener;

use Doctrine\Common\Annotations\AnnotationReader;
use LB\FrameworkExtraBundle\Configuration\Service;
use LB\FrameworkExtraBundle\EventListener\ServiceListener;
use Symfony\Component\HttpFoundation\Request;

class ServiceListenerTest extends \PHPUnit_Framework_TestCase
{
    /** @var AnnotationReader */
    private $reader;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $container;

    /** @var ServiceListener */
    private $listener;

    public function setUp()
    {
        $this->reader = new AnnotationReader();
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->listener = new ServiceListener($this->container);
    }

    public function testOnKernelControllerWithoutServices()
    {
        $request = new Request();

        $this->listener->onKernelController($this->getFilterEvent($request));

        $this->assertFalse($request->attributes->has('services'));
    }

    /**
     * @Service("twig")
     * @Service("translator")
     * @Service({"url_generator"="router", "foo"="bar"})
     */
    public function testOnKernelController()
    {
        $method = new \ReflectionMethod($this, __FUNCTION__);
        $request = new Request();
        $request->attributes->set('_services', $this->reader->getMethodAnnotations($method));

        $this->listener->onKernelController($this->getFilterEvent($request));

        $this->assertTrue($request->attributes->has('services'));
        $this->assertInstanceOf('LB\FrameworkExtraBundle\Request\Services', $services = $request->attributes->get('services'));

        $this->container
            ->expects($this->exactly(4))
            ->method('get')
            ->withConsecutive(
                $this->equalTo('twig'),
                $this->equalTo('translator'),
                $this->equalTo('router'),
                $this->equalTo('bar')
            )
        ;

        /** @var \LB\FrameworkExtraBundle\Request\Services $services */
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
            ->will($this->returnValue($request))
        ;
        $event
            ->expects($this->once())
            ->method('getController')
            ->will($this->returnValue([]))
        ;

        return $event;
    }
}
