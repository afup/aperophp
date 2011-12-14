<?php

require_once(sfConfig::get('sf_plugins_dir').'/sfDoctrineGuardPlugin/modules/sfGuardAuth/lib/BasesfGuardAuthActions.class.php');

class sfGuardAuthActions extends BasesfGuardAuthActions
{
  public function executeSignin($request)
  {
    return parent::executeSignin($request);
    if ($request->isMethod('POST'))
    {
      parent::executeSignin($request);
    }
    else
    {

    }
  }
}
