<?php

namespace Devmachine\Bundle\ServicesInjectorBundle\Tests\ServiceLocator;

use Devmachine\Bundle\ServicesInjectorBundle\ServiceLocator\CallableServiceLocator;

class CallableServiceLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_works_with_valid_callable()
    {
        $locator = new CallableServiceLocator(function ($id) {
            return 'service_from_'.$id;
        });
        $this->assertSame('service_from_foo', $locator->get('foo'));
        $this->assertSame('service_from_bar', $locator->get('bar'));
    }
}
