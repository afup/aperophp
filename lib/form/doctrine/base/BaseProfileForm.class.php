<?php

/**
 * Profile form base class.
 *
 * @method Profile getObject() Returns the current form's model object
 *
 * @package    aperosymfony
 * @subpackage form
 * @author     Benjamin Laugueux <b.laugueux@yzalis.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseProfileForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'sf_guard_user_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'facebook'         => new sfWidgetFormInputText(),
      'twitter'          => new sfWidgetFormInputText(),
      'mobile_phone'     => new sfWidgetFormInputText(),
      'description'      => new sfWidgetFormTextarea(),
      'company'          => new sfWidgetFormInputText(),
      'function'         => new sfWidgetFormInputText(),
      'website'          => new sfWidgetFormInputText(),
      'avatar'           => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'sf_guard_user_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'facebook'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'twitter'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'mobile_phone'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'      => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'company'          => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'function'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'website'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'avatar'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Profile';
  }

}
