<?php

/*
 * This file is part of the symfony package.
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfDoctrinePluginConfiguration Class
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: sfDoctrinePluginConfiguration.class.php 29156 2010-04-14 22:22:41Z bschussek $
 */
class sfDoctrinePluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    sfConfig::set('sf_orm', 'doctrine');

    if (!sfConfig::get('sf_admin_module_web_dir'))
    {
      sfConfig::set('sf_admin_module_web_dir', '/sfDoctrinePlugin');
    }

    if (sfConfig::get('sf_web_debug'))
    {
      require_once dirname(__FILE__).'/../lib/debug/sfWebDebugPanelDoctrine.class.php';

      $this->dispatcher->connect('debug.web.load_panels', array('sfWebDebugPanelDoctrine', 'listenToAddPanelEvent'));
    }

    require_once sfConfig::get('sf_doctrine_dir', realpath(dirname(__FILE__).'/../lib/vendor/doctrine')).'/Doctrine.php';
    spl_autoload_register(array('Doctrine', 'autoload'));

    $manager = Doctrine_Manager::getInstance();
    $manager->setAttribute(Doctrine::ATTR_EXPORT, Doctrine::EXPORT_ALL);
    $manager->setAttribute(Doctrine::ATTR_VALIDATE, Doctrine::VALIDATE_NONE);
    $manager->setAttribute(Doctrine::ATTR_RECURSIVE_MERGE_FIXTURES, true);
    $manager->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
    $manager->setAttribute(Doctrine::ATTR_AUTOLOAD_TABLE_CLASSES, true);

    // apply default attributes
    $manager->setDefaultAttributes();

    if (method_exists($this->configuration, 'configureDoctrine'))
    {
      $this->configuration->configureDoctrine($manager);
    }

    $this->dispatcher->notify(new sfEvent($manager, 'doctrine.configure'));

    // make sure the culture is intercepted
    $this->dispatcher->connect('user.change_culture', array('sfDoctrineRecord', 'listenToChangeCultureEvent'));
  }

  /**
   * Returns options for the Doctrine schema builder.
   *
   * @return array
   */
  public function getModelBuilderOptions()
  {
    $options = array(
      'generateBaseClasses'  => true,
      'generateTableClasses' => true,
      'packagesPrefix'       => 'Plugin',
      'suffix'               => '.class.php',
      'baseClassesDirectory' => 'base',
      'baseClassName'        => 'sfDoctrineRecord',
    );

    // for BC
    $options = array_merge($options, sfConfig::get('doctrine_model_builder_options', array()));

    // filter options through the dispatcher
    $options = $this->dispatcher->filter(new sfEvent($this, 'doctrine.filter_model_builder_options'), $options)->getReturnValue();

    return $options;
  }

  /**
   * Returns a configuration array for the Doctrine CLI.
   *
   * @return array
   */
  public function getCliConfig()
  {
    $config = array(
      'data_fixtures_path' => array_merge(array(sfConfig::get('sf_data_dir').'/fixtures'), $this->configuration->getPluginSubPaths('/data/fixtures')),
      'models_path'        => sfConfig::get('sf_lib_dir').'/model/doctrine',
      'migrations_path'    => sfConfig::get('sf_lib_dir').'/migration/doctrine',
      'sql_path'           => sfConfig::get('sf_data_dir').'/sql',
      'yaml_schema_path'   => sfConfig::get('sf_config_dir').'/doctrine',
    );

    // filter config through the dispatcher
    $config = $this->dispatcher->filter(new sfEvent($this, 'doctrine.filter_cli_config'), $config)->getReturnValue();

    return $config;
  }
}
