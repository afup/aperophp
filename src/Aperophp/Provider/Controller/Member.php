<?php

namespace Aperophp\Provider\Controller;

use Aperophp\Model;
use Aperophp\Lib\Utils;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;

/**
 * Member controller.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 22 janv. 2012 
 * @version 1.1 - 4 févr. 2012 - Koin <pkoin.koin@gmail.com>
 */
class Member implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = new ControllerCollection();
        
        // *******
        // ** Homepage member
        // *******
        $controllers->get('/', function() use ($app)
        {
            return $app['twig']->render('member/index.html.twig');
        })->bind('_homepagemember');
        // *******
        
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
                
                $app['db']->beginTransaction();
                
                try 
                {
                    // 1. Create member
                    $oMember = new Model\Member($app['db']);
                    $oMember
                        ->setUsername($data['username'])
                        ->setPassword(Utils::hashMe($data['password'], $app['secret']))
                        ->setActive(1)
                        ->save();
                    
                    // 2. Create user with member association
                    $oUser = new Model\User($app['db']);
                    $oUser
                        ->setEmail($data['email'])
                        ->setFirstname($data['firstname'])
                        ->setLastname($data['lastname'])
                        ->setMemberId($oMember->getId())
                        ->save();
                    
                    $app['db']->commit();
                } 
                catch (Exception $e) 
                {
                    $app['db']->rollback();
                    throw $e;
                }
                
                return $app->redirect($app['url_generator']->generate('_homepagemember'));
            }
        
            return $app['twig']->render('member/signup.html.twig', array(
                'form' => $form->createView(),
            ));
        })->bind('_createmember');
        // *******
        
        return $controllers;
    }
}