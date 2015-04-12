<?php

namespace LB\FrameworkExtraBundle\Tests\ServiceLocator;

use LB\FrameworkExtraBundle\ServiceLocator\CallableServiceLocator;

class CallableServiceLocatorTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $locator = new CallableServiceLocator(function ($id) {
            return 'service_from_'.$id;
        });
        $this->assertSame('service_from_foo', $locator->get('foo'));
        $this->assertSame('service_from_bar', $locator->get('bar'));
    }
}
