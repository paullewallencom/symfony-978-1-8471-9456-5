<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Autoload again for dev environments.
 * 
 * @package    symfony
 * @subpackage autoload
 * @author     Kris Wallsmith <kris.wallsmith@symfony-project.com>
 * @version    SVN: $Id: sfAutoloadAgain.class.php 19470 2009-06-23 08:44:02Z fabien $
 */
class sfAutoloadAgain
{
  static protected
    $instance = null;

  protected
    $registered = false;

  /**
   * Returns the singleton autoloader.
   * 
   * @return sfAutoloadAgain
   */
  static public function getInstance()
  {
    if (is_null(self::$instance))
    {
      self::$instance = new self();
    }

    return self::$instance;
  }

  /**
   * Constructor.
   */
  protected function __construct()
  {
  }

  /**
   * Reloads the autoloader.
   * 
   * @param  string $class
   * 
   * @return boolean
   */
  public function autoload($class)
  {
    $autoloads = spl_autoload_functions();

    // as of PHP 5.3.0, spl_autoload_functions() returns the object as the first element of the array instead of the class name
    if (version_compare(PHP_VERSION, '5.3.0RC1', '>='))
    {
      foreach ($autoloads as $position => $autoload)
      {
        if ($this === $autoload[0])
        {
          break;
        }
      }
    }
    else
    {
      $position  = array_search(array(__CLASS__, 'autoload'), $autoloads, true);
    }

    if (isset($autoloads[$position + 1]))
    {
      $this->unregister();
      $this->register();

      // since we're rearranged things, call the chain again
      spl_autoload_call($class);

      return class_exists($class, false) || interface_exists($class, false);
    }

    $autoload = sfAutoload::getInstance();
    $autoload->reloadClasses(true);

    return $autoload->autoload($class);
  }

  /**
   * Returns true if the autoloader is registered.
   * 
   * @return boolean
   */
  public function isRegistered()
  {
    return $this->registered;
  }

  /**
   * Registers the autoloader function.
   */
  public function register()
  {
    if (!$this->isRegistered())
    {
      spl_autoload_register(array($this, 'autoload'));
      $this->registered = true;
    }
  }

  /**
   * Unregisters the autoloader function.
   */
  public function unregister()
  {
    spl_autoload_unregister(array($this, 'autoload'));
    $this->registered = false;
  }
}
