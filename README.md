# DevmachineServicesInjectorBundle

[![Build Status](https://travis-ci.org/dev-machine/DevmachineServicesInjectorBundle.svg?branch=master)](https://travis-ci.org/dev-machine/DevmachineServicesInjectorBundle)

Inject services into controllers using annotations on top of [SensioFrameworkExtraBundle](https://github.com/sensiolabs/SensioFrameworkExtraBundle).

## Preambule

There are many ways how to retrieve services in controllers. The easiest one is to extend default container aware `Symfony\Bundle\FrameworkBundle\Controller\Controller` controller and use `get('<service-id>')` method to get a service. This approach is not favoured by purists as injecting a container considers a bad practice (as they say). It is recommended to inject controller dependencies instead in same way you do with other services.

However, this could lead to far too many injections. `Controller#actionOne()` and `Controller#actionTwo()` requirements could be completely different. There is a way to mitigate this by implementing controller utilities class. More info [in this article ](http://www.whitewashing.de/2013/06/27/extending_symfony2__controller_utilities.html) by _Doctrine_ author.

## About

This bundle tries to satisfy both camps. It injects service locator as a request attribute into controller, configured to retrieve defined set of services. Using this approach there is no need to inject container into controller, and each action can retrieve services it actually needs.

## Installation

Add the following to your composer.json:

```javascript
{
    "require": {
        "devmachine/services-injector-bundle": "~1.0"
    }
}
```

Register bundle in the kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...

        new Devmachine\Bundle\ServicesInjectorBundle\DevmachineServicesInjectorBundle(),
    );
}
```

## Example usage

```php

namespace Acme\UserBundle\Controller;

use Devmachine\Bundle\ServicesInjectorBundle\Configuration\Service;
use Devmachine\Bundle\ServicesInjectorBundle\Request\Services;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Inject to all actions. 
 *
 * @Service(user_manager="fos_user.user_manager")
 */
class UserController
{
    /**
     * Multiple definitions.
     *
     * @Service("twig")
     * @Service("translator")
     */
    public function indexAction(Services $services)
    {
        $params = [
            // Use explicit service name.
            'title' => $services->get('translator')->trans('users'),
            
            // Use __call() i.e. same as $services->get('user_manager').
            'users' => $services->getUserManager()->findAll(),
        ];
        
        return new Response($services->getTwig()->render('AcmeUserBundle:User:index', $params));
    }
    
    /**
     * Array definition.
     *
     * @Service({"ff"="form.factory", "url_generator"="router", "translator", "twig"})
     */
    public function editAction(Request $request, $username, Services $services)
    {
        $user = $services->getUserManager()->findByUsername($username);
        $form = $services->get('ff')->createForm('acme_user', $user);
        
        // ...
    }
}
```
