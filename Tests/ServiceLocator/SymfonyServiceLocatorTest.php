<?php

namespace Devmachine\Bundle\ServicesInjectorBundle\Tests\ServiceLocator;

use Devmachine\Bundle\ServicesInjectorBundle\ServiceLocator\SymfonyServiceLocator;

class SymfonyServiceLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_proxies_to_container()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container
            ->expects($this->exactly(2))
            ->method('get')
            ->withConsecutive(
                [$this->equalTo('foo')],
                [$this->equalTo('bar')]
            )
        ;
        $locator = new SymfonyServiceLocator($container);
        $locator->get('foo');
        $locator->get('bar');
    }
}
