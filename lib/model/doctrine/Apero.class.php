<?php

/**
 * Apero
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    aperosymfony
 * @subpackage model
 * @author     Benjamin Laugueux <b.laugueux@yzalis.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Apero extends BaseApero
{
  public function isRegister(sfGuardUser $user)
  {
    if (Doctrine::getTable('AperoUser')->findOneByAperoIdAndUserId($this->getId(), $user->getId()))
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  public function getRegisterMessage(sfGuardUser $user)
  {
    $message = Swift_Message::newInstance()
      ->setFrom(array('contact@apero-symfony.com' => 'Apéro symfony'))
      ->setTo($user->getProfile()->getEmail())
      ->setSubject('Inscription à un apéro symfony')
      ->setBody(
<<<EOF
Bonjour {$user->getProfile()->getFirstName()},

Tu as bien été inscrit à l'apéro : {$this->getLocationCity()}, {$this->getLocationName()} le {$this->getDateAt()} à {$this->getTimeAt()}.
N'oublie pas de noter cette date dans ton agenda.

A bientôt,

Benjamin
www.apero-symfony.com
EOF
      )
    ;
    return $message;
  }

  public function getUnsubscribeMessage(sfGuardUser $user)
  {

    $message = Swift_Message::newInstance()
      ->setFrom(array('contact@apero-symfony.com' => 'Apéro symfony'))
      ->setTo($user->getProfile()->getEmail())
      ->setSubject('Désinscription d\'un apéro symfony')
      ->setBody(
<<<EOF
Bonjour {$user->getFirstName()},

Tu as bien été désincrit de l'apéro {$this->getLocationCity()}, {$this->getLocationName()}.

A bientôt,

Benjamin
www.apero-symfony.com
EOF
      )
    ;
  return $message;
  }

  public function getIcs()
  {
    $ics = "BEGIN:VCALENDAR\n";
    $ics .= "VERSION:2.0\n";
    $ics .= "PRODID:PHP\n";
    $ics .= "BEGIN:VEVENT\n";
    $ics .= "DTSTART:".gmdate("Ymd\THis\Z", strtotime($this->getDateAt().' '.$this->getTimeAt()))."\n";
    $ics .= "DTEND:".gmdate("Ymd\THis\Z", strtotime($this->getDateAt().' '.$this->getTimeAt().' +3 hours'))."\n";
    $ics .= "DESCRIPTION:\n";
    $ics .= "SUMMARY:Apero symfony\n";
    $ics .= "LOCATION:".$this->getLocationName()." - ".$this->getAddress()."\n";
    $ics .= "STATUS:CONFIRMED\n";
    $ics .= "UID:".$this->getId()."\n";
    $ics .= "DTSTAMP:".date("Ymd\THis\Z")."\n";
    $ics .= "END:VEVENT\n";
    $ics .= "END:VCALENDAR\n";

    return $ics;
  }

  public function getVcs()
  {
    $vcs = "BEGIN:VCALENDAR\n";
    $vcs .= "VERSION:1.0\n";
    $vcs .= "PRODID:PHP\n";
    $vcs .= "BEGIN:VEVENT\n";
    $vcs .= "UID:".$this->getId()."\n";
    $vcs .= "SUMMARY:Apero symfony\n";
    $vcs .= "DTSTART:".gmdate("Ymd\THis\Z", strtotime($this->getDateAt().' '.$this->getTimeAt()))."\n";
    $vcs .= "DTEND:".gmdate("Ymd\THis\Z", strtotime($this->getDateAt().' '.$this->getTimeAt().' +3 hours'))."\n";
    $vcs .= "LOCATION:".$this->getLocationName()." - ".$this->getAddress()."\n";
    $vcs .= "END:VEVENT\n";
    $vcs .= "END:VCALENDAR\n";

    return $vcs;
  }

  public function getGcal()
  {
    $gcal = 'http://www.google.com/calendar/event?action=TEMPLATE';
    $gcal .= '&text=Apero symfony';
    $gcal .= '&dates='.gmdate("Ymd\THis\Z", strtotime($this->getDateAt().' '.$this->getTimeAt())).'/'.gmdate("Ymd\THis\Z", strtotime($this->getDateAt().' '.$this->getTimeAt().' +3 hours'));
    $gcal .= '&sprop=website:www.apero-symfony.com.com&sprop;=name:Apéro symfony';
    $gcal .= '&location='.$this->getLocationName()." - ".$this->getAddress();
    $gcal .= '&trp=true';

    return $gcal;
  }

  public function getNbRegister()
  {
    return count($this->getAperoUser());
  }

  public function register(sfGuardUser $user)
  {
    $aperoUser = new AperoUser();
    $aperoUser->setSfGuardUser($user);
    $aperoUser->setApero($this);
    $aperoUser->save();
  }

  public function unsubscribe(sfGuardUser $user)
  {
    $aperoUser = Doctrine::getTable('AperoUser')->findOneByAperoIdAndUserId($this->getId(), $user->getId());
    $aperoUser->delete();
  }

  public function getAddress()
  {
    return $this->getLocationAddress().' '.$this->getLocationZipcode().' '.$this->getLocationCity();
  }

  public function getYear()
  {
    return date('Y', strtotime($this->getDateAt()));
  }

  public function getMonth()
  {
    return date('m', strtotime($this->getDateAt()));
  }

  public function getDay()
  {
    return date('j', strtotime($this->getDateAt()));
  }
}