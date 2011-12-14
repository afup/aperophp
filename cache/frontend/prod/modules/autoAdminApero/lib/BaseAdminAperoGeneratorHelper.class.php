<?php

/**
 * adminApero module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage adminApero
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: helper.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseAdminAperoGeneratorHelper extends sfModelGeneratorHelper
{
  public function getUrlForAction($action)
  {
    return 'list' == $action ? 'apero_adminApero' : 'apero_adminApero_'.$action;
  }
}
