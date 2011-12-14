<?php

class Newsletter extends sfForm
{
  public function configure()
  {
    $this->widgetSchema['email'] = new sfWidgetFormInputText();
    $this->validatorSchema['email'] = new sfValidatorEmail(array('required' => true), array('required' => 'Merci de renseigner votre email', 'invalid' => 'Votre email semble incorrect, merci de le vérifier'));

    $this->widgetSchema->setNameFormat('newsletter[%s]');
  }
}