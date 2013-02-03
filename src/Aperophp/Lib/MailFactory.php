<?php
namespace Aperophp\Lib;

class MailFactory
{

    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function createParticipation($user, $drink)
    {
        $dDrink = \Datetime::createFromFormat('Y-m-d H:i:s', $drink['day'] . ' ' . $drink['hour']);

        $dEndDrink = clone $dDrink;
        $dEndDrink->modify('+3 hours');
        $dateFormat = 'Ymd\THis';


        $icsInvite = \Swift_Attachment::newInstance()
          ->setContentType('text/calendar;charset=UTF-8;method=REQUEST')
          ->setBody($this->app['twig']->render('drink/invite_ics.twig', array(
            'user'      => $user,
            'drink'     => $drink,
            'datetimes' => array(
              'start'   => $dDrink->format($dateFormat),
              'end'     => $dEndDrink->format($dateFormat),
              'current' => date($dateFormat),
            ),
          )))
          ->setEncoder(\Swift_Encoding::getQpEncoding())
        ;

        return $this->app['mailer']
            ->createMessage()
            ->setSubject('[Aperophp.net] Inscription à un '.$drink['kind'])
            ->setFrom(array('noreply@aperophp.net'))
            ->setTo(array($user['email']))
            ->setBody($this->app['twig']->render('drink/participation_mail.html.twig', array(
                'user'  => $user,
                'drink' => $drink
            )), 'text/html')
            ->attach($icsInvite)
        ;
    }

}

