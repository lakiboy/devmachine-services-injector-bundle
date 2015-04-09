<?php

namespace LB\FrameworkExtraBundle\EventListener;

use LB\FrameworkExtraBundle\Request\Services;
use LB\FrameworkExtraBundle\ServiceLocator\SymfonyServiceLocator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ServiceListener implements EventSubscriberInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $request = $event->getRequest();

        if (!$configuration = $request->attributes->get('_services')) {
            return;
        }

        $aliases = [];
        foreach ((array) $request->attributes->get('_services') as $service) {
            /** @var \LB\FrameworkExtraBundle\Configuration\Service $service */
            $aliases = array_merge($aliases, $service->getAliases());
        }

        $locator = new SymfonyServiceLocator($this->container);
        $request->attributes->set('services', new Services($aliases, $locator));
    }
}
