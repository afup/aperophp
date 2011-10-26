<?php

/**
 * page actions.
 *
 * @package    aperosymfony
 * @subpackage page
 * @author     Benjamin Laugueux <b.laugueux@yzalis.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class pageActions extends sfActions
{
  public function executeError404(sfWebRequest $request)
  {
    $this->redirect('@homepage');
  }

  public function executeAperoExportIcs(sfWebRequest $request)
  {
    $apero = $this->getRoute()->getObject();

    $this->forward404If(!$apero->getIsActive());

    $r = $this->getResponse();
    $r->clearHttpHeaders();
    $now = gmdate('D, d M Y H:i:s').' GMT';
    $r->setHttpHeader('Expires', '0');
    $r->setHttpHeader('Pragma', '');
    $r->setHttpHeader('Cache-Control', 'public');
    $r->setHttpHeader('Content-Length', strlen($apero->getIcs()));
    $r->setContentType('text/Calendar');
    $r->setHttpHeader('Content-Transfer-Encoding', 'binary');
    $r->setHttpHeader('Content-Disposition', 'attachment; filename="'.date('Y-m-d_H-m-s').'_apero-symfony.ics"');
    $r->send();

    echo $apero->getIcs();

    return sfView::NONE;
  }
  
  public function executeAperoExportVcs(sfWebRequest $request)
  {
    $apero = $this->getRoute()->getObject();

    $this->forward404If(!$apero->getIsActive());

    $r = $this->getResponse();
    $r->clearHttpHeaders();
    $now = gmdate('D, d M Y H:i:s').' GMT';
    $r->setHttpHeader('Expires', '0');
    $r->setHttpHeader('Pragma', '');
    $r->setHttpHeader('Cache-Control', 'public');
    $r->setHttpHeader('Content-Length', strlen($apero->getIcs()));
    $r->setContentType('text/x-vCalendar');
    $r->setHttpHeader('Content-Transfer-Encoding', 'binary');
    $r->setHttpHeader('Content-Disposition', 'attachment; filename="'.date('Y-m-d_H-m-s').'_apero-symfony.vcs"');
    $r->send();

    echo $apero->getVcs();

    return sfView::NONE;
  }

  public function executeAperoExportGcal(sfWebRequest $request)
  {
    $apero = $this->getRoute()->getObject();

    $this->redirect($apero->getGcal());
  }

  public function executeComingSoon(sfWebRequest $request)
  {
    $this->Form = new Newsletter();

    if ($request->isMethod('POST'))
    {
      $this->Form->bind($request->getParameter($this->Form->getName()));
      if ($this->Form->isValid())
      {
        try
        {
          # Create new instance of the API class
          $api = new MailingReport('761850dde81e5924817c28e92cbccc73');
          $api->setFormat('json');
          
          # Specify paramaters
          $params = array(
              'email' => $this->Form->getValue('email'),
              'lists' => array(
                  'e682351b96',
              ),
          );

          # Run command
          $result = $api->ContactsCreate($params);
          $result = json_decode($result);
          if (isset($result->result->code) && $result->result->code == '153')
          {
            $message = 'Soyez patient, vous êtes déjà inscrit !';
          }
          else
          {
            $message = 'Vous avez été inscrit avec succès. Merci !';
          }
        }
        catch(Exception $e)
        {
          $this->getMailer()->composeAndSend('bureau@afup.org', 'bureau@afup.org', 'Erreur sur l\'API', $e);
        }
        
        $this->getUser()->setFlash('notice', $message);
        $this->redirect('@comingsoon');
      }
    }
  }

  public function executeHomepage(sfWebRequest $request)
  {
    /*if (!in_array($request->getRemoteAddress(), sfConfig::get('app_security_authorized_ip')))
    {
      $this->redirect('@comingsoon');
    }*/

    $this->comingAperos = Doctrine_Core::getTable('Apero')->getComingAperos();
    $this->passedAperos = Doctrine_Core::getTable('Apero')->getPassedAperos();
  }

  public function executeApero(sfWebRequest $request)
  {
    $this->apero = $this->getRoute()->getObject();
  }

  public function executeAperoRegister(sfWebRequest $request)
  {
    $apero = $this->getRoute()->getObject();
    $user = $this->getUser()->getGuardUser();

    if ($apero->isRegister($user))
    {
      $this->getUser()->setFlash('notice', "Vous êtes déjà inscrit à cet apéro !");
      $this->redirect('apero', $apero);
    }

    $apero->register($user);

    $this->getMailer()->send($apero->getRegisterMessage($this->getUser()->getGuardUser()));

    $this->getUser()->setFlash('notice', "Vous êtes bien inscrit. Merci de votre participation");
    $this->redirect('apero', $apero);
  }

  public function executeAperoUnsubscribe(sfWebRequest $request)
  {
    $apero = $this->getRoute()->getObject();
    $user = $this->getUser()->getGuardUser();

    if ($apero->isRegister($user))
    {
      $apero->unsubscribe($user);

      $this->getMailer()->send($apero->getUnsubscribeMessage($this->getUser()->getGuardUser()));

      $this->getUser()->setFlash('notice', "Vous n'êtes plus inscrit à cet apéro. Nous espérons vous voir au prochain !");
      $this->redirect('apero', $apero);
    }

    $this->getUser()->setFlash('notice', "Vous n'êtes pas encore inscrit. Rejoignez-nous !");
    $this->redirect('apero', $apero);
  }

  public function executeSignUp(sfWebRequest $request)
  {
    $this->form = new SignUpForm();
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter($this->form->getName()));
      if ($this->form->isValid())
      {
        $user = $this->form->save();

        try
        {
          $api = new MailingReport('761850dde81e5924817c28e92cbccc73');
          $api->setFormat('json');

          $params = array(
              'email' => $user->getProfile()->getEmail(),
              'lists' => array(
                  'e682351b96',
              ),
          );

          $result = $api->ContactsCreate($params);
        }
        catch(Exception $e)
        {
          $this->getMailer()->composeAndSend('bureau@afup.org', 'bureau@afup.org', 'Erreur sur l\'API', $e);
        }
        
        $this->getMailer()->send($user->getProfile()->getWelcomeMessage($this->form->getValue('password')));

        $this->getUser()->signin($user);

        $this->getUser()->setFlash('notice', 'Merci pour votre inscription');
        return $this->redirect('@homepage');
      }
    }
  }

  public function executeAccount(sfWebRequest $request)
  {
    $this->redirect('@account_aperos');
  }

  public function executeAccountAperos(sfWebRequest $request)
  {
    $this->comingAperos = $this->getUser()->getProfile()->getComingAperos();
    $this->passedAperos = $this->getUser()->getProfile()->getPassedAperos();
  }

  public function executeAccountInformations(sfWebRequest $request)
  {
    $this->form = new AccountForm($this->getUser()->getGuardUser());
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
      if ($this->form->isValid())
      {
        $user = $this->form->save();
        try
        {
          $api = new MailingReport('761850dde81e5924817c28e92cbccc73');
          $api->setFormat('json');

          $params = array(
              'email' => $user->getProfile()->getEmail(),
              'firstName' => $user->getProfile()->getFirstName(),
              'lastName' => $user->getProfile()->getLastName(),
              'lists' => array(
                  'e682351b96',
              ),
              'customFields' => array(
                '7af12fd123' => $user->getProfile()->getCompany(),
                '15da07b4ae' => $user->getProfile()->getWebsite()
              )
          );

          $result = $api->ContactsUpdate($params);
        }
        catch(Exception $e)
        {
          $this->getMailer()->composeAndSend('bureau@afup.org', 'bureau@afup.org', 'Erreur sur l\'API', $e);
        }

        $this->getUser()->setFlash('notice', 'Vos informations ont bien été mises à jour.');
        return $this->redirect('@account_informations');
      }
    }
  }


  public function executeAccountPassword(sfWebRequest $request)
  {
    $this->form = new PasswordForm($this->getUser()->getGuardUser());
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
      if ($this->form->isValid())
      {
        $user = $this->form->save();

        $this->getUser()->setFlash('notice', 'Votre mot de passe a bien été mis à jour.');
        return $this->redirect('@account_password');
      }
    }
  }

  public function executeContact(sfWebRequest $request)
  {
  }

  public function executeConcept(sfWebRequest $request)
  {
  }
}
