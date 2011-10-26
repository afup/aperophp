<?php

/**
 * SfGuardUserProfile filter form base class.
 *
 * @package    aperosymfony
 * @subpackage filter
 * @author     Benjamin Laugueux <b.laugueux@yzalis.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSfGuardUserProfileFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'website' => new sfWidgetFormFilterInput(),
      'company' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'website' => new sfValidatorPass(array('required' => false)),
      'company' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_guard_user_profile_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SfGuardUserProfile';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'user_id' => 'ForeignKey',
      'website' => 'Text',
      'company' => 'Text',
    );
  }
}
