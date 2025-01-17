<?php

/**
 * vacancies actions.
 *
 * @package    milkshake
 * @subpackage vacancies
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class vacanciesActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->vacanciesArray = VacancyPeer::getVacancies($this->getUser()->getCulture());

    if(count($this->vacanciesArray) < 1)
  	{
  		return 'NoVacancies';
  	}
  	else
  	{
  		return sfView::SUCCESS;
  	}
  }
}
