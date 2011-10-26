<?php

class AccountForm extends sfGuardUserAdminForm
{
  public function configure()
  {
    parent::configure();

    $this->useFields(array('first_name', 'last_name', 'email_address'));

    $this->widgetSchema['first_name']->setAttribute('size', '35');

    $this->widgetSchema['last_name']->setAttribute('size', '35');

    $this->widgetSchema['email_address']->setAttribute('size', '35');

    $profileForm = new ProfileForm($this->object->getProfile());
    unset($profileForm['id'], $profileForm['sf_guard_user_id']);
    $this->mergeEmbedForm('Profile', $profileForm);
  }
}
