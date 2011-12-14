<?php

class miMergeEmbedFormPluginConfiguration extends sfPluginConfiguration
{
  const VERSION = '1.2';

  public function initialize()
  {
    $this->dispatcher->connect('form.method_not_found',
      array('miMergeEmbedFormEventListener', 'onFormMethodNotFound'));

    $this->dispatcher->connect('form.filter_values',
      array('miMergeEmbedFormEventListener', 'onFormFilterValues'));

    $this->dispatcher->connect('form.validation_error',
      array('miMergeEmbedFormEventListener', 'onFormValidationError'));
  }
}
