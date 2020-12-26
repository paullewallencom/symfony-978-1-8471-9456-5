<?php

class alSignupRouting
{
  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   */
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();

    // preprend our routes
    $r->prependRoute('signup', new sfRoute('/signup', array('module' => 'alSignup', 'action' => 'index')));
    $r->prependRoute('signup_submit', new sfRoute('/signup/submit', array('module' => 'alSignup', 'action' => 'submit')));

  }
}