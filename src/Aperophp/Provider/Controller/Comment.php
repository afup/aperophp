<?php

namespace Aperophp\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Comment controller.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 18 févr. 2012
 * @version 1.0 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
 */
class Comment implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        // *******
        // ** Add a comment
        // *******
        $controllers->post('{drinkId}/create.html', function(Request $request, $drinkId) use ($app)
        {
            $drink = $app['drinks']->find($drinkId);

            if (!$drink) {
                $app->abort(404, 'Cet événement n\'existe pas.');
            }

            $form = $app['form.factory']->create('drink_comment');

            $form->bind($request->request->get('drink_comment'));
            if ($form->isValid()) {
                $data = $form->getData();

                // If user is not authenticated, a user is created.
                if (!$app['session']->has('user')) {
                    try {
                        $app['users']->insert($data['user']);
                        $data['user']['id'] = $app['users']->lastInsertId();
                        $app['session']->set('user', $data['user']);
                    } catch (\Exception $e) {
                        $app->abort(500, 'Impossible de sauvegarder vos identifiants. Merci de réessayer plus tard.');
                    }
                }

                $user = $app['session']->get('user');

                $authorName =  $user['lastname'];
                $email = $user['email'];
                $isSpam = $app['akismet']->isSpam($data['content'], $authorName, $email, null, null, 'comment');

                try {
                    $app['drink_comments']->insert(array(
                        'content'    => $data['content'],
                        'user_id'    => $user['id'],
                        'drink_id'   => $drinkId,
                        'is_spam'    => $isSpam,
                    ));
                } catch (\Exception $e) {
                    $app->abort(500, 'Impossible de sauvegarder votre commentaire. Merci de réessayer plus tard.');
                }

                $app['session']->getFlashBag()->add('success', 'Votre commentaire a été posté avec succès.');

                return $request->isXmlHttpRequest() ? 'redirect' : $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drinkId)));
            }

            return $app['twig']->render('comment/_new.html.twig', array(
                'commentForm' => $form->createView(),
                'drink' => $drink,
            ));
        })->bind('_createcomment');

        $controllers->get('{drinkId}/comment/{commentId}/toggle_spam.html', function(Request $request, $drinkId, $commentId) use ($app) {
                $drinkComment = $app['drink_comments']->find($commentId);
                if (null == $drinkComment) {
                    $app->abort(500, 'Commentaire non existant.');
                }
                $drink = $app['drinks']->find($drinkId);
                if (null == $drink) {
                    $app->abort(500, 'Drink non existant.');
                }
                $member = $app['session']->get('member');
                if (!$member) {
                    $app['session']->getFlashBag()->add('error', 'Vous devez être authentifié pour pouvoir éditer cet événement.');

                    return $app->redirect($app['url_generator']->generate('_signinmember'));
                }

                if ($drink['member_id'] != $member['id']) {
                    $app['session']->getFlashBag()->add('error', "Vous devez être organisateur de cet événement pour pouvoir modifier l'état des commentaires.");

                    return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drinkId)));
                }
                $drinkComment['is_spam'] = !$drinkComment['is_spam'];
                $app['drink_comments']->update($drinkComment, array('id' => $commentId));
                return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drinkId)));
        })->bind('_comment_toggle_is_spam');
        // *******

        return $controllers;
    }

}
