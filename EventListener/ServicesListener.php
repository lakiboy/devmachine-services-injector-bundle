<?php

namespace Devmachine\Bundle\ServicesInjectorBundle\EventListener;

use Devmachine\Bundle\ServicesInjectorBundle\Request\Services;
use Devmachine\Bundle\ServicesInjectorBundle\ServiceLocator\ServiceLocator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ServicesListener implements EventSubscriberInterface
{
    private $serviceLocator;

    public function __construct(ServiceLocator $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
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
        foreach ($request->attributes->get('_services') as $service) {
            /* @var \Devmachine\Bundle\ServicesInjectorBundle\Configuration\Service $service */
            $aliases = array_merge($aliases, $service->getAliases());
        }

        $request->attributes->set('services', new Services($aliases, $this->serviceLocator));
    }
}
