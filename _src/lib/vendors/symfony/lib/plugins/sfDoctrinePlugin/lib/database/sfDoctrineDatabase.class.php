<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A symfony database driver for Doctrine.
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: sfDoctrineDatabase.class.php 19939 2009-07-06 17:26:02Z Jonathan.Wage $
 */
class sfDoctrineDatabase extends sfDatabase
{
  /**
   * Instance of the Doctrine_Connection for this instance of sfDoctrineDatabase.
   * Connection can be accessed by the getDoctrineConnection() accessor method.
   *
   * @var Doctrine_Connection $_doctrineConnection
   */
  protected $_doctrineConnection = null;

  /**
   * Initialize a sfDoctrineDatabase connection with the given parameters.
   *
   * <code>
   * $parameters = array(
   *    'name'       => 'doctrine',
   *    'dsn'        => 'sqlite:////path/to/sqlite/db');
   *
   * $p = new sfDoctrineDatabase($parameters);
   * </code>
   *
   * @param array $parameters  Array of parameters used to initialize the database connection
   * @return void
   */
  public function initialize($parameters = array())
  {
    parent::initialize($parameters);

    if (!is_null($this->_doctrineConnection))
    {
      return;
    }

    $dsn = $this->getParameter('dsn');
    $name = $this->getParameter('name');

    // Make sure we pass non-PEAR style DSNs as an array
    if ( !strpos($dsn, '://'))
    {
      $dsn = array($dsn, $this->getParameter('username'), $this->getParameter('password'));
    }

    // Make the Doctrine connection for $dsn and $name
    $this->_doctrineConnection = Doctrine_Manager::connection($dsn, $name);
    $attributes = $this->getParameter('attributes', array());
    foreach ($attributes as $name => $value)
    {
      if (is_string($name))
      {
        $stringName = $name;
        $name = constant('Doctrine::ATTR_'.strtoupper($name));
      }
      if (is_string($value))
      {
        $value = constant('Doctrine::'.strtoupper($stringName).'_'.strtoupper($value));
      }
      $this->_doctrineConnection->setAttribute($name, $value);
    }

    $encoding = $this->getParameter('encoding', 'UTF8'); 
    $eventListener = new sfDoctrineConnectionListener($this->_doctrineConnection, $encoding); 
    $this->_doctrineConnection->addListener($eventListener);

    // Load Query Logger Listener
    if (sfConfig::get('sf_debug') && sfConfig::get('sf_logging_enabled'))
    {
      $this->_doctrineConnection->addListener(new sfDoctrineLogger());
    }

    // Invoke the configuration methods for the connection if they exist
    $configuration = sfProjectConfiguration::getActive();

    $method = sprintf('configureDoctrineConnection%s', ucwords($this->_doctrineConnection->getName()));
    
    if (method_exists($configuration, 'configureDoctrineConnection') && ! method_exists($configuration, $method))
    {
      $configuration->configureDoctrineConnection($this->_doctrineConnection);
    }

    if (method_exists($configuration, $method))
    {
      $configuration->$method($this->_doctrineConnection);
    }
  }

  /**
   * Get the Doctrine_Connection instance.
   *
   * @return Doctrine_Connection $conn
   */
  public function getDoctrineConnection()
  {
    return $this->_doctrineConnection;
  }

  /**
   * Initializes the connection and sets it to object.
   *
   * @return void
   */
  public function connect()
  {
    $this->connection = $this->_doctrineConnection->getDbh();
  }

  /**
   * Execute the shutdown procedure.
   *
   * @return void
   */
  public function shutdown()
  {
    if ($this->connection !== null)
    {
      $this->connection = null;
      $this->_doctrineConnection = null;
    }
  }
}