# LBFrameworkExtraBundle

[![Build Status](https://travis-ci.org/lakiboy/LBFrameworkExtraBundle.svg?branch=master)](https://travis-ci.org/lakiboy/LBFrameworkExtraBundle)

Configure Symfony controllers via annotations as extension of [SensioFrameworkExtraBundle](https://github.com/sensiolabs/SensioFrameworkExtraBundle).

## @Service annotation sample usage

```php

namespace Acme\UserBundle\Controller;

use LB\FrameworkExtraBundle\Configuration\Service;
use LB\FrameworkExtraBundle\Request\Services;
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
