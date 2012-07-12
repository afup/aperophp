<?php

namespace Aperophp\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
                $app->abort(404, 'Cet apéro n\'existe pas.');
            }

            $form = $app['form.factory']->create('drink_comment');

            $form->bindRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                // If user is not authenticated, a user is created.
                if (!$app['session']->has('user')) {
                    $app['users']->insert($data['user']);
                    $data['user']['id'] = $app['users']->lastInsertId();
                    $app['session']->set('user', $data['user']);
                }

                $user = $app['session']->get('user');

                $app['drink_comments']->insert(array(
                    'content'    => $data['content'],
                    'user_id'    => $user['id'],
                    'drink_id'   => $drinkId,
                    'created_at' => date('c'),
                ));

                $app['session']->setFlash('success', 'Votre commentaire a été posté avec succès.');

                return $request->isXmlHttpRequest() ? 'redirect' : $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drinkId)));
            }

            return $app['twig']->render('comment/new.html.twig', array(
                'form' => $form->createView(),
                'drink' => $drink,
            ));
        })->bind('_createcomment');
        // *******

        return $controllers;
    }
}
