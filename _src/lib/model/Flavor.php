<?php

class Flavor extends BaseFlavor
{
  public function __toString()
  {
    return $this->getName();
  }
}
