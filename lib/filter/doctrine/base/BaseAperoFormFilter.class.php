<?php

/**
 * Apero filter form base class.
 *
 * @package    aperosymfony
 * @subpackage filter
 * @author     Benjamin Laugueux <b.laugueux@yzalis.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseAperoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'description'      => new sfWidgetFormFilterInput(),
      'location_name'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'location_address' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'location_city'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'location_zipcode' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'date_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'time_at'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'price'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'max_people'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_active'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'slug'             => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'description'      => new sfValidatorPass(array('required' => false)),
      'location_name'    => new sfValidatorPass(array('required' => false)),
      'location_address' => new sfValidatorPass(array('required' => false)),
      'location_city'    => new sfValidatorPass(array('required' => false)),
      'location_zipcode' => new sfValidatorPass(array('required' => false)),
      'date_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'time_at'          => new sfValidatorPass(array('required' => false)),
      'price'            => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'max_people'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_active'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'slug'             => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('apero_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Apero';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'description'      => 'Text',
      'location_name'    => 'Text',
      'location_address' => 'Text',
      'location_city'    => 'Text',
      'location_zipcode' => 'Text',
      'date_at'          => 'Date',
      'time_at'          => 'Text',
      'price'            => 'Number',
      'max_people'       => 'Number',
      'is_active'        => 'Boolean',
      'slug'             => 'Text',
    );
  }
}
