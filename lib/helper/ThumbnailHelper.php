<?php

function thumbnail_tag($img, $width = '128', $height = '128', $options = array())
{
  $options = _parse_attributes($options);
  $pathName = dirname($img);
  $fileName = basename($img);
  $thumbnailPath = sfConfig::get('sf_upload_dir').DIRECTORY_SEPARATOR.'thumbnail'.DIRECTORY_SEPARATOR.$width.'x'.$height.DIRECTORY_SEPARATOR;
  $thumbnailLink = image_path('/uploads/thumbnail/'.$width.'x'.$height.'/'.$fileName);
  
  if (!is_file($thumbnailPath.$fileName))
  {
    if (!file_exists(sfConfig::get('sf_upload_dir').DIRECTORY_SEPARATOR.'thumbnail'))
    {
      mkdir(sfConfig::get('sf_upload_dir').DIRECTORY_SEPARATOR.'thumbnail');
    }

    if (!file_exists($thumbnailPath))
    {
      mkdir($thumbnailPath);
    }
    
    $thumbnail = new sfThumbnail($width, $height, true, false);
    $thumbnail->loadFile(sfConfig::get('sf_web_dir').$img);
    $thumbnail->save($thumbnailPath.$fileName, 'image/png');
  }

  $options['src'] = $thumbnailLink;

  return tag('img', $options, false);
}