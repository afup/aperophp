<?php

class PasswordForm extends sfGuardUserAdminForm
{
  public function configure()
  {
    parent::configure();

    $this->useFields(array('password', 'password_again'));
  }
}
