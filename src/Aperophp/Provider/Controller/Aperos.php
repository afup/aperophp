<?php

namespace Aperophp\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;

/**
 *	Aperos controller
 *
 * 	@author Mikael Randy <mikael.randy@gmail.com>
 */
class Aperos implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = new ControllerCollection();
        
        // ******* 
        // ** Homepage
        // ******* 
        $controllers->get('/', function() use ($app) 
        {			
            return $app['twig']->render('index.html.twig');
        });
        // *******
        
        // *******
        // ** Add a drink 
        // *******
        $controllers->get('apero/new.html', function() use ($app)
        {
            return $app['twig']->render('apero/new.html.twig'); 
        });
        // *******
        
        return $controllers;
    }
}