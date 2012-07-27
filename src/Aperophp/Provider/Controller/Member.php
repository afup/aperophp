<?php

namespace Aperophp\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Member controller.
 *
 * @author  Koin <pkoin.koin@gmail.com>
 * @author  Mikael Randy <mikael.randy@gmail.com>
 * @since   22 janv. 2012
 * @version 1.4 - 22 mars 2012 - Mikael Randy <mikael.randy@gmail.com>
 */
class Member implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        // *******
        // ** Signin member
        // *******
        $controllers->match('signin.html', function(Request $request) use ($app)
        {
            $app['session']->set('menu', 'signin');
            $form = $app['form.factory']->create('signin');

            // If it's not POST method, just display void form
            if ($request->getMethod() == 'POST') {
                $form->bindRequest($request);
                if ($form->isValid()) {
                    $data = $form->getData();

                    $member = $app['members']->findOneByUsernameAndPassword($data['username'], $app['utils']->hash($data['password']));

                    if ($member) {
                        unset($member['password']);
                        $app['session']->set('member', $member);
                        $user = $app['users']->findOneByMemberId($member['id']);
                        $app['session']->set('user', $user);

                        return $app->redirect($app['url_generator']->generate('_homepagedrinks'));
                    }

                    $app['session']->setFlash('error', 'Identifiant / Mot de passe incorrect.');
                }
                // Invalid form will display form back
            }

            return $app['twig']->render('member/signin.html.twig', array(
                'form' => $form->createView(),
            ));
        })
        ->bind('_signinmember')
        ->method('GET|POST');

        // *******

        // *******
        // ** Signout member
        // *******
        $controllers->get('signout.html', function(Request $request) use ($app)
        {
            $app['session']->clear();
            $app['session']->invalidate();

            return $app->redirect($app['url_generator']->generate('_homepagedrinks'));
        })->bind('_signoutmember');
        // *******

        // *******
        // ** Signup member
        // *******
        $controllers->get('signup.html', function(Request $request) use ($app)
        {
            $app['session']->set('menu', 'signup');
            $form = $app['form.factory']->create('signup');

            // If it's not POST method, just display void form
            if ($request->getMethod() == 'POST') {
                $form->bindRequest($request);
                if ($form->isValid()) {
                    $data = $form->getData();

                    $app['db']->beginTransaction();

                    try {
                        $data['member']['password'] = $app['utils']->hash($data['member']['password']);
                        $app['members']->insert($data['member']);
                        $data['user']['member_id'] = $app['members']->lastInsertId();
                        $app['users']->insert($data['user']);

                        $app['db']->commit();
                    } catch (\Exception $e) {
                        try {
                            $app['db']->rollback();
                        } catch (\Exception $e) {
                        }
                        $app->abort(500, 'Impossible de vous inscrire. Merci de réessayer plus tard.');
                    }

                    $app['session']->setFlash('success', 'Votre compte a été créé avec succès.');

                    return $app->redirect($app['url_generator']->generate('_signinmember'));
                }
                // Invalid form will display form back
                $app['session']->setFlash('error', 'Quelque chose n\'est pas valide');
            }

            return $app['twig']->render('member/signup.html.twig', array(
                'form' => $form->createView(),
            ));
        })
        ->bind('_signupmember')
        ->method('GET|POST');
        // *******

        // *******
        // ** Edit member
        // *******
        $controllers->match('edit.html', function(Request $request) use ($app)
        {
            if (!$app['session']->has('member')) {
                $app['session']->setFlash('error', 'Vous devez être authentifié pour accéder à cette ressource.');

                return new RedirectResponse($app['url_generator']->generate('_signinmember'));
            }

            $member = $app['session']->get('member');
            $user = $app['session']->get('user');

            $form = $app['form.factory']->create('member_edit', array(
                'member' => $member,
                'user'   => $user
            ));

            // If it's not POST method, just display void form
            if ($request->getMethod() == 'POST') {
                $form->bindRequest($request);
                if ($form->isValid()) {
                    $data = $form->getData();

                    $member = $app['session']->get('member');
                    $user = $app['session']->get('user');

                    $app['db']->beginTransaction();

                    try {
                        if ('' !== $data['member']['password']) {
                            $data['member']['password'] = $app['utils']->hash($data['member']['password']);
                            $app['members']->update($data['member'], array('id' => (int) $member['id']));
                        }

                        $app['users']->update($data['user'], array('id' => (int) $user['id']));
                        // Update session
                        foreach ($data['user'] as $key => $value) {
                            $user[$key] = $value;
                        }
                        $app['session']->set('user', $user);

                        $app['db']->commit();
                    } catch (\Exception $e) {
                        try {
                            $app['db']->rollback();
                        } catch (\Exception $e) {
                        }
                        $app->abort(500, 'Impossible de modifier votre profil. Merci de réessayer plus tard.');
                    }

                    $app['session']->setFlash('success', 'Votre compte a été modifié avec succès.');
                }
                else
                    $app['session']->setFlash('error', 'Quelque chose n\'est pas valide');

                return $app->redirect($app['url_generator']->generate('_editmember'));
            }

            return $app['twig']->render('member/edit.html.twig', array(
                'form' => $form->createView(),
            ));
        })
        ->bind('_editmember')
        ->method('GET|POST');
        // *******

        return $controllers;
    }
}
