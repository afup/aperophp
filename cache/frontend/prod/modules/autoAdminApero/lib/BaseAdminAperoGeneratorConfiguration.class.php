<?php

/**
 * adminApero module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage adminApero
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: configuration.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseAdminAperoGeneratorConfiguration extends sfModelGeneratorConfiguration
{
  public function getActionsDefault()
  {
    return array();
  }

  public function getFormActions()
  {
    return array(  '_delete' => NULL,  '_list' => NULL,  '_save' => NULL,  '_save_and_add' => NULL,);
  }

  public function getNewActions()
  {
    return array();
  }

  public function getEditActions()
  {
    return array();
  }

  public function getListObjectActions()
  {
    return array(  '_edit' => NULL,  '_delete' => NULL,);
  }

  public function getListActions()
  {
    return array(  '_new' => NULL,);
  }

  public function getListBatchActions()
  {
    return array();
  }

  public function getListParams()
  {
    return '<p>Le %%date_at%% à %%time_at%% : %%location_city%%, %%location_name%%</p><p>%%is_active%% %%nb_register%% participant(s) / %%max_people%% maximum (%%price%%)</p>';
  }

  public function getListLayout()
  {
    return 'stacked';
  }

  public function getListTitle()
  {
    return 'AdminApero List';
  }

  public function getEditTitle()
  {
    return 'Edit AdminApero';
  }

  public function getNewTitle()
  {
    return 'New AdminApero';
  }

  public function getFilterDisplay()
  {
    return array();
  }

  public function getFormDisplay()
  {
    return array(  'Localisation' =>   array(    0 => 'location_name',    1 => 'location_address',    2 => 'location_city',    3 => 'location_zipcode',    4 => 'max_people',  ),  'Date' =>   array(    0 => 'date_at',    1 => 'time_at',  ),  'Contenu' =>   array(    0 => 'description',    1 => 'price',  ),  'Etat' =>   array(    0 => 'is_active',  ),);
  }

  public function getEditDisplay()
  {
    return array();
  }

  public function getNewDisplay()
  {
    return array();
  }

  public function getListDisplay()
  {
    return array(  0 => 'date_at',  1 => 'time_at',  2 => 'location_city',  3 => 'location_name',  4 => 'is_active',);
  }

  public function getFieldsDefault()
  {
    return array(
      'id' => array(  'is_link' => true,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'description' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'location_name' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Lieu',),
      'location_address' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'location_city' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Ville',),
      'location_zipcode' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'date_at' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Date',  'label' => 'Date',),
      'time_at' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Time',  'label' => 'Heure',),
      'price' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Prix',),
      'max_people' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Max pers.',),
      'is_active' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Boolean',  'label' => 'Ouvert',),
      'slug' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'nb_register' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',  'label' => 'Nb participants',),
    );
  }

  public function getFieldsList()
  {
    return array(
      'id' => array(),
      'description' => array(),
      'location_name' => array(),
      'location_address' => array(),
      'location_city' => array(),
      'location_zipcode' => array(),
      'date_at' => array(  'date_format' => 'dd MMMM yyyy',),
      'time_at' => array(),
      'price' => array(  'format_currency' => 'EUR',),
      'max_people' => array(),
      'is_active' => array(),
      'slug' => array(),
    );
  }

  public function getFieldsFilter()
  {
    return array(
      'id' => array(),
      'description' => array(),
      'location_name' => array(),
      'location_address' => array(),
      'location_city' => array(),
      'location_zipcode' => array(),
      'date_at' => array(),
      'time_at' => array(),
      'price' => array(),
      'max_people' => array(),
      'is_active' => array(),
      'slug' => array(),
    );
  }

  public function getFieldsForm()
  {
    return array(
      'id' => array(),
      'description' => array(),
      'location_name' => array(),
      'location_address' => array(),
      'location_city' => array(),
      'location_zipcode' => array(),
      'date_at' => array(),
      'time_at' => array(),
      'price' => array(),
      'max_people' => array(),
      'is_active' => array(),
      'slug' => array(),
    );
  }

  public function getFieldsEdit()
  {
    return array(
      'id' => array(),
      'description' => array(),
      'location_name' => array(),
      'location_address' => array(),
      'location_city' => array(),
      'location_zipcode' => array(),
      'date_at' => array(),
      'time_at' => array(),
      'price' => array(),
      'max_people' => array(),
      'is_active' => array(),
      'slug' => array(),
    );
  }

  public function getFieldsNew()
  {
    return array(
      'id' => array(),
      'description' => array(),
      'location_name' => array(),
      'location_address' => array(),
      'location_city' => array(),
      'location_zipcode' => array(),
      'date_at' => array(),
      'time_at' => array(),
      'price' => array(),
      'max_people' => array(),
      'is_active' => array(),
      'slug' => array(),
    );
  }


  /**
   * Gets the form class name.
   *
   * @return string The form class name
   */
  public function getFormClass()
  {
    return 'AperoForm';
  }

  public function hasFilterForm()
  {
    return false;
  }

  /**
   * Gets the filter form class name
   *
   * @return string The filter form class name associated with this generator
   */
  public function getFilterFormClass()
  {
    return 'AperoFormFilter';
  }

  public function getPagerClass()
  {
    return 'sfDoctrinePager';
  }

  public function getPagerMaxPerPage()
  {
    return 20;
  }

  public function getDefaultSort()
  {
    return array(null, null);
  }

  public function getTableMethod()
  {
    return '';
  }

  public function getTableCountMethod()
  {
    return '';
  }
}
