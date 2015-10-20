<?php

namespace Devmachine\Bundle\ServicesInjectorBundle\Tests\Configuration;

use Devmachine\Bundle\ServicesInjectorBundle\Configuration\Service;
use Doctrine\Common\Annotations\AnnotationReader;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var AnnotationReader */
    private $reader;

    public function setUp()
    {
        $this->reader = new AnnotationReader();
        $this->reader->addGlobalIgnoredName('test');
    }

    /**
     * @test
     */
    public function it_has_valid_alias()
    {
        $this->assertSame('services', (new Service([]))->getAliasName());
    }

    /**
     * @test
     */
    public function it_allows_multiple_definitions()
    {
        $this->assertTrue((new Service([]))->allowArray());
    }

    /**
     * @test
     *
     * @Service("twig")
     */
    public function it_maps_service_with_same_id()
    {
        $this->assertSame(['twig' => 'twig'], $this->readAnnotation(__FUNCTION__)->getAliases());
    }

    /**
     * @test
     *
     * @Service(url_generator="router", translator="translator")
     */
    public function it_maps_services_with_custom_keys()
    {
        $this->assertSame([
            'url_generator' => 'router',
            'translator'    => 'translator',
        ], $this->readAnnotation(__FUNCTION__)->getAliases());
    }

    /**
     * @test
     *
     * @Service({"twig", "url_generator"="router", "translator"})
     */
    public function it_maps_services_with_variable_keys()
    {
        $this->assertSame([
            'twig'          => 'twig',
            'url_generator' => 'router',
            'translator'    => 'translator',
        ], $this->readAnnotation(__FUNCTION__)->getAliases());
    }

    /**
     * @param string $methodName
     *
     * @return \Devmachine\Bundle\ServicesInjectorBundle\Configuration\Service
     */
    private function readAnnotation($methodName)
    {
        $rm = new \ReflectionMethod($this, $methodName);

        return $this->reader->getMethodAnnotation($rm, 'Devmachine\Bundle\ServicesInjectorBundle\Configuration\Service');
    }
}
