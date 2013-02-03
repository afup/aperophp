<?php

namespace Aperophp\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;

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
            if ($app['session']->has('member')) {
                $app['session']->getFlashBag()->add('error', 'Vous êtes déjà authentifié.');

                return $app->redirect($app['url_generator']->generate('_homepagedrinks'));
            }

            $app['session']->set('menu', 'signin');
            $form = $app['form.factory']->create('signin');

            // If it's not POST method, just display void form
            if ('POST' === $request->getMethod()) {
                $form->bind($request->request->get('signin'));
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

                    $app['session']->getFlashBag()->add('error', 'Identifiant / Mot de passe incorrect.');
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
            if ($app['session']->has('member')) {
                $app['session']->getFlashBag()->add('error', 'Vous êtes déjà authentifié.');

                return $app->redirect($app['url_generator']->generate('_homepagedrinks'));
            }

            $app['session']->set('menu', 'signup');
            $form = $app['form.factory']->create('signup');

            // If it's not POST method, just display void form
            if ('POST' === $request->getMethod()) {
                $form->bind($request->request->get('signup'));
                if ($form->isValid()) {
                    $data = $form->getData();

                    $app['db']->beginTransaction();

                    try {
                        $data['member']['password'] = $app['utils']->hash($data['member']['password']);
                        $app['members']->insert($data['member']);
                        $data['user']['member_id'] = $app['members']->lastInsertId();
                        $app['users']->insert($data['user']);
                        $data['user']['id'] = $app['users']->lastInsertId();
                        $app['drink_participants']->groupByEmail($data['user']['email'], $data['user']['id']);
                        $app['drink_comments']->groupByEmail($data['user']['email'], $data['user']['id']);
                        $app['users']->removeUsers($data['user']['email'], $data['user']['id']);

                        $app['db']->commit();
                    } catch (\Exception $e) {
                        try {
                            $app['db']->rollback();
                        } catch (\Exception $e) {
                        }
                        $app->abort(500, 'Impossible de vous inscrire. Merci de réessayer plus tard.');
                    }

                    $app['session']->getFlashBag()->add('success', 'Votre compte a été créé avec succès.');

                    return $app->redirect($app['url_generator']->generate('_signinmember'));
                }
                // Invalid form will display form back
                $app['session']->getFlashBag()->add('error', 'Quelque chose n\'est pas valide.');
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
                $app['session']->getFlashBag()->add('error', 'Vous devez être authentifié pour accéder à cette ressource.');

                return $app->redirect($app['url_generator']->generate('_signinmember'));
            }

            $member = $app['session']->get('member');
            $user = $app['session']->get('user');

            $form = $app['form.factory']->create('member_edit', array(
                'member' => $member,
                'user'   => $user
            ));

            // If it's not POST method, just display void form
            if ('POST' === $request->getMethod()) {
                $form->bind($request->request->get('member_edit'));

                if ($form->isValid()) {
                    $data = $form->getData();

                    $member = $app['session']->get('member');
                    $user   = $app['session']->get('user');

                    $app['db']->beginTransaction();

                    try {

                        if (!is_null($data['member']['password'])) {

                            if(!$this->changePasswordUser($member, $app, $data)){
                                $app['session']->getFlashBag()->add('error', 'Votre mot de passe n\'est pas le bon');

                                return $app->redirect($app['url_generator']->generate('_editmember'));
                            }
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

                    $app['session']->getFlashBag()->add('success', 'Votre compte a été modifié avec succès.');
                } else {

                    $app['session']->getFlashBag()->add('error', 'Quelque chose n\'est pas valide.');
                }

                return $app->redirect($app['url_generator']->generate('_editmember'));
            }

            return $app['twig']->render('member/edit.html.twig', array(
                'form' => $form->createView(),
            ));
        })
        ->bind('_editmember')
        ->method('GET|POST');
        // *******

        // *******
        // ** Request a new password member
        // *******
        $controllers->match('forget.html', function(Request $request) use ($app)
        {
            if ($app['session']->has('member')) {
                $app['session']->getFlashBag()->add('error', 'Vous êtes déjà authentifié.');

                return $app->redirect($app['url_generator']->generate('_homepagedrinks'));
            }

            $form = $app['form.factory']->create('member_forget', array());

            // If it's not POST method, just display void form
            if ('POST' === $request->getMethod()) {
                $form->bind($request->request->get('member_forget'));
                if ($form->isValid()) {
                    $data = $form->getData();

                    $user = $app['users']->findOneByEmail($data['email']);
                    $member = $user && null != $user['member_id'] ? $app['members']->find($user['member_id']) : false;
                    if (!$user || !$member || !$member['active']) {
                        $app['session']->getFlashBag()->add('error', 'Aucun utilisateur ne possède cet adresse email.');

                        return $app->redirect($app['url_generator']->generate('_forgetmember'));
                    }

                    try {
                        $user['token'] = sha1(md5(rand()).microtime(true).md5(rand()));
                        $app['users']->update($user, array('id' => (int) $user['id']));

                        $app['mailer']->send($app['mailer']
                            ->createMessage()
                            ->setSubject('[Aperophp.net] Changement de mot de passe')
                            ->setFrom(array('noreply@aperophp.net'))
                            ->setTo(array($user['email']))
                            ->setBody($app['twig']->render('member/forget_mail.html.twig', array(
                                'user'   => $user,
                                'member' => $member
                            )), 'text/html')
                        );
                    } catch (\Exception $e) {
                        $app->abort(500, 'Impossible de vous rappeler votre mot de passe. Merci de réessayer plus tard.');
                    }

                    $app['session']->getFlashBag()->add('success', "Vous allez recevoir un email dans quelques instants pour changer de mot de passe.\nSi vous ne recevez pas cet email, pensez à vérifier vos indésirables.");

                    return $app->redirect($app['url_generator']->generate('_homepagedrinks'));
                }

                $app['session']->getFlashBag()->add('error', 'Quelque chose n\'est pas valide.');
            }

            return $app['twig']->render('member/forget.html.twig', array(
                'form' => $form->createView(),
            ));
        })
        ->bind('_forgetmember')
        ->method('GET|POST');

        // *******
        // ** Remember password member
        // *******
        $controllers->get('remember.html/{email}/{token}', function(Request $request, $email, $token) use ($app)
        {
            if ($app['session']->has('member')) {
                $app['session']->getFlashBag()->add('error', 'Vous êtes déjà authentifié.');

                return $app->redirect($app['url_generator']->generate('_homepagedrinks'));
            }

            if (!$user = $app['users']->findOneByEmailToken($email, $token)) {
                $app['session'] ->getFlashBag()->add('error', 'Couple email/jeton invalide.');

                return $app->redirect($app['url_generator']->generate('_homepagedrinks'));
            }

            if (!($member = $app['members']->find($user['member_id'])) || !$member['active']) {
                $app['session'] ->getFlashBag()->add('error', 'Member invalide/inactif.');

                return $app->redirect($app['url_generator']->generate('_homepagedrinks'));
            }

            $app['db']->beginTransaction();

            try {
                $password = sha1(md5(rand()).microtime(true).md5(rand()));
                $member['password'] = $app['utils']->hash($password);
                $app['members']->update($member, array('id' => (int) $member['id']));

                $user['token'] = sha1(md5(rand()).microtime(true).md5(rand()));
                $app['users']->update($user, array('id' => (int) $user['id']));

                $app['db']->commit();

                $app['mailer']->send($app['mailer']
                    ->createMessage()
                    ->setSubject('[Aperophp.net] Changement de mot de passe')
                    ->setFrom(array('noreply@aperophp.net'))
                    ->setTo(array($user['email']))
                    ->setBody($app['twig']->render('member/remember_mail.html.twig', array(
                        'password' => $password,
                        'member'   => $member
                    )), 'text/html')
                );
            } catch (\Exception $e) {
                try {
                    $app['db']->rollback();
                } catch (\Exception $e) {
                }
                $app->abort(500, 'Impossible de changer votre mot de passe. Merci de réessayer plus tard.');
            }

            $app['session'] ->getFlashBag()->add('success', 'Votre nouveau mot de passe vient de vous être envoyé. Vous pouvez vous connecter immédiatement avec celui-ci.');

            return $app->redirect($app['url_generator']->generate('_signinmember'));
        })->bind('_remembermember');
        // *******

        return $controllers;
    }

    /**
     * Change password if current password is ok
     *
     * @return boolean
     */
    private function changePasswordUser($member, $app, $data)
    {   
        if($app['members']->checkUserPassword($member['username'], $app['utils']->hash($data['member']['oldpassword'])) != 0) {
            
            $data['member']['password'] = $app['utils']->hash($data['member']['password']);
            unset($data['member']['oldpassword']);
            $app['members']->update($data['member'], array('id' => (int) $member['id']));

            return true;
        }
        
        return false;
    }
}
