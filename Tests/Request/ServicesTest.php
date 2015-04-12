<?php

namespace LB\FrameworkExtraBundle\Tests\Request;

use LB\FrameworkExtraBundle\Request\Services;
use LB\FrameworkExtraBundle\ServiceLocator\CallableServiceLocator;

class ServicesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetMissingAlias()
    {
        $services = new Services([], new CallableServiceLocator(function ($id) {}));
        $services->get('twig');
    }

    public function testGet()
    {
        $locator = $this->getMockBuilder('LB\FrameworkExtraBundle\ServiceLocator\ServiceLocator')->getMock();
        $services = new Services(['url_generator' => 'router'], $locator);

        $locator
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('router'))
        ;
        $services->get('url_generator');
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testNonGetterCall()
    {
        $services = new Services([], new CallableServiceLocator(function ($id) {}));
        $services->setFoo();
    }

    public function testCall()
    {
        $ids = [];
        $map = ['url_generator' => 'router', 'template' => 'twig'];

        $services = new Services($map, new CallableServiceLocator(function ($id) use (&$ids) { $ids[] = $id; }));
        $services->getUrlGenerator();
        $services->getTemplate();

        $this->assertSame(['router', 'twig'], $ids);
    }
}
