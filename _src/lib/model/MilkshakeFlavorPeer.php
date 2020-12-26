<?php

class MilkshakeFlavorPeer extends BaseMilkshakeFlavorPeer
{
  public static function getMilkshakeFlavor($slug)
  {

   return DbFinder::from('MilkshakeFlavor')
    ->with('Milkshake')
    ->with('Flavor')
    ->where('Milkshake.UrlSlug', $slug)
    ->find();
  }
}
