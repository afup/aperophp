<?php

class SignUpForm extends sfGuardUserAdminForm
{
  public function configure()
  {
    parent::configure();

    $this->useFields(array('first_name', 'last_name', 'email_address', 'password', 'password_again'));

    $this->validatorSchema['password']->setOption('required', true);

  }
}
