<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Base class for all symfony tasks.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfBaseTask.class.php 19273 2009-06-15 12:47:22Z fabien $
 */
abstract class sfBaseTask extends sfCommandApplicationTask
{
  protected
    $configuration = null,
    $pluginManager = null;

  /**
   * @see sfTask
   */
  protected function doRun(sfCommandManager $commandManager, $options)
  {
    $event = $this->dispatcher->filter(new sfEvent($this, 'command.filter_options', array('command_manager' => $commandManager)), $options);
    $options = $event->getReturnValue();

    $this->process($commandManager, $options);

    $event = new sfEvent($this, 'command.pre_command', array('arguments' => $commandManager->getArgumentValues(), 'options' => $commandManager->getOptionValues()));
    $this->dispatcher->notifyUntil($event);
    if ($event->isProcessed())
    {
      return $event->getReturnValue();
    }

    $this->checkProjectExists();

    $application = $commandManager->getArgumentSet()->hasArgument('application') ? $commandManager->getArgumentValue('application') : ($commandManager->getOptionSet()->hasOption('application') ? $commandManager->getOptionValue('application') : null);
    $env = $commandManager->getOptionSet()->hasOption('env') ? $commandManager->getOptionValue('env') : 'test';

    if (true === $application)
    {
      $application = $this->getFirstApplication();

      if ($commandManager->getOptionSet()->hasOption('application'))
      {
        $commandManager->setOption($commandManager->getOptionSet()->getOption('application'), $application);
      }
    }

    $this->configuration = $this->createConfiguration($application, $env);

    if (!is_null($this->commandApplication) && !$this->commandApplication->withTrace())
    {
      sfConfig::set('sf_logging_enabled', false);
    }

    $ret = $this->execute($commandManager->getArgumentValues(), $commandManager->getOptionValues());

    $this->dispatcher->notify(new sfEvent($this, 'command.post_command'));

    return $ret;
  }

  /**
   * Returns the filesystem instance.
   *
   * @return sfFilesystem A sfFilesystem instance
   */
  public function getFilesystem()
  {
    if (!isset($this->filesystem))
    {
      if (is_null($this->commandApplication) || $this->commandApplication->isVerbose())
      {
        $this->filesystem = new sfFilesystem($this->dispatcher, $this->formatter);
      }
      else
      {
        $this->filesystem = new sfFilesystem();
      }
    }

    return $this->filesystem;
  }

  /**
   * Checks if the current directory is a symfony project directory.
   *
   * @return true if the current directory is a symfony project directory, false otherwise
   */
  public function checkProjectExists()
  {
    if (!file_exists('symfony'))
    {
      throw new sfException('You must be in a symfony project directory.');
    }
  }

  /**
   * Checks if an application exists.
   *
   * @param  string $app  The application name
   *
   * @return bool true if the application exists, false otherwise
   */
  public function checkAppExists($app)
  {
    if (!is_dir(sfConfig::get('sf_apps_dir').'/'.$app))
    {
      throw new sfException(sprintf('Application "%s" does not exist', $app));
    }
  }

  /**
   * Checks if a module exists.
   *
   * @param  string $app     The application name
   * @param  string $module  The module name
   *
   * @return bool true if the module exists, false otherwise
   */
  public function checkModuleExists($app, $module)
  {
    if (!is_dir(sfConfig::get('sf_apps_dir').'/'.$app.'/modules/'.$module))
    {
      throw new sfException(sprintf('Module "%s/%s" does not exist.', $app, $module));
    }
  }

  /**
   * Creates a configuration object.
   *
   * @param string  $application The application name
   * @param string  $env         The environment name
   *
   * @return sfProjectConfiguration A sfProjectConfiguration instance
   */
  protected function createConfiguration($application, $env)
  {
    if (!is_null($application))
    {
      $this->checkAppExists($application);

      require_once sfConfig::get('sf_config_dir').'/ProjectConfiguration.class.php';

      $configuration = ProjectConfiguration::getApplicationConfiguration($application, $env, true, null, $this->dispatcher);
    }
    else
    {
      if (file_exists(sfConfig::get('sf_config_dir').'/ProjectConfiguration.class.php'))
      {
        require_once sfConfig::get('sf_config_dir').'/ProjectConfiguration.class.php';
        $configuration = new ProjectConfiguration(null, $this->dispatcher);
      }
      else
      {
        $configuration = new sfProjectConfiguration(getcwd(), $this->dispatcher);
      }

      if (!is_null($env))
      {
        sfConfig::set('sf_environment', $env);
      }

      $autoloader = sfSimpleAutoload::getInstance(sfConfig::get('sf_cache_dir').'/project_autoload.cache');
      $autoloader->addFiles(sfFinder::type('file')->prune('symfony')->follow_link()->name('*.php')->in(sfConfig::get('sf_lib_dir')));
      $autoloader->register();
    }

    return $configuration;
  }

