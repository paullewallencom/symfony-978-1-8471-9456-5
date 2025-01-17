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
 * Doctrine form generator.
 *
 * This class generates a Doctrine forms.
 *
 * @package    symfony
 * @subpackage generator
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGenerator.class.php 8512 2008-04-17 18:06:12Z fabien $
 */
class sfDoctrineFormGenerator extends sfGenerator
{
  /**
   * Array of all the loaded models
   *
   * @var array
   */
  public $models = array();

  /**
   * Array of all plugin models
   *
   * @var array
   */
  public $pluginModels = array();

  /**
   * Initializes the current sfGenerator instance.
   *
   * @param sfGeneratorManager A sfGeneratorManager instance
   */
  public function initialize(sfGeneratorManager $generatorManager)
  {
    parent::initialize($generatorManager);

    $this->getPluginModels();
    $this->setGeneratorClass('sfDoctrineForm');
  }

  /**
   * Generates classes and templates in cache.
   *
   * @param array The parameters
   *
   * @return string The data to put in configuration cache
   */
  public function generate($params = array())
  {
    $this->params = $params;

    if (!isset($this->params['connection']))
    {
      throw new sfParseException('You must specify a "connection" parameter.');
    }

    if (!isset($this->params['model_dir_name']))
    {
      $this->params['model_dir_name'] = 'model';
    }

    if (!isset($this->params['form_dir_name']))
    {
      $this->params['form_dir_name'] = 'form';
    }

    $models = $this->loadModels();

    // create the project base class for all forms
    $file = sfConfig::get('sf_lib_dir').'/form/doctrine/BaseFormDoctrine.class.php';
    if (!file_exists($file))
    {
      if (!is_dir(sfConfig::get('sf_lib_dir').'/form/doctrine/base'))
      {
        mkdir(sfConfig::get('sf_lib_dir').'/form/doctrine/base', 0777, true);
      }

      file_put_contents($file, $this->evalTemplate('sfDoctrineFormBaseTemplate.php'));
    }

    $pluginPaths = $this->generatorManager->getConfiguration()->getAllPluginPaths();

    // create a form class for every Doctrine class
    foreach ($models as $model)
    {
      $this->table = Doctrine::getTable($model);
      $this->modelName = $model;

      $baseDir = sfConfig::get('sf_lib_dir') . '/form/doctrine';

      $isPluginModel = $this->isPluginModel($model);
      if ($isPluginModel)
      {
        $pluginName = $this->getPluginNameForModel($model);
        $baseDir .= '/' . $pluginName;
      }

      if (!is_dir($baseDir.'/base'))
      {
        mkdir($baseDir.'/base', 0777, true);
      }

      file_put_contents($baseDir.'/base/Base'.$model.'Form.class.php', $this->evalTemplate('sfDoctrineFormGeneratedTemplate.php'));
      if ($isPluginModel)
      {
        $pluginBaseDir = $pluginPaths[$pluginName].'/lib/form/doctrine';
        if (!file_exists($classFile = $pluginBaseDir.'/Plugin'.$model.'Form.class.php'))
        {
            if (!is_dir($pluginBaseDir))
            {
              mkdir($pluginBaseDir, 0777, true);
            }
            file_put_contents($classFile, $this->evalTemplate('sfDoctrineFormPluginTemplate.php'));
        }
      }
      if (!file_exists($classFile = $baseDir.'/'.$model.'Form.class.php'))
      {
        if ($isPluginModel)
        {
           file_put_contents($classFile, $this->evalTemplate('sfDoctrinePluginFormTemplate.php'));
        } else {
           file_put_contents($classFile, $this->evalTemplate('sfDoctrineFormTemplate.php'));
        }
      }
    }
  }

