<?php

namespace Aperophp\Provider\Controller;

use Aperophp\Model;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;

/**
 * Aperos controller
 *
 * @author Mikael Randy <mikael.randy@gmail.com>
 * @since 21 janv. 2012
 * @version 1.1 - 6 févr. 2012 - Koin <pkoin.koin@gmail.com>
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
            $app['session']->set('menu', 'home');
            
            return $app['twig']->render('apero/index.html.twig');
        })->bind('_homepageaperos');
        // *******
        
        // *******
        // ** Add a drink 
        // *******
        $controllers->get('new.html', function() use ($app)
        {
            $app['session']->set('menu', 'newdrink');
            
            if (!$app['session']->has('user'))
            {
                $app->abort(401, 'Authentication required.');
            }
            
            $form = $app['form.factory']->create(new \Aperophp\Form\DrinkType($app['db']));
            
            return $app['twig']->render('apero/new.html.twig', array(
                'form' => $form->createView(),
            ));
        })->bind('_newdrink');
        // *******
        
        // *******
        // ** Create a drink
        // *******
        $controllers->post('create.html', function(Request $request) use ($app)
        {
            if (!$app['session']->has('user'))
            {
                $app->abort(401, 'Authentication required.');
            }
            
            $user = $app['session']->get('user');
            
            $form = $app['form.factory']->create(new \Aperophp\Form\DrinkType($app['db']));
        
            $form->bindRequest($request);
            if ($form->isValid())
            {
                $data = $form->getData();
                
                $oDrink = new Model\Drink($app['db']);
                $oDrink
                    ->setPlace($data['place'])
                    ->setDay($data['day'])
                    ->setHour($data['hour'])
                    ->setKind(Model\Drink::KIND_DRINK)
                    ->setDescription($data['description'])
                    ->setIdCity($data['id_city'])
                    ->setIdUser($user['id']);
                
                $oDrink->save();
                
                return $app->redirect($app['url_generator']->generate('_homepageaperos'));
            }
            
            return $app['twig']->render('apero/new.html.twig', array(
                'form' => $form->createView(),
            ));
        })->bind('_createdrink');
        // *******
        
        return $controllers;
    }
}