<?php

namespace Aperophp\Provider\Controller;

use Aperophp\Model;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;

/**
 *  Controller for DrinkParticipation managing.
 *
 *  @author Gautier DI FOLCO <gautier.difolco@gmail.com>
 *  @version 1.0 - 21 janv. 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
 */
class DrinkParticipation implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = new ControllerCollection();

        // TODO : Check if Drink is not terminated

        // *******
        // ** Create participation
        // *******
        $controllers->post('create.html', function(Request $request) use ($app)
        {
            $app['session']->set('menu', 'drink');

            if( $app['session']->has('user') )
            {
                $form       = $app['form.factory']->create(new \Aperophp\Form\Participate\CreateType());
                $user_id    = $app['session']->get('user')->getId();
                $form       ->bindRequest($request);
            }
            else
            {
                $form       = $app['form.factory']->create(new \Aperophp\Form\Participate\CreateAnonymousType());
                $form       ->bindRequest($request);
                $d          = $form->getData();
                $user_id    = null;
            }

            if( $form->isValid() )
            {
                $data           = $form->getData();


                if( null === $user_id )
                {
                    $token      = sha1( microtime(true) + 42 );
                    $user       = new Model\User($app['db']);
                    $user       ->setEmail($data['email'])
                                ->setFirstname($data['firstname'])
                                ->setLastname($data['lastname'])
                                ->setToken($token)
                                ->save();

                    $user_id    = $user->getId();
                    // TODO : Send mail
                }

                $participation  = Model\DrinkParticipation::find(
                                                                    $app['db'],
                                                                    $data['drink'],
                                                                    $user_id
                                                                );

                if( null !== $participation )
                    $participation  ->setDrinkId($data['drink'])
                                    ->setUserId($user_id);

                $participation      ->setPercentage($data['percentage'])
                                    ->setReminder($data['remider'])
                                    ->save();

                $app['session']     ->setFlash('success', 'Participation modifiée.');
            }
            else
                $app['session']     ->setFlash('error', 'Champs manquant(s).');

            return $app->redirect($app['url_generator']->generate('_viewdrink'));
        })->bind('_createparticipatedrink');
        // *******

        // *******
        // ** Update participation
        // *******
        $controllers->post('update.html', function(Request $request) use ($app)
        {
            $app['session']->set('menu', 'drink');

            if( $app['session']->has('user') )
            {
                $form           = $app['form.factory']->create(new \Aperophp\Form\Participate\UpdateType());
                $user_id        = $app['session']->get('user')->getId();
                $form           ->bindRequest($request);
            }
            else
            {
                $form       = $app['form.factory']->create(new \Aperophp\Form\Participate\UpdateAnonymousType());
                $form       ->bindRequest($request);
                $d          = $form->getData();
                $user_id    = Modele\User::findByTokenEmail($d['token'], $d['email']);

                if( null !== $user_id )
                {
                    $app['session'] ->setFlash('error', 'Couple email/jeton invalide.');
                    return $app     ->redirect($app['url_generator']
                                    ->generate('_viewdrink'));
                }
            }

            if( $form->isValid() )
            {
                $data           = $form->getData();

                $participation  = Model\DrinkParticipation::find(
                                                                    $app['db'],
                                                                    $data['drink'],
                                                                    $user_id
                                                                );

                if( null !== $participation )
                    $participation  ->setDrinkId($data['drink'])
                                    ->setUserId($user_id);

                $participation      ->setPercentage($data['percentage'])
                                    ->setReminder($data['remider'])
                                    ->save();

                $app['session']     ->setFlash('success', 'Participation modifiée.');
            }
            else
                $app['session']     ->setFlash('error', 'Champs manquant(s).');

            return $app->redirect($app['url_generator']->generate('_viewdrink'));
        })->bind('_updateparticipatedrink');
        // *******

        // *******
        // ** Delete participation
        // *******
        $controllers->post('delete.html', function(Request $request) use ($app)
        {
            $app['session']->set('menu', 'drink');

            if( $app['session']->has('user') )
            {
                $form           = $app['form.factory']->create(new \Aperophp\Form\Participate\DeleteType());
                $user_id        = $app['session']->get('user')->getId();
                $form           ->bindRequest($request);
            }
            else
            {
                $form       = $app['form.factory']->create(new \Aperophp\Form\Participate\DeleteAnonymousType());
                $form       ->bindRequest($request);
                $d          = $form->getData();
                $user_id    = Modele\User::findByTokenEmail($d['token'], $d['email']);

                if( null !== $user_id )
                {
                    $app['session'] ->setFlash('error', 'Couple email/jeton invalide.');
                    return $app     ->redirect($app['url_generator']
                                    ->generate('_viewdrink'));
                }
            }

            if( $form->isValid() )
            {
                $data           = $form->getData();

                $participation  = Model\DrinkParticipation::find(
                                                                    $app['db'],
                                                                    $data['drink'],
                                                                    $user_id
                                                                );

                if( null !== $participation )
                    $app['session'] ->setFlash('error', 'Participation inéxistante.');
                else
                {
                    $participation  ->delete();
                    $app['session'] ->setFlash('success', 'Participation supprimée avec succès.');
                }
            }
            else
                $app['session']     ->setFlash('error', 'Champs manquant(s).');

            return $app->redirect($app['url_generator']->generate('_viewdrink'));
        })->bind('_updateparticipatedrink');

        return $controllers;
    }
}
