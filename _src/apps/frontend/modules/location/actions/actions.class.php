<?php

/**
 * location actions.
 *
 * @package    milkshake
 * @subpackage location
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class locationActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->locationsArray = StoreLocationPeer::getAllLocations();

    return sfView::SUCCESS;
  }
}
