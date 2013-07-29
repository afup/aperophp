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

                try {
                    $app['drink_comments']->insert(array(
                        'content'    => $data['content'],
                        'user_id'    => $user['id'],
                        'drink_id'   => $drinkId,
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
        // *******

        return $controllers;
    }
}
