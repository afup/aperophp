<?php

namespace Aperophp\Provider\Controller;

use Aperophp\Model;
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
        $controllers = new ControllerCollection();

        // *******
        // ** Add a comment
        // *******
        $controllers->post('{drink_id}/create.html', function(Request $request, $drink_id) use ($app)
        {
            $oDrink = Model\Drink::findOneById($app['db'], $drink_id);

            if (!$oDrink)
            {
                $app->abort(404, 'Cet apéro n\'existe pas.');
            }

            $oUser = null;
            $values = array();
            if ($user = $app['session']->get('user'))
            {
                $oUser = Model\User::findOneById($app['db'], $user['id']);
            }

            $form = $app['form.factory']->create(new \Aperophp\Form\DrinkCommentType(), null, array('user' => $oUser));

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
                    if (!$oUser)
                    {
                        $oUser = new Model\User($app['db']);
                        $oUser
                            ->setEmail($data['email'])
                            ->setFirstname($data['firstname'])
                            ->setLastname($data['lastname'])
                            ->save();
                    }

                    // Save user's comment on this Drink.
                    $oDrinkComment = new Model\DrinkComment($app['db']);
                    $oDrinkComment
                        ->setContent($data['content'])
                        ->setCreatedAt(date('c'))
                        ->setUserId($oUser->getId())
                        ->setDrinkId($drink_id)
                        ->save();

                    $app['db']->commit();
                }
                catch (Exception $e)
                {
                    $app['db']->rollback();
                    throw $e;
                }

                return $request->isXmlHttpRequest() ? 'redirect' : $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $drink_id)));
            }

            return $app['twig']->render('comment/new.html.twig', array(
                'form' => $form->createView(),
                'drink' => $oDrink,
            ));
        })->bind('_createcomment');
        // *******

        return $controllers;
    }
}
