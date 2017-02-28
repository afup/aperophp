<?php

namespace Aperophp\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;

/**
 * Drink controller
 */
class Drink implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        // *******
        // ** Homepage
        // *******
        $controllers->get('/', function() use ($app)
        {
            $app['session']->set('menu', 'home');

            $drinks = $app['drinks']->findNext(14);

            return $app['twig']->render('drink/index.html.twig', array(
                'drinks' => $drinks
            ));
        })->bind('_homepagedrinks');
        // *******

        // *******
        // ** List
        // *******
        $controllers->get('list.{format}', function($format) use ($app)
        {
            $drinks = $app['drinks']->findAll(25);
            if ($format == 'html') {
                //TODO pagination
                $app['session']->set('menu', 'listdrinks');
                return $app['twig']->render('drink/list.html.twig', array(
                    'drinks' => $drinks
                ));
            }

            $exporter = new \Aperophp\Lib\FeedExporter();
            return $exporter->export($drinks);
        })
        ->assert('format', 'html|atom')
        ->bind('_listdrinks');
        // *******

        // *******
        // ** Add a drink
        // *******
        $controllers->match('new.html', function(Request $request) use ($app)
        {
            if (!$app['session']->has('member')) {
                $app['session']->getFlashBag()->add('error', 'Vous devez être authentifié pour créer un événement.');

                return $app->redirect($app['url_generator']->generate('_signinmember'));
            }

            $app['session']->set('menu', 'newdrink');

            $form = $app['form.factory']->create('drink');

            if ('POST' === $request->getMethod()) {
                $form->bind($request->request->get('drink'));
                if ($form->isValid()) {
                    $data = $form->getData();

                    $member = $app['session']->get('member');
                    $data['member_id'] = $member['id'];
                    unset($data['captcha']);

                    try {
                        $app['drinks']->insert($data);
                    } catch (\Exception $e) {
                        $app->abort(500, 'Impossible de créer l\'événement. Merci de réessayer plus tard.');
                    }

                    $app['session']->getFlashBag()->add('success', 'L\'événement a été créé avec succès.');

                    return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $app['drinks']->lastInsertId())));
                }
            }

            return $app['twig']->render('drink/new.html.twig', array(
                'form' => $form->createView(),
            ));
        })
        ->bind('_newdrink')
        ->method('GET|POST');
        // *******

        // *******
        // ** Edit a drink
        // *******
        $controllers->get('{id}/edit.html', function(Request $request, $id) use ($app)
        {
            $app['session']->set('menu', null);

            $drink = $app['drinks']->find($id);

            if (!$drink)
                $app->abort(404, 'Cet événement n\'existe pas.');

            $now = new \Datetime('now');
            $dDrink = \Datetime::createFromFormat('Y-m-d H:i:s', $drink['day'] . ' ' . $drink['hour']);
            if ($now > $dDrink) {
                $app['session']->getFlashBag()->add('error', 'L\'événement est terminé.');

                return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $id)));
            }

            $member = $app['session']->get('member');

            if (!$member) {
                $app['session']->getFlashBag()->add('error', 'Vous devez être authentifié pour pouvoir éditer cet événement.');

                return $app->redirect($app['url_generator']->generate('_signinmember'));
            }

            if ($drink['member_id'] != $member['id']) {
                $app['session']->getFlashBag()->add('error', 'Vous devez être organisateur de cet événement pour pouvoir l\'éditer.');

                return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $id)));
            }

            $form = $app['form.factory']->create('drink', $drink);

            if ('POST' === $request->getMethod()) {
                $form->bind($request->request->get('drink'));
                if ($form->isValid()) {
                    $data = $form->getData();

                    $data['member_id'] = $member['id'];
                    unset($data['captcha']);

                    try {
                        $app['drinks']->update($data, array('id' => $drink['id']));
                    } catch (\Exception $e) {
                        $app->abort(500, 'Impossible de modifier l\'événement. Merci de réessayer plus tard.');
                    }
                    $app['session']->getFlashBag()->add('success', 'L\'événement a été modifié avec succès.');

                    return $app->redirect($app['url_generator']->generate('_showdrink', array('id' => $id)));
                }
                else
                {
                    $app['session']->getFlashBag()->add('error', 'Il y a des erreurs dans le formulaire.');
                    return $app->redirect($app['url_generator']->generate('_editdrink', array('id' => $id)));
                }
            }

            return $app['twig']->render('drink/edit.html.twig', array(
                'form' => $form->createView(),
                'id'   => $id,
            ));
        })
        ->bind('_editdrink')
        ->method('GET|POST');
        // *******

        // *******
        // ** See a drink
        // *******
        $controllers->get('{id}/view.html/{email}/{token}', function($id) use ($app)
        {
            $app['session']->set('menu', null);

            $drink = $app['drinks']->find($id);

            if (!$drink) {
                $app->abort(404, 'Cet événement n\'existe pas.');
            }

            $member = $app['session']->get('member');
            $hideSpam = !($member && $member['id'] == $drink['member_id']);

            $participants = $app['drink_participants']->findByDrinkId($drink['id']);
            $presences = $app['drink_participants']->findAllPresencesInAssociativeArray();
            $comments = $app['drink_comments']->findByDrinkId($drink['id'], $hideSpam);

            $textProcessor = new \Michelf\Markdown();
            $textProcessor->no_markup = true;
            $textProcessor->no_entities = true;

            foreach ($comments as &$comment)
                $comment['content'] = str_replace('href="javascript:', 'href="', substr($textProcessor->transform($comment['content']), 3, -5));

            $user = $app['session']->get('user');

            // First, deal with participation
            $isParticipating = false;
            $data = array();
            if (null !== $user) {
                $data = array('user' => $user);
                if (false !== $participation = $app['drink_participants']->findOne($id, $user['id'])) {
                    $data += $participation;
                    $isParticipating = true;
                }
            }

            // Avoid transformer error
            $data['reminder'] = (boolean) array_key_exists('reminder', $data)? (boolean) $data['reminder'] : false;
            $participationForm = $app['form.factory']->create('drink_participate', $data);


            // If member is authenticated, prefill form.
            $data = array();
            if (null !== $user) {
                $data = array('user' => $user);
            }

            $commentForm = $app['form.factory']->create('drink_comment', $data);

            $now = new \Datetime('now');
            $dDrink = \Datetime::createFromFormat('Y-m-d H:i:s', $drink['day'] . ' ' . $drink['hour']);

            if (null === $drink['meetup_com_id']) {
                $drink['description'] = str_replace('href="javascript:', 'href="', substr($textProcessor->transform($drink['description']), 3, -5));
            }

            return $app['twig']->render('drink/view.html.twig', array(
                'drink'             => $drink,
                'participants'      => $participants,
                'comments'          => $comments,
                'commentForm'       => $commentForm->createView(),
                'participationForm' => $participationForm->createView(),
                'isFinished'        => $now > $dDrink,
                'isParticipating'   => $isParticipating,
                'isConnected'       => null !== $user,
                'presences'         => $presences,
            ));
        })
        ->value('email', null)
        ->value('token', null)
        ->bind('_showdrink');
        // *******

        return $controllers;
    }
}
