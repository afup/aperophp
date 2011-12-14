<?php

/**
 * AperoUser form base class.
 *
 * @method AperoUser getObject() Returns the current form's model object
 *
 * @package    aperosymfony
 * @subpackage form
 * @author     Benjamin Laugueux <b.laugueux@yzalis.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseAperoUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'apero_id' => new sfWidgetFormInputHidden(),
      'user_id'  => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'apero_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('apero_id')), 'empty_value' => $this->getObject()->get('apero_id'), 'required' => false)),
      'user_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('user_id')), 'empty_value' => $this->getObject()->get('user_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('apero_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'AperoUser';
  }

}
