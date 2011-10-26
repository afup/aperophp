<?php

/**
 * Profile filter form base class.
 *
 * @package    aperosymfony
 * @subpackage filter
 * @author     Benjamin Laugueux <b.laugueux@yzalis.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseProfileFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'sf_guard_user_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'facebook'         => new sfWidgetFormFilterInput(),
      'twitter'          => new sfWidgetFormFilterInput(),
      'mobile_phone'     => new sfWidgetFormFilterInput(),
      'description'      => new sfWidgetFormFilterInput(),
      'company'          => new sfWidgetFormFilterInput(),
      'function'         => new sfWidgetFormFilterInput(),
      'website'          => new sfWidgetFormFilterInput(),
      'avatar'           => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'sf_guard_user_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'facebook'         => new sfValidatorPass(array('required' => false)),
      'twitter'          => new sfValidatorPass(array('required' => false)),
      'mobile_phone'     => new sfValidatorPass(array('required' => false)),
      'description'      => new sfValidatorPass(array('required' => false)),
      'company'          => new sfValidatorPass(array('required' => false)),
      'function'         => new sfValidatorPass(array('required' => false)),
      'website'          => new sfValidatorPass(array('required' => false)),
      'avatar'           => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Profile';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'sf_guard_user_id' => 'ForeignKey',
      'facebook'         => 'Text',
      'twitter'          => 'Text',
      'mobile_phone'     => 'Text',
      'description'      => 'Text',
      'company'          => 'Text',
      'function'         => 'Text',
      'website'          => 'Text',
      'avatar'           => 'Text',
    );
  }
}
