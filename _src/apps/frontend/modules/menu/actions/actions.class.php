<?php

/**
 * menu actions.
 *
 * @package    milkshake
 * @subpackage menu
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class menuActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new MilkshakeSearchForm();
    
    //Get all out all of the shakes
  	$this->milkshakeObj = MilkShakePeer::getAllShakes($request->getParameter('page'), sfConfig::get('mod_menu_total_menu_items'));

    //Forward to 404 if no results
    $this->forward404If($this->milkshakeObj->getNbResults() < 1, 'No Results in the Database');

    return sfView::SUCCESS;
  }


   /**
    * The milkshake page
    *
    * @param sfWebRequest $request
    * @return <String>
    */
  public function executeMilkshake(sfWebRequest $request)
  {
    $this->flavorArray = MilkshakeFlavorPeer::getMilkshakeFlavor($request->getParameter('url_slug'));

    //Forward to a 404
    $this->forward404If(count($this->flavorArray) < 1);

    //strip from array
    $this->milkshakeObj = $this->flavorArray[0];

    //Set the page title
    $this->getResponse()->setTitle($this->milkshakeObj->getMilkShake()->getName().' Milkshake');

    //Increment the total views
    $total = $this->milkshakeObj->getMilkShake()->getViews() + 1 ;

    //Update the object and save
    $this->milkshakeObj->getMilkShake()->setViews($total);
    $this->milkshakeObj->getMilkShake()->save();

    return sfView::SUCCESS;
  }

  public function executeSearch(sfWebRequest $request)
  {
    $this->getResponse()
      ->setContentType('application/json');

    $milkshakes = MilkshakePeer::searchMilkshakesAjax(
      $request->getParameter('q'), $request->getParameter('limit')
    );

    return $this->renderText(json_encode($milkshakes));
  }
}
