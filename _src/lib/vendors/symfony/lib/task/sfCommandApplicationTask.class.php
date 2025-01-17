<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Base class for tasks that depends on a sfCommandApplication object.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfCommandApplicationTask.class.php 19273 2009-06-15 12:47:22Z fabien $
 */
abstract class sfCommandApplicationTask extends sfTask
{
  protected
    $commandApplication = null;

  /**
   * Sets the command application instance for this task.
   *
   * @param sfCommandApplication $commandApplication A sfCommandApplication instance
   */
  public function setCommandApplication(sfCommandApplication $commandApplication = null)
  {
    $this->commandApplication = $commandApplication;
  }

  /**
   * @see sfTask
   */
  public function log($messages)
  {
    if (is_null($this->commandApplication) || $this->commandApplication->isVerbose())
    {
      parent::log($messages);
    }
  }

  /**
   * @see sfTask
   */
  public function logSection($section, $message, $size = null, $style = 'INFO')
  {
    if (is_null($this->commandApplication) || $this->commandApplication->isVerbose())
    {
      parent::logSection($section, $message, $size, $style);
    }
  }

  /**
   * Executes another task in the context of the current one.
   *
   * @param  string  $name      The name of the task to execute
   * @param  array   $arguments An array of arguments to pass to the task
   * @param  array   $options   An array of options to pass to the task
   *
   * @return Boolean The returned value of the task run() method
   */
  protected function runTask($name, $arguments = array(), $options = array())
  {
    if (is_null($this->commandApplication))
    {
      throw new LogicException('No command application associated with this task yet.');
    }

    $task = $this->commandApplication->getTaskToExecute($name);
    $task->setCommandApplication($this->commandApplication);

    return $task->run($arguments, $options);
  }
}
