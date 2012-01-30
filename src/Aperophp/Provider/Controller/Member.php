<?php

namespace Aperophp\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;

/**
 * Member controller.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 22 janv. 2012 
 * @version 1.0 - 22 janv. 2012 - Koin <pkoin.koin@gmail.com>
 */
class Member implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = new ControllerCollection();
        
        // *******
        // ** Signup member
        // *******
        $controllers->get('signup.html', function() use ($app)
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
        $controllers->post('create.html', function(Request $request) use ($app)
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