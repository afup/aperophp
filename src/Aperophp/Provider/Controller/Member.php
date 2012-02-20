<?php

namespace Aperophp\Provider\Controller;

use Aperophp\Model;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Member controller.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 22 janv. 2012 
 * @version 1.3 - 7 févr. 2012 - Koin <pkoin.koin@gmail.com>
 */
class Member implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = new ControllerCollection();
        
        // *******
        // ** Signin member
        // *******
        $controllers->get('signin.html', function() use ($app)
        {
            $app['session']->set('menu', 'signin');
            
            $form = $app['form.factory']->create(new \Aperophp\Form\SigninType());
        
            return $app['twig']->render('member/signin.html.twig', array(
                'form' => $form->createView(),
            ));
        })->bind('_signinmember');
        // *******
        
        // *******
        // ** Authenticate member
        // *******
        $controllers->post('authenticate.html', function(Request $request) use ($app)
        {
            $app['session']->set('menu', 'signin');
            
            $form = $app['form.factory']->create(new \Aperophp\Form\SigninType());
        
            $form->bindRequest($request);
            if ($form->isValid())
            {
                $data = $form->getData();
                
                $oMember = Model\Member::findOneByUsername($app['db'], $data['username']);
                
                if ($oMember && $oMember->getActive() && $oMember->getPassword() == $app['utils']->hash($data['password']))
                {
                    $app['session']->set('user', array(
                        'id' => $oMember->getId(),
                        'username' => $oMember->getUsername(),
                    ));
                    return $app->redirect($app['url_generator']->generate('_homepageaperos'));
                }
                
                $app['session']->setFlash('error', 'Identifiant / Mot de passe incorrect.');
            }
            
            return $app['twig']->render('member/signin.html.twig', array(
                'form' => $form->createView(),
            ));
        })->bind('_authenticatemember');
        // *******
        
        // *******
        // ** Signout member
        // *******
        $controllers->get('signout.html', function(Request $request) use ($app)
        {
            $app['session']->clear();
            $app['session']->invalidate();
            
            return $app->redirect($app['url_generator']->generate('_homepageaperos'));
        })->bind('_signoutmember');
        // *******
        
        // *******
        // ** Signup member
        // *******
        $controllers->get('signup.html', function() use ($app)
        {
            $app['session']->set('menu', 'signup');
            
            $form = $app['form.factory']->create(new \Aperophp\Form\SignupType());
        
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
            $app['session']->set('menu', 'signup');
            
            $form = $app['form.factory']->create(new \Aperophp\Form\SignupType());
        
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
                        ->setPassword($app['utils']->hash($data['password']))
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
                
                $app['session']->setFlash('success', 'Votre compte a été créé avec succès.');
                
                return $app->redirect($app['url_generator']->generate('_signinmember'));
            }
        
            return $app['twig']->render('member/signup.html.twig', array(
                'form' => $form->createView(),
            ));
        })->bind('_createmember');
        // *******
        
        // *******
        // ** Edit member
        // *******
        $controllers->get('edit.html', function() use ($app)
        {
            if (!$app['session']->has('user'))
            {
                $app['session']->setFlash('error', 'Vous devez être authentifié pour accéder à cette ressource.');
                return new RedirectResponse($app['url_generator']->generate('_signinmember'));
            }
            
            $user = $app['session']->get('user');
            $oMember = Model\Member::findOneByUsername($app['db'], $user['username']);
            $oUser = $oMember->getUser();
            
            $form = $app['form.factory']->create(new \Aperophp\Form\EditMemberType(), array(
                'lastname' => $oUser->getLastname(),
                'firstname' => $oUser->getFirstname(),
                'email' => $oUser->getEmail(),
            ));
        
            return $app['twig']->render('member/edit.html.twig', array(
                'form' => $form->createView(),
            ));
        })->bind('_editmember');
        // *******
        
        // *******
        // ** Update member
        // *******
        $controllers->post('update.html', function(Request $request) use ($app)
        {
            if (!$app['session']->has('user'))
            {
                $app['session']->setFlash('error', 'Vous devez être authentifié pour accéder à cette ressource.');
                return new RedirectResponse($app['url_generator']->generate('_signinmember'));
            }
            
            $user = $app['session']->get('user');
            $oMember = Model\Member::findOneByUsername($app['db'], $user['username']);
            $oUser = $oMember->getUser();
            
            $form = $app['form.factory']->create(new \Aperophp\Form\EditMemberType());
        
            $form->bindRequest($request);
            if ($form->isValid())
            {
                $data = $form->getData();
                
                $app['db']->beginTransaction();
                
                try
                {
                    $oUser
                        ->setLastname($data['lastname'])
                        ->setFirstname($data['firstname'])
                        ->setEmail($data['email'])
                        ->save();
                    
                    if ($data['password'])
                    {
                        $oMember
                            ->setPassword($app['utils']->hash($data['password']))
                            ->save();
                    }
                    
                    $app['db']->commit();
                }
                catch (Exception $e)
                {
                    $app['db']->rollback();
                    throw $e;
                }
                
                $app['session']->setFlash('success', 'Votre compte a été modifié avec succès.');
                
                return $app->redirect($app['url_generator']->generate('_editmember'));
            }
            
            return $app['twig']->render('member/edit.html.twig', array(
                'form' => $form->createView(),
            ));
        })->bind('_updatemember');
        // *******
        
        return $controllers;
    }
}