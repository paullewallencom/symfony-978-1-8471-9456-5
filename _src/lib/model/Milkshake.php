<?php

class Milkshake extends BaseMilkshake
{
  public function setUrlSlug($v)
  {
    //Remove all non-alphanumeric characters except for a space.

    $newV = preg_replace('/\s+/', '-', $v);
    $newV = preg_replace('/[^a-zA-Z0-9\-]/', '', $newV);

    // trim and lowercase
    $newV = strtolower(trim($newV, '-'));

    return parent::setUrlSlug($newV);
  }
  
  public function setImageUrl($value)
  {
    $shortName = substr($value, 0, 16);
    // taking upload path from config
    $uploadPath = sfConfig::get('sf_upload_dir').'/milkshakes/';
    $thumbPrefix = 'thumb_';

    // removing old thumbnail
    $oldImage = $this->getImageUrl();

    if (!empty($oldImage) 
      && is_file($uploadPath.$thumbPrefix.$oldImage))
    {
      unlink($uploadPath.$thumbPrefix.$oldImage);
    }

    // creating thumbnail
    if (!empty($value) && is_file($uploadPath.$value))
    {
      // creating thumbnail
      $thumb = new sfThumbnail(80, 60, true, false, 90);
      $thumb->loadFile($uploadPath.$value);
      $thumb->save($uploadPath.$thumbPrefix.$value);

      // rescaling.
      $rescale = new sfThumbnail(950, 580, true, false, 100);
      $rescale->loadFile($uploadPath.$value);
      $rescale->save($uploadPath.$value);
    }

    parent::setImageUrl($value);
    parent::setThumbUrl($thumbPrefix.$value);
  }
}
