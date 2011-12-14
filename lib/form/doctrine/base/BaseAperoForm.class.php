<?php

/**
 * Apero form base class.
 *
 * @method Apero getObject() Returns the current form's model object
 *
 * @package    aperosymfony
 * @subpackage form
 * @author     Benjamin Laugueux <b.laugueux@yzalis.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseAperoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'description'      => new sfWidgetFormTextarea(),
      'location_name'    => new sfWidgetFormInputText(),
      'location_address' => new sfWidgetFormInputText(),
      'location_city'    => new sfWidgetFormInputText(),
      'location_zipcode' => new sfWidgetFormInputText(),
      'date_at'          => new sfWidgetFormDate(),
      'time_at'          => new sfWidgetFormTime(),
      'price'            => new sfWidgetFormInputText(),
      'max_people'       => new sfWidgetFormInputText(),
      'is_active'        => new sfWidgetFormInputCheckbox(),
      'slug'             => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'description'      => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'location_name'    => new sfValidatorString(array('max_length' => 255)),
      'location_address' => new sfValidatorString(array('max_length' => 255)),
      'location_city'    => new sfValidatorString(array('max_length' => 255)),
      'location_zipcode' => new sfValidatorString(array('max_length' => 255)),
      'date_at'          => new sfValidatorDate(),
      'time_at'          => new sfValidatorTime(),
      'price'            => new sfValidatorNumber(array('required' => false)),
      'max_people'       => new sfValidatorInteger(array('required' => false)),
      'is_active'        => new sfValidatorBoolean(array('required' => false)),
      'slug'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Apero', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('apero[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Apero';
  }

}
