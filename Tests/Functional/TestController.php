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
        $services->get('ff');
        $services->getUrlGenerator();
        $services->getTranslator();

        return Response::create();
    }
}
