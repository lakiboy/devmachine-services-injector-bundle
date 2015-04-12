<?php

namespace LB\FrameworkExtraBundle\Tests\Configuration;

use Doctrine\Common\Annotations\AnnotationReader;
use LB\FrameworkExtraBundle\Configuration\Service;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var AnnotationReader */
    private $reader;

    public function setUp()
    {
        $this->reader = new AnnotationReader();
    }

    public function testGetAliasName()
    {
        $this->assertSame('services', (new Service([]))->getAliasName());
    }

    public function testAllowArray()
    {
        $this->assertTrue((new Service([]))->allowArray());
    }

    /**
     * @Service("twig")
     */
    public function testSingleConfiguration()
    {
        /** @var Service $config */
        $service = $this->reader->getMethodAnnotation(new \ReflectionMethod($this, __FUNCTION__), 'LB\FrameworkExtraBundle\Configuration\Service');
        $this->assertSame(['twig' => 'twig'], $service->getAliases());
    }

    /**
     * @Service(url_generator="router", translator="translator")
     */
    public function testMultipleConfiguration()
    {
        /** @var Service $config */
        $service = $this->reader->getMethodAnnotation(new \ReflectionMethod($this, __FUNCTION__), 'LB\FrameworkExtraBundle\Configuration\Service');
        $this->assertSame([
            'url_generator' => 'router',
            'translator' => 'translator',
        ], $service->getAliases());
    }

    /**
     * @Service({"twig", "url_generator"="router", "translator"})
     */
    public function testArrayConfiguration()
    {
        /** @var Service $config */
        $service = $this->reader->getMethodAnnotation(new \ReflectionMethod($this, __FUNCTION__), 'LB\FrameworkExtraBundle\Configuration\Service');
        $this->assertSame([
            'twig' => 'twig',
            'url_generator' => 'router',
            'translator' => 'translator',
        ], $service->getAliases());
    }
}
