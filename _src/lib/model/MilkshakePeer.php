<?php

class MilkshakePeer extends BaseMilkshakePeer
{
  /**
   * Get all milshakes
   *
   * @param Int $limit
   * @return Array Get all
   */
  public static function getAllShakes($currentPage, $totalItems)
  {
    $pagerObj = DbFinder::from('Milkshake')->useCache(new sfMemcacheCache())->select(array('name'))->paginate($currentPage, $totalItems);

    return $pagerObj;
  }



  public static function getBestMilkshakes()
  {
    $milkshakeResults =  DbFinder::from('Milkshake')->find();
    
    return $milkshakeResults;

  }
  
  public static function searchMilkshakesAjax($q, $limit)
  {
    $c = new Criteria();
    $c->add(self::NAME, '%'.$q.'%', Criteria::LIKE);
    $c->addAscendingOrderByColumn(self::NAME);
    $c->setLimit($limit);

    $milkshakes = array();
    foreach (self::doSelect($c) as $milkshake)
    {
      $milkshakes[$milkshake->getUrlSlug()] = $milkshake->getName();
    }

    return $milkshakes;
  }
}
