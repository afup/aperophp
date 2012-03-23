<?php

namespace Aperophp\Provider\Controller;

use Aperophp\Model;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;

/**
 *  Controller for DrinkParticipations managing.
 *
 *  @author Gautier DI FOLCO <gautier.difolco@gmail.com>
 *  @version 1.3 - 21 mars 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
 */
class Participate implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = new ControllerCollection();

        // *******
        // ** Save/Update participation
        // *******
        $controllers->post('{drink_id}/register.html', function(Request $request, $drink_id) use ($app)
        {
            $oDrink = Model\Drink::findOneById($app['db'], $drink_id);

            if (!$oDrink)
            {
                $app->abort(404, 'Cet événement n\'existe pas.');
            }

            $now = new \Datetime('now');
            $dDrink = \Datetime::createFromFormat(  'Y-m-d H:i:s',
                                                    $oDrink->getDay() . ' ' . $oDrink->getHour());
            if ($now > $dDrink)
            {
                $app['session'] ->setFlash('error', 'L\'événement est terminé.');
                return $request->isXmlHttpRequest() ? 'redirect' : $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drink_id)));
            }

            $oUser = null;
            $values = array();
            if ($user = $app['session']->get('user'))
            {
                $oUser = Model\User::findOneById($app['db'], $user['id']);
            }

            $form           = $app['form.factory']->create(new \Aperophp\Form\DrinkParticipationType(), null, array('user' => $oUser));

            $form->bindRequest($request);
            if ($form->isValid())
            {
                $data = $form->getData();

                if ($oUser && $oUser->getId() != $data['user_id'])
                {
                    throw new \Exception('Une erreur est survenue, il se peut que vous vous soyez connecté entre temps');
                }

                if (!$oUser && $data['user_id'])
                {
                    throw new \Exception('Une erreur est survenue, il se peut que vous ayez perdu votre session');
                }

                $app['db']->beginTransaction();

                try
                {
                    // If member is not authenticated, a user is created.
                    if (!$oUser)// TODO  sauf s'il a un token !!!
                    {
                        $token = sha1(md5(rand()).microtime(true).md5(rand()));
                        $oUser = new Model\User($app['db']);
                        $oUser
                                ->setEmail($data['email'])
                                ->setFirstname($data['firstname'])
                                ->setLastname($data['lastname'])
                                ->setToken($token)
                                ->save();

                        /*
                        // TODO : Send mail
                        $message = \Swift_Message::newInstance()
                            ->setSubject('[YourSite] Feedback')
                            ->setFrom(array('noreply@yoursite.com'))
                            ->setTo(array('feedback@yoursite.com'))
                            ->setBody($request->get('message'));

                        $app['mailer']->send($message);
                         */
                    }

                    $data           = $form->getData();
                    $participation  = Model\DrinkParticipation::find(
                                                                        $app['db'],
                                                                        $drink_id,
                                                                        $oUser->getId()
                                                                    );

                    if( null === $participation )
                    {
                        $participation  = new Model\DrinkParticipation($app['db']);
                        $participation
                                        ->setDrinkId($drink_id)
                                        ->setUserId($oUser->getId());
                    }

                    $participation      ->setPercentage($data['percentage'])
                                        ->setReminder($data['reminder'])
                                        ->save();

                    $app['session']     ->setFlash('success', 'Participation modifiée.');

                    $app['db']->commit();
                }
                catch (Exception $e)
                {
                    $app['db']->rollback();
                    throw $e;
                }

                return $request->isXmlHttpRequest() ? 'redirect' : $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drink_id)));
            }
            else

            return $app['twig']->render('drink/participate.html.twig', array(
                'participationForm' => $form->createView(),
                'drink' => $oDrink,
            ));

        })->bind('_participatedrink');
        // *******

        // *******
        // ** Delete participation
        // *******
        $controllers->get('{drink_id}/delete.html/{email}/{token}', function(Request $request, $drink_id, $email, $token) use ($app)
        {
            $oDrink = Model\Drink::findOneById($app['db'], $drink_id);

            if (!$oDrink)
            {
                $app->abort(404, 'Cet événement n\'existe pas.');
            }

            $now = new \Datetime('now');
            $dDrink = \Datetime::createFromFormat(  'Y-m-d H:i:s',
                                                    $oDrink->getDay() . ' ' . $oDrink->getHour());
            if ($now > $dDrink)
            {
                $app['session'] ->setFlash('error', 'L\'événement est terminé.');
                return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drink_id)));
            }

            $oUser = null;
            $values = array();

            if (!empty($token) && !empty($email))
            {
                $oUser = Model\User::findOneByEmailToken($app['db'], $email, $token);
                if (!$oUser)
                {
                    $app['session'] ->setFlash('error', 'Couple email/jeton invalide.');
                    return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drink_id)));
                }
            }
            else
            {
                if ($user = $app['session']->get('user'))
                {
                    $oUser = Model\User::findOneById($app['db'], $user['id']);
                }
                // If member is not authenticated, nothing can be done.
                if (!$oUser)
                {
                    $app['session'] ->setFlash('error', 'Connectez-vous ou utilisez le lien reçu par mail.');
                    return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drink_id)));
                }
            }

            $app['db']->beginTransaction();

            try
            {

                $participation  = Model\DrinkParticipation::find(
                                                                    $app['db'],
                                                                    $drink_id,
                                                                    $oUser->getId()
                                                                );

                if (null === $participation
                || ((!empty($token) || !empty($email)) && null != $participation->getUser()->getMember()))
                    $app['session'] ->setFlash('error', 'Participation inéxistante.');
                else
                {
                    $participation  ->delete();
                    $app['session'] ->setFlash('success', 'Participation supprimée avec succès.');
                }

                $app['session']     ->setFlash('success', 'Participation modifiée.');

                $app['db']->commit();
            }
            catch (Exception $e)
            {
                $app['db']->rollback();
                throw $e;
            }

            return $request->isXmlHttpRequest() ? 'redirect' : $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drink_id)));

        })->value('email', null)->value('token', null)->bind('_deleteparticipatedrink');

        return $controllers;
    }
}
