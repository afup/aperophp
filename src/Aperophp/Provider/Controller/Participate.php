<?php

namespace Aperophp\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;

/**
 *  Controller for DrinkParticipations managing.
 *
 *  @author Gautier DI FOLCO <gautier.difolco@gmail.com>
 *  @version 1.4 - 23 mars 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
 */
class Participate implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        // *******
        // ** Save/Update participation
        // *******
        $controllers->post('{drinkId}/register.html', function(Request $request, $drinkId) use ($app)
        {
            $returnValue = $request->isXmlHttpRequest() ? 'redirect' : $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drinkId)));

            $drink = $app['drinks']->find($drinkId);

            if (!$drink)
                $app->abort(404, 'Cet apéro n\'existe pas.');

            $now = new \Datetime('now');
            $dDrink = \Datetime::createFromFormat('Y-m-d H:i:s', $drink['day'] . ' ' . $drink['hour']);

            if ($now > $dDrink) {
                $app['session'] ->setFlash('error', 'L\'événement est terminé.');

                return $returnValue;
            }

            $user = $app['session']->get('user');

            $form = $app['form.factory']->create('drink_participate');

            $form->bindRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $member = $app['session']->get('member');
                $user = $app['session']->get('user');

                if (null === $user) {
                    $data['user']['token'] = sha1(md5(rand()).microtime(true).md5(rand()));
                    try {
                        $app['users']->insert($data['user']);
                    } catch (\Exception $e) {
                        $app['db']->rollback();
                        $app->abort(500, 'Un requête n\a pas pu s\'exécuter.');
                    }

                    // Load User in session
                    $id = $app['users']->lastInsertId();
                    $user = $app['users']->find($id);
                    $app['session']->set('user', $user);
                } elseif (null === $member) {
                    try {
                        $app['users']->update($data['user'], array('id' => $user['id']));
                    } catch (\Exception $e) {
                        $app['db']->rollback();
                        $app->abort(500, 'Un requête n\a pas pu s\'exécuter.');
                    }
                }

                // Already participating?
                $participation = $app['drink_participants']->findOne($drinkId, $user['id']);
                if (false !== $participation) {
                    $participation['percentage'] = $data['percentage'];
                    $participation['reminder'] = $data['reminder'];
                    try {
                        $app['drink_participants']->update($participation, array(
                            'drink_id' => $drinkId,
                            'user_id' => $user['id'],
                        ));
                    } catch (\Exception $e) {
                        $app['db']->rollback();
                        $app->abort(500, 'Un requête n\a pas pu s\'exécuter.');
                    }

                    $app['session']->setFlash('success', 'Participation modifiée.');

                    return $returnValue;
                }

                $participation['percentage'] = $data['percentage'];
                $participation['reminder'] = (boolean) $data['reminder'];
                $participation['user_id'] = $user['id'];
                $participation['drink_id'] = $drinkId;
                try {
                    $app['drink_participants']->insert($participation);
                } catch (\Exception $e) {
                    $app['db']->rollback();
                    $app->abort(500, 'Un requête n\a pas pu s\'exécuter.');
                }

                $app['session']->setFlash('success', 'Participation ajoutée.');

                $app['mailer']->send($app['mailer']
                    ->createMessage()
                    ->setSubject('[Aperophp.net] Inscription à un '.$drink['kind'])
                    ->setFrom(array('noreply@aperophp.net'))
                    ->setTo(array($user['email']))
                    ->setBody($app['twig']->render('drink/participation_mail.html.twig', array(
                        'user'  => $user,
                        'drink' => $drink
                    )), 'text/html')
                );

                return $returnValue;
            }

            return $app['twig']->render('drink/participate.html.twig', array(
                'participationForm' => $form->createView(),
                'drink' => $drink,
            ));

        })->bind('_participatedrink');
        // *******

        // *******
        // ** Delete participation
        // *******
        $controllers->get('{drinkId}/delete.html/{email}/{token}', function(Request $request, $drinkId, $email, $token) use ($app)
        {
            $drink = $app['drinks']->find($drinkId);

            if (!$drink)
                $app->abort(404, 'Cet apéro n\'existe pas.');

            $now = new \Datetime('now');
            $dDrink = \Datetime::createFromFormat('Y-m-d H:i:s', $drink['day'] . ' ' . $drink['hour']);

            if ($now > $dDrink) {
                $app['session'] ->setFlash('error', 'L\'événement est terminé.');

                return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drinkId)));
            }

            $oUser = null;
            $values = array();

            if (!$app['session']->has('user')) {
                if (null === $email || null === $token) {
                    $app['session'] ->setFlash('error', 'Connectez-vous ou utilisez le lien reçu par mail.');

                    return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drinkId)));
                }
                if (!$user = $app['users']->findOneByEmailAndToken($email, $token)) {
                    $app['session'] ->setFlash('error', 'Couple email/jeton invalide.');

                    return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drinkId)));
                }
                $app['session']->set('user', $user);
            }
            $user = $app['session']->get('user');

            $participation = $app['drink_participants']->findOne($drinkId, $user['id']);

            if (false === $participation) {
                $app['session'] ->setFlash('error', 'Participation inexistante.');

                return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drinkId)));
            }

            try {
                $app['drink_participants']->delete(array(
                    'drink_id' => $participation['drink_id'],
                    'user_id' => $participation['user_id']
                ));
            } catch (\Exception $e) {
                $app['db']->rollback();
                $app->abort(500, 'Un requête n\a pas pu s\'exécuter.');
            }

            $app['session'] ->setFlash('success', 'Participation supprimée avec succès.');

            return $request->isXmlHttpRequest() ? 'redirect' : $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drinkId)));

        })
        ->value('email', null)
        ->value('token', null)
        ->bind('_deleteparticipatedrink');

        return $controllers;
    }
}
