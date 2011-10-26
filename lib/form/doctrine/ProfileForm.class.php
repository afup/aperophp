<?php

/**
 * Profile form.
 *
 * @package    aperosymfony
 * @subpackage form
 * @author     Benjamin Laugueux <b.laugueux@yzalis.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProfileForm extends BaseProfileForm
{
  public function configure()
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Asset'));

    $this->widgetSchema['company']->setLabel('Société');
    $this->widgetSchema['company']->setAttribute('size', '35');

    $this->widgetSchema['function']->setLabel('Poste occupé');
    $this->widgetSchema['function']->setAttribute('size', '35');

    $this->widgetSchema['description']->setAttributes(array('cols' => '50', 'rows' => '8'));

    $this->widgetSchema['mobile_phone']->setLabel('Tél. mobile');
    $this->widgetSchema['mobile_phone']->setAttribute('size', '35');

    $this->validatorSchema['twitter'] = new sfValidatorUrl(array('required' => false));
    $this->widgetSchema['twitter']->setAttribute('size', '35');

    $this->validatorSchema['facebook'] = new sfValidatorUrl(array('required' => false));
    $this->widgetSchema['facebook']->setAttribute('size', '35');
    
    $this->widgetSchema['website']->setLabel('Site Internet');
    $this->widgetSchema['website']->setAttribute('size', '35');
    $this->validatorSchema['website'] = new sfValidatorUrl(array('required' => false));

    $dir = $this->getObject()->getAvatarDirectory();
    $img = trim($this->getObject()->getAvatar());
    $isEditMode = !$this->isNew() && ($img != '');
    $withDelete = $isEditMode;

    $this->widgetSchema['avatar'] = new sfWidgetFormInputFileEditable(array(
      'file_src' => image_path('/uploads/'.$dir.'/'.$img),
      'is_image' => true,
      'edit_mode' => $isEditMode,
      'with_delete' => $withDelete,
      'delete_label' => 'Supprimer',
      'template' => '%input%<div>%delete_label%%delete%<div class="form_input_file_image">%file%</div></div>',
    ), array('width' => '100px'));
    
    $this->validatorSchema['avatar'] = new sfValidatorFile(array(
      'required' => false,
      'max_size' => 500000,
      'mime_types' => 'web_images',
      'path' => sfConfig::get('sf_upload_dir').DIRECTORY_SEPARATOR.$dir,
    ));

    if ($withDelete)
    {
      $this->validatorSchema['avatar_delete'] = new sfValidatorBoolean();
    }
  }
}
