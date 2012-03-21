<?php

namespace Aperophp\Provider\Controller;

use Aperophp\Model;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Drink controller
 *
 * @author Mikael Randy <mikael.randy@gmail.com>
 * @since 21 janv. 2012
 * @version 1.3 - 21 mars 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
 */
class Drink implements ControllerProviderInterface
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

            $aDrinkChunked = array_chunk(Model\Drink::findAllJoinParticipants($app['db'], 6), 3);

            return $app['twig']->render('drink/index.html.twig', array(
                'drinks' => $aDrinkChunked
            ));
        })->bind('_homepagedrinks');
        // *******

        // *******
        // ** List
        // *******
        $controllers->get('list.html', function() use ($app)
        {
            $app['session']->set('menu', 'listdrinks');

            //TODO pagination
            $aDrink = Model\Drink::findAllJoinParticipants($app['db']);

            return $app['twig']->render('drink/list.html.twig', array(
                'drinks' => $aDrink
            ));
        })->bind('_listdrinks');
        // *******

        // *******
        // ** Add a drink
        // *******
        $controllers->get('new.html', function() use ($app)
        {
            if (!$app['session']->has('user'))
            {
                $app['session']->setFlash('error', 'Vous devez être authentifié pour créer un apéro.');
                return new RedirectResponse($app['url_generator']->generate('_signinmember'));
            }

            $app['session']->set('menu', 'newdrink');

            $form = $app['form.factory']->create(new \Aperophp\Form\DrinkType(), null, array('cities' => Model\City::findAll($app['db'])));

            return $app['twig']->render('drink/new.html.twig', array(
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
                $app['session']->setFlash('error', 'Vous devez être authentifié pour créer un apéro.');
                return new RedirectResponse($app['url_generator']->generate('_signinmember'));
            }

            $user = $app['session']->get('user');

            $form = $app['form.factory']->create(new \Aperophp\Form\DrinkType(), null, array('cities' => Model\City::findAll($app['db'])));

            $form->bindRequest($request);
            if ($form->isValid())
            {
                $data = $form->getData();

                $oDrink = new Model\Drink($app['db']);
                $oDrink
                    ->setPlace($data['place'])
                    ->setAddress($data['address'])
                    ->setDay($data['day'])
                    ->setHour($data['hour'])
                    ->setKind(Model\Drink::KIND_DRINK)
                    ->setDescription($data['description'])
                    ->setLatitude($data['latitude'])
                    ->setLongitude($data['longitude'])
                    ->setCityId($data['city_id'])
                    ->setUserId($user['id']);

                $oDrink->save();

                return $app->redirect($app['url_generator']->generate('_homepagedrinks'));
            }

            return $app['twig']->render('drink/new.html.twig', array(
                'form' => $form->createView(),
            ));
        })->bind('_createdrink');
        // *******

        // *******
        // ** Edit a drink
        // *******
        $controllers->get('{id}/edit.html', function($id) use ($app)
        {
            $app['session']->set('menu', null);

            $oDrink = Model\Drink::findOneById($app['db'], $id);

            if (!$oDrink)
            {
                $app->abort(404, 'Cet apéro n\'existe pas.');
            }

            $now = new \Datetime('now');
            $dDrink = \Datetime::createFromFormat(  'Y-m-d H:i:s',
                                                    $oDrink->getDay() . ' ' . $oDrink->getHour());
            if ($now > $dDrink)
            {
                $app['session'] ->setFlash('error', 'L\'événement est terminé.');
                return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $id)));
            }

            $user = $app['session']->get('user');

            if (!$user || $oDrink->getUserId() != $user['id'])
            {
                $app['session']->setFlash('error', 'Vous devez être authentifié et être organisateur de cet apéro pour pouvoir l\'éditer.');
                return new RedirectResponse($app['url_generator']->generate('_signinmember'));
            }

            $form = $app['form.factory']->create(new \Aperophp\Form\DrinkType(), $oDrink, array('cities' => Model\City::findAll($app['db'])));

            return $app['twig']->render('drink/edit.html.twig', array(
                'form' => $form->createView(),
                'id' => $id,
            ));
        })->bind('_editdrink');
        // *******

        // *******
        // ** Update a drink
        // *******
        $controllers->post('{id}/update.html', function(Request $request, $id) use ($app)
        {
            $app['session']->set('menu', null);

            $oDrink = Model\Drink::findOneById($app['db'], $id);

            if (!$oDrink)
            {
                $app->abort(404, 'Cet apéro n\'existe pas.');
            }

            $now = new \Datetime('now');
            $dDrink = \Datetime::createFromFormat(  'Y-m-d H:i:s',
                                                    $oDrink->getDay() . ' ' . $oDrink->getHour());
            if ($now > $dDrink)
            {
                $app['session'] ->setFlash('error', 'L\'événement est terminé.');
                return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $id)));
            }

            $user = $app['session']->get('user');

            if (!$user || $oDrink->getUserId() != $user['id'])
            {
                $app['session']->setFlash('error', 'Vous devez être authentifié et être organisateur de cet apéro pour pouvoir l\'éditer.');
                return new RedirectResponse($app['url_generator']->generate('_signinmember'));
            }

            $form = $app['form.factory']->create(new \Aperophp\Form\DrinkType(), null, array('cities' => Model\City::findAll($app['db'])));

            $form->bindRequest($request);
            if ($form->isValid())
            {
                $data = $form->getData();

                $oDrink
                    ->setPlace($data['place'])
                    ->setAddress($data['address'])
                    ->setDay($data['day'])
                    ->setHour($data['hour'])
                    ->setKind(Model\Drink::KIND_DRINK)
                    ->setDescription($data['description'])
                    ->setLatitude($data['latitude'])
                    ->setLongitude($data['longitude'])
                    ->setCityId($data['city_id'])
                    ->setUserId($user['id']);

                $oDrink->save();

                $app['session']->setFlash('success', 'L\'apéro a été modifié avec succès.');

                return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $id)));
            }

            return $app['twig']->render('drink/edit.html.twig', array(
                'form' => $form->createView(),
                'id' => $id,
            ));
        })->bind('_updatedrink');
        // *******

        // *******
        // ** See a drink
        // *******
        $controllers->get('{id}/view.html', function($id) use ($app)
        {
            $app['session']->set('menu', null);

            $oDrink = Model\Drink::findOneById($app['db'], $id);

            if (!$oDrink)
            {
                $app->abort(404, 'Cet apéro n\'existe pas.');
            }

            // If member is authenticated, prefill form.
            $oUser = null;
            $oDrinkParticipation = null;
            $values = array();
            if ($user = $app['session']->get('user'))
            {
                $oUser = Model\User::findOneById($app['db'], $user['id']);

                $values = array(
                    'user_id' => $oUser->getId(),
                    'lastname' => $oUser->getLastname(),
                    'firstname' => $oUser->getFirstname(),
                    'email' => $oUser->getEmail(),
                );

                $oDrinkParticipation = Model\DrinkParticipation::find($app['db'], $oDrink->getId(), $oUser->getId());
            }

            $dValues             = $values;
            if( $oDrinkParticipation )
                $dValues += array(
                                    'percentage'    => $oDrinkParticipation->getPercentage(),
                                    'reminder'      => $oDrinkParticipation->getReminder()
                            );


            $comment        = $app['form.factory']->create(new \Aperophp\Form\DrinkCommentType(), $values, array('user' => $oUser));
            $participation  = $app['form.factory']->create(new \Aperophp\Form\DrinkParticipationType(), $dValues, array('user' => $oUser));

            $now = new \Datetime('now');
            $dDrink = \Datetime::createFromFormat(  'Y-m-d H:i:s',
                                                    $oDrink->getDay() . ' ' . $oDrink->getHour());

            return $app['twig']->render('drink/view.html.twig', array(
                'drink'             => $oDrink,
                'commentForm'       => $comment->createView(),
                'participationForm' => $participation->createView(),
                'isFinished'        => $now > $dDrink,
                'isParticipating'   => null !== $oDrinkParticipation));
        })->bind('_showdrink');
        // *******

        return $controllers;
    }
}