  /**
   * Get all the models which are a part of a plugin and the name of the plugin.
   * The array format is modelName => pluginName
   *
   * @todo This method is ugly and is a very weird way of finding the models which 
   *       belong to plugins. If we could come up with a better way that'd be great
   * @return array $pluginModels
   */
  public function getPluginModels()
  {
    if (!$this->pluginModels)
    {
      $plugins     = $this->generatorManager->getConfiguration()->getPlugins();
      $pluginPaths = $this->generatorManager->getConfiguration()->getAllPluginPaths();

      foreach ($pluginPaths as $pluginName => $path)
      {
        if (!in_array($pluginName, $plugins))
        {
          continue;
        }

        foreach (sfFinder::type('file')->name('*.php')->in($path.'/lib/model/doctrine') as $path)
        {
          $info = pathinfo($path);
          $e = explode('.', $info['filename']);
          $modelName = substr($e[0], 6, strlen($e[0]));

          if (class_exists($e[0]) && class_exists($modelName))
          {
            $parent = new ReflectionClass('Doctrine_Record');
            $reflection = new ReflectionClass($modelName);
            if ($reflection->isSubClassOf($parent))
            {
              $this->pluginModels[$modelName] = $pluginName;
              $generators = Doctrine::getTable($modelName)->getGenerators();
              foreach ($generators as $generator)
              {
                $this->pluginModels[$generator->getOption('className')] = $pluginName;
              }
            }
          }
        }
      }
    }

    return $this->pluginModels;
  }

  /**
   * Check to see if a model is part of a plugin
   *
   * @param string $modelName 
   * @return boolean $bool
   */
  public function isPluginModel($modelName)
  {
    return isset($this->pluginModels[$modelName]) ? true:false;
  }

  /**
   * Get the name of the plugin a model belongs to
   *
   * @param string $modelName 
   * @return string $pluginName
   */
  public function getPluginNameForModel($modelName)
  {
    if ($this->isPluginModel($modelName))
    {
      return $this->pluginModels[$modelName];
    } else {
      return false;
    }
  }

  /**
   * Returns an array of relations that represents a many to many relationship.
   *
   * A table is considered to be a m2m table if it has 2 foreign keys that are also primary keys.
   *
   * @return array An array of relations.
   */
  public function getManyToManyRelations()
  {
    $relations = array();
    foreach ($this->table->getRelations() as $relation)
    {
      if ($relation->getType() === Doctrine_Relation::MANY && isset($relation['refTable']))
      {
        $relations[] = $relation;
      }
    }
    return $relations;
  }

  /**
   * Returns PHP names for all foreign keys of the current table.
   *
   * This method does not returns foreign keys that are also primary keys.
   *
   * @return array An array composed of: 
   *                 * The foreign table PHP name
   *                 * The foreign key PHP name
   *                 * A Boolean to indicate whether the column is required or not
   *                 * A Boolean to indicate whether the column is a many to many relationship or not
   */
  public function getForeignKeyNames()
  {
    $names = array();
    foreach ($this->table->getRelations() as $relation)
    {
      if ($relation->getType() === Doctrine_Relation::ONE)
      {
        $foreignDef = $relation->getTable()->getDefinitionOf($relation->getForeignFieldName());
        $names[] = array($relation['table']->getOption('name'), $relation->getForeignFieldName(), $this->isColumnNotNull($relation->getForeignFieldName(), $foreignDef), false);
      }
    }

    foreach ($this->getManyToManyRelations() as $relation)
    {
      $names[] = array($relation['table']->getOption('name'), $relation['alias'], false, true);
    }

    return $names;
  }

  /**
   * Returns the first primary key column of the current table.
   *
   * @param ColumnMap A ColumnMap object
   */
  public function getPrimaryKey()
  {
    foreach ($this->getColumns() as $column)
    {
      if ($column->isPrimaryKey())
      {
        return $column;
      }
    }
  }

  /**
   * Returns a sfWidgetForm class name for a given column.
   *
   * @param  sfDoctrineColumn $column
   * @return string    The name of a subclass of sfWidgetForm
   */
  public function getWidgetClassForColumn($column)
  {
    switch ($column->getDoctrineType())
    {
      case 'string':
        if ($column->getLength() > 255)
        {
          $widgetSubclass = 'Textarea';
        } else {
          $widgetSubclass = 'Input';
        }
        break;
      case 'boolean':
        $widgetSubclass = 'InputCheckbox';
        break;
      case 'blob':
      case 'clob':
        $widgetSubclass = 'Textarea';
        break;
      case 'date':
        $widgetSubclass = 'Date';
        break;
      case 'time':
        $widgetSubclass = 'Time';
        break;
      case 'timestamp':
        $widgetSubclass = 'DateTime';
        break;
      case 'enum':
        $widgetSubclass = 'Choice';
        break;
      default:
        $widgetSubclass = 'Input';
    }

    if ($column->isPrimaryKey())
    {
      $widgetSubclass = 'InputHidden';
    }
    else if ($column->isForeignKey())
    {
      $widgetSubclass = 'DoctrineChoice';
    }

    return sprintf('sfWidgetForm%s', $widgetSubclass);
  }

