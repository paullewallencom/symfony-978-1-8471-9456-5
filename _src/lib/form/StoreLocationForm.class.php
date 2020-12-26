<?php

/**
 * StoreLocation form.
 *
 * @package    milkshake
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class StoreLocationForm extends BaseStoreLocationForm
{
  public function configure()
  {
  }

  public function save($con = null)
  {
    // we are setting directory to to <env>/template/milkshake/all,
    // because we caching whole page layout (option with_layout = true, 
    // not just module itself
    // $frontend_cache_dir = sfConfig::get('sf_cache_dir').
    //   DIRECTORY_SEPARATOR.'frontend'.DIRECTORY_SEPARATOR.
    //   sfConfig::get('sf_environment').DIRECTORY_SEPARATOR.'template'.
    //   DIRECTORY_SEPARATOR.'milkshake'.DIRECTORY_SEPARATOR.'all';

    // $cache = new sfFileCache(array(
    //   'cache_dir' => $frontend_cache_dir
    // ));
    // $cache->removePattern('location/index');
    
    $cache = new sfMemcacheCache(array(
      'prefix' => 'frontend',
      'storeCacheInfo' => true
    ));

    $cache->removePattern('/milkshake/all/location/index');

    return parent::save($con);
  }
}