  /**
   * Returns the first application in apps.
   *
   * @return string The Application name
   */
  protected function getFirstApplication()
  {
    if (count($dirs = sfFinder::type('dir')->ignore_version_control()->maxdepth(0)->follow_link()->relative()->in(sfConfig::get('sf_apps_dir'))))
    {
      return $dirs[0];
    }

    return null;
  }

  /**
   * Reloads autoloaders.
   */
  protected function reloadAutoload()
  {
    $this->logSection('autoload', 'Reloading autoloaders');

    $finder = sfFinder::type('file')->name('*autoload.yml.php');
    $this->getFilesystem()->remove($finder->in(sfConfig::get('sf_cache_dir')));
    sfAutoload::getInstance()->reloadClasses();

    sfSimpleAutoload::getInstance(sfConfig::get('sf_cache_dir').'/project_autoload.cache')->reload();
  }

  /**
   * Mirrors a directory structure inside the created project.
   *
   * @param string   $dir    The directory to mirror
   * @param sfFinder $finder A sfFinder instance to use for the mirroring
   */
  protected function installDir($dir, $finder = null)
  {
    if (is_null($finder))
    {
      $finder = sfFinder::type('any')->discard('.sf');
    }

    $this->getFilesystem()->mirror($dir, sfConfig::get('sf_root_dir'), $finder);
  }

  /**
   * Replaces tokens in files contained in a given directory.
   *
   * If you don't pass a directory, it will replace in the config/ and lib/ directory.
   *
   * You can define global tokens by defining the $this->tokens property.
   *
   * @param array $dirs   An array of directory where to do the replacement
   * @param array $tokens An array of tokens to use
   */
  protected function replaceTokens($dirs = array(), $tokens = array())
  {
    if (!$dirs)
    {
      $dirs = array(sfConfig::get('sf_config_dir'), sfConfig::get('sf_lib_dir'));
    }

    $tokens = array_merge(isset($this->tokens) ? $this->tokens : array(), $tokens);

    $this->getFilesystem()->replaceTokens(sfFinder::type('file')->prune('vendor')->in($dirs), '##', '##', $tokens);
  }

  /**
   * Reloads tasks.
   *
   * Useful when you install plugins with tasks and if you want to use them with the runTask() method.
   */
  protected function reloadTasks()
  {
    if (is_null($this->commandApplication))
    {
      return;
    }

    $this->configuration = $this->createConfiguration(null, null);

    $this->commandApplication->clearTasks();
    $this->commandApplication->loadTasks($this->configuration);

    $disabledPluginsRegex = sprintf('#^(%s)#', implode('|', array_diff($this->configuration->getAllPluginPaths(), $this->configuration->getPluginPaths())));
    $tasks = array();
    foreach (get_declared_classes() as $class)
    {
      $r = new Reflectionclass($class);
      if ($r->isSubclassOf('sfTask') && !$r->isAbstract() && !preg_match($disabledPluginsRegex, $r->getFileName()))
      {
        $tasks[] = new $class($this->dispatcher, $this->formatter);
      }
    }

    $this->commandApplication->registerTasks($tasks);
  }

  /**
   * Enables a plugin in the ProjectConfiguration class.
   *
   * @param string $plugin The name of the plugin
   */
  protected function enablePlugin($plugin)
  {
    $this->getPluginManager()->enablePlugin($plugin);
  }

  /**
   * Disables a plugin in the ProjectConfiguration class.
   *
   * @param string $plugin The name of the plugin
   */
  protected function disablePlugin($plugin)
  {
    $this->getPluginManager()->disablePlugin($plugin);
  }

  /**
   * Returns a plugin manager instance.
   *
   * @return sfSymfonyPluginManager A sfSymfonyPluginManager instance
   */
  protected function getPluginManager()
  {
    if (is_null($this->pluginManager))
    {
      $environment = new sfPearEnvironment($this->dispatcher, array(
        'plugin_dir' => sfConfig::get('sf_plugins_dir'),
        'cache_dir'  => sfConfig::get('sf_cache_dir').'/.pear',
        'web_dir'    => sfConfig::get('sf_web_dir'),
        'config_dir' => sfConfig::get('sf_config_dir'),
      ));

      $this->pluginManager = new sfSymfonyPluginManager($this->dispatcher, $environment);
    }

    return $this->pluginManager;
  }
}