  /**
   * Returns a PHP string representing options to pass to a widget for a given column.
   *
   * @param  sfDoctrineColumn $column
   * @return string    The options to pass to the widget as a PHP string
   */
  public function getWidgetOptionsForColumn($column)
  {
    $options = array();

    if ($column->isForeignKey())
    {
      $options[] = sprintf('\'model\' => \'%s\', \'add_empty\' => %s', $column->getForeignTable()->getOption('name'), $column->isNotNull() ? 'false' : 'true');
    }
    else
    {
      switch ($column->getDoctrineType())
      {
        case 'enum':
          $values = $column->getDefinitionKey('values');
          $values = array_combine($values, $values);
          $options[] = "'choices' => " . str_replace("\n", '', $this->arrayExport($values));
          break;
      }
    }

    return count($options) ? sprintf('array(%s)', implode(', ', $options)) : '';
  }

  /**
   * Returns a sfValidator class name for a given column.
   *
   * @param sfDoctrineColumn $column
   * @return string    The name of a subclass of sfValidator
   */
  public function getValidatorClassForColumn($column)
  {
    switch ($column->getDoctrineType())
    {
      case 'boolean':
        $validatorSubclass = 'Boolean';
        break;
      case 'string':
    		if ($column->getDefinitionKey('email'))
    		{
    		  $validatorSubclass = 'Email';
    		}
    		else if ($column->getDefinitionKey('regexp'))
    		{
    		  $validatorSubclass = 'Regex';
    		}
    		else
    		{
    		  $validatorSubclass = 'String';
    		}
        break;
      case 'clob':
      case 'blob':
        $validatorSubclass = 'String';
        break;
      case 'float':
      case 'decimal':
        $validatorSubclass = 'Number';
        break;
      case 'integer':
        $validatorSubclass = 'Integer';
        break;
      case 'date':
        $validatorSubclass = 'Date';
        break;
      case 'time':
        $validatorSubclass = 'Time';
        break;
      case 'timestamp':
        $validatorSubclass = 'DateTime';
        break;
      case 'enum':
        $validatorSubclass = 'Choice';
        break;
      default:
        $validatorSubclass = 'Pass';
    }

    if ($column->isPrimaryKey() || $column->isForeignKey())
    {
      $validatorSubclass = 'DoctrineChoice';
    }

    return sprintf('sfValidator%s', $validatorSubclass);
  }

  /**
   * Returns a PHP string representing options to pass to a validator for a given column.
   *
   * @param sfDoctrineColumn $column
   * @return string    The options to pass to the validator as a PHP string
   */
  public function getValidatorOptionsForColumn($column)
  {
    $options = array();

    if ($column->isForeignKey())
    {
      $options[] = sprintf('\'model\' => \'%s\'', $column->getForeignTable()->getOption('name'));
    }
    else if ($column->isPrimaryKey())
    {
      $options[] = sprintf('\'model\' => \'%s\', \'column\' => \'%s\'', $this->modelName, $column->getName());
    }
    else
    {
      switch ($column->getDoctrineType())
      {
        case 'string':
          if ($column['length'])
          {
            $options[] = sprintf('\'max_length\' => %s', $column['length']);
          }
          if (isset($column['minlength']))
          {
            $options[] = sprintf('\'min_length\' => %s', $column['minlength']);
          }
          if (isset($column['regexp']))
          {
            $options[] = sprintf('\'pattern\' => \'%s\'', $column['regexp']);
          }
          break;
        case 'enum':
          $values = array_combine($column['values'], $column['values']);
          $options[] = "'choices' => " . str_replace("\n", '', $this->arrayExport($values));
          break;
      }
    }

    if (!$column->isNotNull() || $column->isPrimaryKey())
    {
      $options[] = '\'required\' => false';
    }

    return count($options) ? sprintf('array(%s)', implode(', ', $options)) : '';
  }

  /**
   * Returns the maximum length for a column name.
   *
   * @return integer The length of the longer column name
   */
  public function getColumnNameMaxLength()
  {
    $max = 0;
    foreach ($this->getColumns() as $column)
    {
      if (($m = strlen($column->getFieldName())) > $max)
      {
        $max = $m;
      }
    }

    foreach ($this->getManyToManyRelations() as $relation)
    {
      if (($m = strlen($this->underscore($relation['alias']).'_list')) > $max)
      {
        $max = $m;
      }
    }

    return $max;
  }

