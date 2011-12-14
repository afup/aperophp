<?php

/**
 * Apero form.
 *
 * @package    aperosymfony
 * @subpackage form
 * @author     Benjamin Laugueux <b.laugueux@yzalis.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AperoForm extends BaseAperoForm
{
  public function configure()
  {
    $this->useFields(array('description', 'date_at', 'time_at', 'price', 'max_people', 'location_name', 'location_address', 'location_city', 'location_zipcode', 'is_active'));
    
    $this->widgetSchema['description'] = new sfWidgetFormTextareaTinyMCE(array('width' => '600', 'height' => '300'));
    $this->widgetSchema['description']->setLabel('Description');

    $this->widgetSchema['location_name']->setLabel('Lieu');

    $this->widgetSchema['location_address']->setLabel('Adresse');

    $this->widgetSchema['location_city']->setLabel('Ville');

    $this->widgetSchema['location_zipcode']->setLabel('Code postal');

    $this->widgetSchema['date_at'] = new sfWidgetFormJQueryDate(array(
      'date_widget' => new sfWidgetFormI18nDate(array('culture' => sfContext::getInstance()->getUser()->getCulture())),
      'image' => '/images/icons/famfamfam/date.png',
      'culture' => sfContext::getInstance()->getUser()->getCulture()
    ));
    $this->widgetSchema['date_at']->setLabel('Date');

    $this->widgetSchema['time_at']->setLabel('Heure');

    $this->widgetSchema['price']->setLabel('Prix');

    $this->widgetSchema['max_people']->setLabel('Nb pers. max');

    $this->widgetSchema['is_active']->setLabel('En ligne');
  }
}
