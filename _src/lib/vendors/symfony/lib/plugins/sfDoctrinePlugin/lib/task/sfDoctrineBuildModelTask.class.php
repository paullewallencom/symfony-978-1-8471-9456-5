<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once(dirname(__FILE__).'/sfDoctrineBaseTask.class.php');

/**
 * Create classes for the current model.
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: sfDoctrineBuildModelTask.class.php 20025 2009-07-08 17:29:18Z Jonathan.Wage $
 */
class sfDoctrineBuildModelTask extends sfDoctrineBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));

    $this->aliases = array('doctrine-build-model');
    $this->namespace = 'doctrine';
    $this->name = 'build-model';
    $this->briefDescription = 'Creates classes for the current model';

    $this->detailedDescription = <<<EOF
The [doctrine:build-model|INFO] task creates model classes from the schema:

  [./symfony doctrine:build-model|INFO]

The task read the schema information in [config/doctrine/*.yml|COMMENT]
from the project and all installed plugins.

The model classes files are created in [lib/model/doctrine|COMMENT].

This task never overrides custom classes in [lib/model/doctrine|COMMENT].
It only replaces files in [lib/model/doctrine/base|COMMENT].
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection('doctrine', 'generating model classes');

    $config = $this->getCliConfig();

    $tmpPath = sfConfig::get('sf_cache_dir').DIRECTORY_SEPARATOR.'tmp';

    if (!file_exists($tmpPath))
    {
      Doctrine_Lib::makeDirectories($tmpPath);
    }

    $plugins = $this->configuration->getPlugins();
    foreach ($this->configuration->getAllPluginPaths() as $plugin => $path)
    {
      if (!in_array($plugin, $plugins))
      {
        continue;
      }
      $schemas = sfFinder::type('file')->name('*.yml')->in($path.'/config/doctrine');
      foreach ($schemas as $schema)
      {
        $tmpSchemaPath = $tmpPath.DIRECTORY_SEPARATOR.$plugin.'-'.basename($schema);

        $models = Doctrine_Parser::load($schema, 'yml');
        if (!isset($models['package']))
        {
          $models['package'] = $plugin.'.lib.model.doctrine';
          $models['package_custom_path'] = $path.'/lib/model/doctrine';
        }
        Doctrine_Parser::dump($models, 'yml', $tmpSchemaPath);
      }
    }

    $options = array('generateBaseClasses'  => true,
                     'generateTableClasses' => true,
                     'packagesPath'         => sfConfig::get('sf_plugins_dir'),
                     'packagesPrefix'       => 'Plugin',
                     'suffix'               => '.class.php',
                     'baseClassesDirectory' => 'base',
                     'baseClassName'        => 'sfDoctrineRecord');
    $options = array_merge($options, sfConfig::get('doctrine_model_builder_options', array()));

    $import = new Doctrine_Import_Schema();
    $import->setOptions($options);
    $import->importSchema(array($tmpPath, $config['yaml_schema_path']), 'yml', $config['models_path']);
  }
}