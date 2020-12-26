<?php

class AlSignupNewsletterAds extends BaseAlSignupNewsletterAds
{
  public function __toString()
  {
    return $this->getAdvertised();
  }
}
