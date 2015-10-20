<?php

namespace Devmachine\Bundle\ServicesInjectorBundle\Tests\Functional;

use Devmachine\Bundle\ServicesInjectorBundle\Configuration\Service;
use Devmachine\Bundle\ServicesInjectorBundle\Request\Services;
use Symfony\Component\HttpFoundation\Response;

/**
 * Inject to all actions.
 *
 * @Service(twig="twig")
 */
class TestController
{
    /**
     * @Service("translator")
     *
     * @param Services $services
     *
     * @return Response
     */
    public function firstAction(Services $services)
    {
        $services->getTwig();
        $services->getTranslator();

        return Response::create();
    }

    /**
     * @Service({"ff"="form.factory", "url_generator"="router", "translator"})
     *
     * @param Services $services
     *
     * @return Response
     */
    public function secondAction(Services $services)
    {
        $services->get('twig');
        $services->get('ff');
        $services->getUrlGenerator();
        $services->getTranslator();

        return Response::create();
    }

    public function thirdAction(Services $services)
    {
        $services->get('translator');
    }
}
