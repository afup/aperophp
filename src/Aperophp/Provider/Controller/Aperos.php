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
        
        // *******
        // ** Signup member
        // *******
        $controllers->get('member/signup.html', function() use ($app)
        {
            $form = $app['form.factory']->create(new \Aperophp\Form\Signup());
            
            return $app['twig']->render('member/signup.html.twig', array(
                'form' => $form->createView(),
            ));
        })->bind('_signupmember');
        // *******
        
        // *******
        // ** Create member
        // *******
        $controllers->post('member/create.html', function(Request $request) use ($app)
        {
            $form = $app['form.factory']->create(new \Aperophp\Form\Signup());
            
            $form->bindRequest($request);
            if ($form->isValid())
            {
                $data = $form->getData();
                // TODO save member in database.
                var_dump($data);
                die;
            }
            
            return $app['twig']->render('member/signup.html.twig', array(
                'form' => $form->createView(),
            ));
        })->bind('_createmember');
        // *******
        
        return $controllers;
    }
}