  /**
   * Returns an array of primary key column names.
   *
   * @return array An array of primary key column names
   */
  public function getPrimaryKeyColumNames()
  {
    return $this->table->getIdentifierColumnNames();
  }

  /**
   * Returns a PHP string representation for the array of all primary key column names.
   *
   * @return string A PHP string representation for the array of all primary key column names
   *
   * @see getPrimaryKeyColumNames()
   */
  public function getPrimaryKeyColumNamesAsString()
  {
    return sprintf('array(\'%s\')', implode('\', \'', $this->getPrimaryKeyColumNames()));
  }

  /**
   * Returns true if the current table is internationalized.
   *
   * @return Boolean true if the current table is internationalized, false otherwise
   */
  public function isI18n()
  {
    return $this->table->hasRelation('Translation');
  }

  /**
   * Returns the i18n model name for the current table.
   *
   * @return string The model class name
   */
  public function getI18nModel()
  {
    return $this->table->getRelation('Translation')->getTable()->create();
  }

  public function underscore($name)
  {
    return strtolower(preg_replace(array('/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'), '\\1_\\2', $name));
  }

  /**
   * Get array of sfDoctrineColumn objects
   *
   * @return array $columns
   */
  public function getColumns()
  {
    foreach (array_keys($this->table->getColumns()) as $name)
    {
      $columns[] = new sfDoctrineColumn($name, $this->table);
    }

    return $columns;
  }

  public function getUniqueColumnNames()
  {
    $uniqueColumns = array();

    foreach ($this->getColumns() as $column)
    {
      if ($column->getDefinitionKey('unique'))
      {
        $uniqueColumns[] = array($column->getName());
      }
    }

    $indexes = $this->table->getOption('indexes');
    foreach ($indexes as $name => $index)
    {
      if (isset($index['type']) && $index['type'] == 'unique')
      {
        $tmp = $index['fields'];
        if (is_array(array_shift($tmp)))
        {
          $uniqueColumns[] = array_keys($index['fields']);
        } else {
          $uniqueColumns[] = $index['fields'];
        }
      }
    }

    return $uniqueColumns;
  }

  /**
   * Loads all Doctrine builders.
   */
  protected function loadModels()
  {
    Doctrine::loadModels($this->generatorManager->getConfiguration()->getModelDirs(),
                                   Doctrine::MODEL_LOADING_CONSERVATIVE);
    $models = Doctrine::getLoadedModels();
    $models =  Doctrine::initializeModels($models);
    $models = Doctrine::filterInvalidModels($models);
    $this->models = $this->filterModels($models);

    return $this->models;
  }

  /**
   * Filter out models that have disabled generation of form classes
   *
   * @return array $models Array of models to generate forms for
   */
  protected function filterModels($models)
  {
    foreach ($models as $key => $model)
    {
      $table = Doctrine::getTable($model);
      $symfonyOptions = $table->getOption('symfony');

      if (isset($symfonyOptions['form']) && !$symfonyOptions['form'])
      {
        unset($models[$key]);
      }
    }

    return $models;
  }

  /**
   * Array export. Export array to formatted php code
   *
   * @param array $values
   * @return string $php
   */
  protected function arrayExport($values)
  {
    $php = var_export($values, true);
    $php = str_replace("\n", '', $php);
    $php = str_replace('array (  ', 'array(', $php);
    $php = str_replace(',)', ')', $php);
    $php = str_replace('  ', ' ', $php);
    return $php;
  }

  /**
   * Get the name of the form class to extend based on the inheritance of the model
   *
   * @return string
   */
  public function getFormClassToExtend()
  {
    $parentName = $this->modelName;
    while (true)
    {
      $reflectionClass = new ReflectionClass($parentName);
      $parent = $reflectionClass->getParentClass();
      $parentName = $parent->getName();

      if (preg_match('/^Base/', $parentName))
      {
        $reflectionClass = new ReflectionClass($parentName);
        $parent = $reflectionClass->getParentClass();
        $parentName = $parent->getName();

        if ($parentName == 'sfDoctrineRecord')
        {
          return 'BaseFormDoctrine';
        }
        else if (!$parent->isAbstract())
        {
          return $parentName.'Form';
        }

        return 'BaseFormDoctrine';
      }
      else if ($parentName == 'Doctrine_Record')
      {
        return 'BaseFormDoctrine';
      }
    }
  }
}