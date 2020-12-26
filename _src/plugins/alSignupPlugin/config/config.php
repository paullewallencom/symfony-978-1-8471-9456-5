<?php

if (sfConfig::get('app_alSignup_routes_register', true) && in_array('alSignup', sfConfig::get('sf_enabled_modules')))
{
  $this->dispatcher->connect('routing.load_configuration', array('alSignupRouting', 'listenToRoutingLoadConfigurationEvent'));
}