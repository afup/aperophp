<?php

class miMergeEmbedFormEventListener
{
  public static function onFormMethodNotFound(sfEvent $event)
  {
      $form = $event->getSubject();

      switch (strtolower($event['method'])){
      case 'mergeembedform':
          if(count($event['arguments']) !== 2){
              throw new InvalidArugmentException('The number of arguments is invalid.');
          }
          self::mergeEmbedForm($event['arguments'][0],$event['arguments'][1],$form);
          $form->addEmbeddedForm($event['arguments'][0],$event['arguments'][1]);
          $event->setProcessed(true);
          break;      
      case 'addembeddedform':
          if(count($event['arguments']) !== 2){
              throw new InvalidArugmentException('The number of arguments is invalid.');
          }
          self::addEmbeddedForm($form, $event['arguments'][0], $event['arguments'][1]);
          $event->setProcessed(true);
          break;
     }
  }

  public static function onFormFilterValues(sfEvent $event,$values)
  {
      $form = $event->getSubject();
      return self::filterValues($values,$form);
  }

  public static function onFormValidationError(sfEvent $event)
  {
      $form = $event->getSubject();
      self::updateErrors($form,$event['error']);
  }

  private static function addEmbeddedForm(sfForm $form, $name, sfForm $embedded_form)
  {
      $form->embedForm($name, $embedded_form);
      // clear widget schema and defaults
      $form->getWidgetSchema()->offsetUnset($name);
      $defaults = $form->getDefaults();
      unset($defaults[$name]);
      $form->setDefaults($defaults);
  }

  private static function updateErrors(sfForm $form, sfValidatorErrorSchema $errors, $path='')
  {
    $fields = array_keys($form->getWidgetSchema()->getFields());
    $child_errors = array();
    foreach($errors->getErrors() as $child => $child_error){
      $child_path = self::combinedName($path,$child);
      if($child_error instanceof sfValidatorErrorSchema){
        $new_child_errors = self::updateErrors($form,$child_error,$child_path);
        $child_errors = array_merge($child_errors,$new_child_errors);
      }
      if($path!='' && in_array($child_path,$fields)){
        $child_errors[$child_path] = $child_error;
      }
    }
    if($path==''){
      foreach($child_errors as $child_path=>$child_error){
        $errors->addError($child_error,$child_path);
      }
    }
    return $child_errors;
  }

  private static function filterValues(array $values, sfForm $form,$path='')
  {
    foreach($form->getEmbeddedForms() as $child => $child_form){
      $child_path = self::combinedName($path,$child);
      $values = self::filterValues($values,$child_form,$child_path);
      if(isset($values[$child]) && is_array($values[$child])){
        // not mergeembedded children can also have mergeembedded children 
        $values[$child] = self::filterValues($values[$child],$child_form);
      }
    }
    // the parent form is not merged
    if(strlen($path)==0){
      return $values;
    }
    
    // use validators, since some values don't have field
    $fields = array_keys($form->getValidatorSchema()->getFields());
    foreach($fields as $field){
      $field_path = self::combinedName($path,$field);
      // don't use isset: null values also count
      if(in_array($field_path,array_keys($values),true)){
        if(isset($values[$path]) && !is_array($values[$path])){
          $values[$path] = array();
        }
        $values[$path][$field] = $values[$field_path];
        unset($values[$field_path]);
      }
    }
    return $values;
  }

  private static function combinedName($name,$field)
  {
    if(strlen($name)>0){
      return $name.'_'.$field;
    }else{
      return $field;
    }
  }

  private static function mergeEmbedForm($name,sfForm $child, sfForm $parent)
  {
    $name = (string) $name;
    if(true === $parent->isBound() || true === $child->isBound()){
      throw new LogicException('A bound form cannot be merged');
    }
    $child = clone $child;
    unset($child[$child->getCSRFFieldName()]);
   
    // set widgets

    $parentWidgetSchema = $parent->getWidgetSchema();
    $childWidgetSchema = $child->getWidgetSchema();
 
    foreach($childWidgetSchema->getFields() as $field => $widget){
      $widgetName = self::combinedName($name,$field);
      if(isset($parentWidgetSchema[$widgetName])){
        throw new LogicException("The forms cannot be merged. A field name '$widgetName' already exists.");
      }
      $parentWidgetSchema[$widgetName] = $widget;
      $parentWidgetSchema->setHelp($widgetName,$childWidgetSchema->getHelp($field));
      $parent->setDefault($widgetName, $child->getDefault($field));

      if(!$widget->getLabel()){
        $label = $childWidgetSchema->getFormFormatter()->generateLabelName($field);
        if($field != $label){
          $parentWidgetSchema->setLabel($widgetName, $label);
        }
      }
    }

    // set validators
    $parentValidatorSchema = $parent->getValidatorSchema();
    $childValidatorSchema = $child->getValidatorSchema();
    
    $parentValidatorSchema[$name] = $childValidatorSchema;
    $parent->resetFormFields();
  }

}