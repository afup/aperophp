<?php

namespace Aperophp\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
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

            if (!$drink) {
                $app->abort(404, 'Cet événement n\'existe pas.');
            }

            $now = new \Datetime('now');
            $dDrink = \Datetime::createFromFormat('Y-m-d H:i:s', $drink['day'] . ' ' . $drink['hour']);

            if ($now > $dDrink) {
                $app['session'] ->getFlashBag()->add('error', 'L\'événement est terminé.');

                return $returnValue;
            }

            $user = $app['session']->get('user');

            $form = $app['form.factory']->create('drink_participate');

            $form->bind($request->request->get('drink_participate'));
            if ($form->isValid()) {
                $data = $form->getData();

                $member = $app['session']->get('member');
                $user = $app['session']->get('user');

                if (null === $user) {
                    $data['user']['token'] = sha1(md5(rand()).microtime(true).md5(rand()));
                    try {
                        $app['users']->insert($data['user']);
                    } catch (\Exception $e) {
                        $app->abort(500, 'Impossible de vous créer un compte. Merci de réessayer plus tard.');
                    }

                    // Load User in session
                    $id = $app['users']->lastInsertId();
                    $user = $app['users']->find($id);
                    $app['session']->set('user', $user);
                } elseif (null === $member) {
                    try {
                        $app['users']->update($data['user'], array('id' => $user['id']));
                    } catch (\Exception $e) {
                        $app->abort(500, 'Impossible de modifier votre compte. Merci de réessayer plus tard.');
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
                        $app->abort(500, 'Impossible de sauvegarder votre participation. Merci de réessayer plus tard.');
                    }

                    $app['session']->getFlashBag()->add('success', 'Participation modifiée.');

                    return $returnValue;
                }

                $participation['percentage'] = $data['percentage'];
                $participation['reminder'] = (boolean) $data['reminder'];
                $participation['user_id'] = $user['id'];
                $participation['drink_id'] = $drinkId;
                try {
                    $app['drink_participants']->insert($participation);
                } catch (\Exception $e) {
                    $app->abort(500, 'Impossible de sauvegarder votre participation. Merci de réessayer plus tard.');
                }

                $app['session']->getFlashBag()->add('success', 'Participation ajoutée.');

                if ($participation['percentage'] > 0) {
                    $app['mailer']->send($app['mail_factory']->createParticipation($user, $drink));
                }

                return $returnValue;
            }

            $app['session']->getFlashBag()->add('error', 'Le formulaire de participation est mal remplis.');

            return $returnValue;

        })->bind('_participatedrink');
        // *******

        // *******
        // ** Delete participation
        // *******
        $controllers->get('{drinkId}/delete.html/{email}/{token}', function(Request $request, $drinkId, $email, $token) use ($app)
        {
            $drink = $app['drinks']->find($drinkId);

            if (!$drink) {
                $app->abort(404, 'Cet événement n\'existe pas.');
            }

            $now = new \Datetime('now');
            $dDrink = \Datetime::createFromFormat('Y-m-d H:i:s', $drink['day'] . ' ' . $drink['hour']);

            if ($now > $dDrink) {
                $app['session'] ->getFlashBag()->add('error', 'L\'événement est terminé.');

                return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drinkId)));
            }

            if (!$app['session']->has('user')) {
                if (null === $email || null === $token) {
                    $app['session'] ->getFlashBag()->add('error', 'Connectez-vous ou utilisez le lien reçu par mail.');

                    return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drinkId)));
                }
                if (!$user = $app['users']->findOneByEmailToken($email, $token)) {
                    $app['session'] ->getFlashBag()->add('error', 'Couple email/jeton invalide.');

                    return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drinkId)));
                }
                $app['session']->set('user', $user);
            }
            $user = $app['session']->get('user');

            $participation = $app['drink_participants']->findOne($drinkId, $user['id']);

            if (false === $participation) {
                $app['session'] ->getFlashBag()->add('error', 'Participation inexistante.');

                return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drinkId)));
            }

            try {
                $app['drink_participants']->delete(array(
                    'drink_id' => $participation['drink_id'],
                    'user_id' => $participation['user_id']
                ));
            } catch (\Exception $e) {
                $app->abort(500, 'Impossible de sauvegarder votre participation. Merci de réessayer plus tard.');
            }

            $app['session'] ->getFlashBag()->add('success', 'Participation supprimée avec succès.');

            return $request->isXmlHttpRequest() ? 'redirect' : $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drinkId)));

        })
        ->value('email', null)
        ->value('token', null)
        ->bind('_deleteparticipatedrink');

        // *******
        // ** Request to resend an user token
        // *******
        $controllers->match('{drinkId}/forget.html', function(Request $request, $drinkId) use ($app)
        {
            if ($app['session']->has('member')) {
                $app['session']->getFlashBag()->add('error', 'Vous êtes déjà authentifié.');

                return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drinkId)));
            }

            $drink = $app['drinks']->find($drinkId);

            if (!$drink) {
                $app->abort(404, 'Cet événement n\'existe pas.');
            }

            $form = $app['form.factory']->create('participation_forget', array());

            // If it's not POST method, just display void form
            if ('POST' === $request->getMethod()) {
                $form->bind($request->request->get('participation_forget'));
                if ($form->isValid()) {
                    $data = $form->getData();

                    $user = $app['users']->findOneByEmail($data['email']);
                    if (!$user) {
                        $app['session']->getFlashBag()->add('error', 'Aucun utilisateur ne possède cet adresse email.');

                        return $app->redirect($app['url_generator']->generate('_forgetparticipatedrink', array('drinkId' => $drinkId)));
                    }

                    $participation = $app['drink_participants']->findOne($drink['id'], $user['id']);

                    if (false === $participation) {
                        $app['session'] ->getFlashBag()->add('error', 'Participation inexistante.');

                        return $app->redirect($app['url_generator']->generate('_forgetparticipatedrink', array('drinkId' => $drinkId)));
                    }

                    try {
                        $app['mailer']->send($app['mailer']
                            ->createMessage()
                            ->setSubject('[Aperophp.net] Rappel de votre participation à un '.$drink['kind'])
                            ->setFrom(array('noreply@aperophp.net'))
                            ->setTo(array($user['email']))
                            ->setBody($app['twig']->render('drink/forget_mail.html.twig', array(
                                'user'  => $user,
                                'drink' => $drink
                            )), 'text/html')
                        );
                    } catch (\Exception $e) {
                        $app->abort(500, 'Impossible de vous envoyer à nouveau votre jeton. Merci de réessayer plus tard.');
                    }

                    $app['session']->getFlashBag()->add('success', 'Vous allez recevoir un email dans quelques instants contenant votre participation.');

                    return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drinkId)));
                }

                $app['session']->getFlashBag()->add('error', 'Quelque chose n\'est pas valide.');
            }

            return $app['twig']->render('drink/forget.html.twig', array(
                'form' => $form->createView(),
                'drinkId' => $drinkId,
            ));
        })
        ->bind('_forgetparticipatedrink')
        ->method('GET|POST');

        return $controllers;
    }
}
