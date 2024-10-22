<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWebDebugPanelCache adds a panel to the web debug toolbar with a link to ignore the cache
 * on the next request.
 *
 * @package    symfony
 * @subpackage debug
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWebDebugPanelCache.class.php 14599 2009-01-11 09:34:06Z dwhittle $
 */
class sfWebDebugPanelCache extends sfWebDebugPanel
{
  public function getTitle()
  {
    return '<img src="'.$this->webDebug->getOption('image_root_path').'/reload.png" alt="Reload" />';
  }

  public function getTitleUrl()
  {
    return $_SERVER['REQUEST_URI'].((strpos($_SERVER['REQUEST_URI'], '_sf_ignore_cache') === false) ? '?_sf_ignore_cache=1' : '');
  }

  public function getPanelTitle()
  {
    return 'reload and ignore cache';
  }

  public function getPanelContent()
  {
  }
}
