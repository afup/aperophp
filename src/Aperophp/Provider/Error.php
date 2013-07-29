<?php

namespace Aperophp\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Error provider.
 *
 * @author  Gautier DI FOLCO <gautier.difolco@gmail.com>
 * @since   23 july 2012
 * @version 1.0 - 23 july 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
 */
class Error implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        // *******
        // ** Signin member
        // *******

        $app->error(function (\Exception $e, $code) use($app)
        {
            $page = 'default';
            switch ($code){
                    default:
                        break;
            }
            $app['session']->getFlashBag()->add('error', $e->getMessage());
            return new Response($app['twig']->render('error/'.$page.'.html.twig'), $code);
        });
        // *******

        return $controllers;
    }
}
