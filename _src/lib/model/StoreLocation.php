<?php

class StoreLocation extends BaseStoreLocation
{
  public function __toString()
  {
    return $this->getCity(). ' (' . $this->getCountry() .')';
  }
}
