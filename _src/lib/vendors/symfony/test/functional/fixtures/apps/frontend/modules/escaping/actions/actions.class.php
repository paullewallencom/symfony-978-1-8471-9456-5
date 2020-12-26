<?php

/**
 * escaping actions.
 *
 * @package    project
 * @subpackage escaping
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: actions.class.php 14698 2009-01-14 09:35:26Z dwhittle $
 */
class escapingActions extends sfActions
{
  public function preExecute($request)
  {
    $this->var = 'Lorem <strong>ipsum</strong> dolor sit amet.';
    $this->setLayout(false);
    $this->setTemplate('index');
  }

  public function executeOn()
  {
    sfConfig::set('sf_escaping_strategy', 'on');
  }

  public function executeOff()
  {
    sfConfig::set('sf_escaping_strategy', 'off');
  }
}
