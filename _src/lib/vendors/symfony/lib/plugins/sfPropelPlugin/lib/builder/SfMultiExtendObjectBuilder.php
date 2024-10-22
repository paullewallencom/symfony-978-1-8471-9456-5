<?php

require_once 'propel/engine/builder/om/php5/PHP5MultiExtendObjectBuilder.php';

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @package    symfony
 * @subpackage propel
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: SfMultiExtendObjectBuilder.php 14770 2009-01-15 18:30:44Z Kris.Wallsmith $
 */
class SfMultiExtendObjectBuilder extends PHP5MultiExtendObjectBuilder
{
  public function build()
  {
    $code = parent::build();
    if (!DataModelBuilder::getBuildProperty('builderAddComments'))
    {
      $code = sfToolkit::stripComments($code);
    }

    return $code;
  }

  protected function addIncludes(&$script)
  {
    if (!DataModelBuilder::getBuildProperty('builderAddIncludes'))
    {
      return;
    }

    parent::addIncludes($script);
  }

  protected function addClassOpen(&$script)
  {
    parent::addClassOpen($script);

    // remove comments and fix coding standards
    $script = str_replace(array(" {\n", "\n\n\n"), array("\n{", "\n"), sfToolkit::stripComments($script));
  }

  protected function addClassBody(&$script)
  {
    parent::addClassBody($script);

    // remove comments and fix coding standards
    $script = str_replace(array("\t", "{\n  \n"), array('  ', "{\n"), sfToolkit::stripComments($script));
  }

  protected function addClassClose(&$script)
  {
    parent::addClassClose($script);

    // fix coding standards
    $script = preg_replace('#\n} // .+$#m', '}', $script);
  }
}
