<?php

namespace Devmachine\Bundle\ServicesInjectorBundle\Tests\Functional;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group functional
 */
class DevmachineServicesInjectorBundleTest extends KernelTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serviceLocator;

    public static function setUpBeforeClass()
    {
        AnnotationRegistry::registerLoader('class_exists');
    }

    public function setUp()
    {
        static::bootKernel();

        $this->serviceLocator = $this->getMock('Devmachine\Bundle\ServicesInjectorBundle\ServiceLocator\ServiceLocator');

        static::$kernel->getContainer()->set(
            'devmachine_services_injector.service_locator.container',
            $this->serviceLocator
        );
    }

    /**
     * @test
     */
    public function it_injects_locator_into_first_action()
    {
        $this->serviceLocator
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('translator'))
        ;

        static::$kernel->handle($this->createRequest('firstAction'));
    }

    /**
     * @test
     */
    public function it_injects_locator_into_second_action()
    {
        $this->serviceLocator
            ->expects($this->exactly(3))
            ->method('get')
            ->withConsecutive(
                [$this->equalTo('form.factory')],
                [$this->equalTo('router')],
                [$this->equalTo('translator')]
            )
        ;

        static::$kernel->handle($this->createRequest('secondAction'));
    }

    protected static function getKernelClass()
    {
        return __NAMESPACE__.'\\TestKernel';
    }

    /**
     * @param string $methodName
     *
     * @return Request
     */
    private function createRequest($methodName)
    {
        return new Request([], [], ['_controller' => [new TestController(), $methodName]]);
    }
}
