<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWebDebugPanelView adds a panel to the web debug toolbar with information about the view layer.
 * 
 * @package     symfony
 * @subpackage  debug
 * @author      Kris Wallsmith <kris.wallsmith@symfony-project.com>
 * @version     SVN: $Id: sfWebDebugPanelView.class.php 19774 2009-07-01 09:46:42Z Kris.Wallsmith $
 */
class sfWebDebugPanelView extends sfWebDebugPanel
{
  protected
    $actions  = array(),
    $partials = array();

  /**
   * Constructor.
   *
   * @param sfWebDebug $webDebug The web debut toolbar instance
   */
  public function __construct(sfWebDebug $webDebug)
  {
    parent::__construct($webDebug);

    $this->webDebug->getEventDispatcher()->connect('controller.change_action', array($this, 'listenForChangeAction'));
    $this->webDebug->getEventDispatcher()->connect('template.filter_parameters', array($this, 'filterTemplateParameters'));
  }

  /**
   * Resets the parameter collections.
   * 
   * @param sfEvent $event
   */
  public function listenForChangeAction(sfEvent $event)
  {
    $this->actions  = array();
    $this->partials = array();
  }

  /**
   * Stacks action and partial parameters in the template.filter_parameters event.
   * 
   * @param  sfEvent $event
   * @param  array   $parameters
   * 
   * @return array
   */
  public function filterTemplateParameters(sfEvent $event, $parameters)
  {
    $entry = array('parameters' => $parameters);

    if ('action' == $parameters['sf_type'] && $file = $this->getLastTemplate())
    {
      $this->actions[] = $entry + array('file' => $file);
    }
    else if ('partial' == $parameters['sf_type'] && $file = $this->getLastTemplate('sfPartialView'))
    {
      $this->partials[] = $entry + array('file' => $file);
    }

    return $parameters;
  }

  /**
   * Returns the path to the last template rendered.
   * 
   * @param  string $class Name of the rendering view class
   * 
   * @return string|null
   */
  protected function getLastTemplate($class = 'sfPHPView')
  {
    foreach (array_reverse($this->webDebug->getLogger()->getLogs()) as $log)
    {
      if (
        ($class == $log['type'] || is_subclass_of($log['type'], $class))
        &&
        preg_match('/^Render "(.*)"$/', $log['message'], $match)
      )
      {
        return $match[1];
      }
    }
  }

  /**
   * @see sfWebDebugPanel
   */
  public function getTitle()
  {
    return 'view';
  }

  /**
   * @see sfWebDebugPanel
   */
  public function getPanelTitle()
  {
    return 'View Layer';
  }

  /**
   * @see sfWebDebugPanel
   */
  public function getPanelContent()
  {
    $html = array();

    foreach ($this->actions as $action)
    {
      $html[] = $this->renderTemplateInformation($action['file'], $action['parameters']);
    }

    foreach ($this->partials as $partial)
    {
      $html[] = $this->renderTemplateInformation($partial['file'], $partial['parameters'], 'Partial');
    }

    return join("\n", $html);
  }

  /**
   * Renders information about the passed template and its parameters.
   * 
   * @param  string $file       The template file path
   * @param  array  $parameters
   * @param  string $label
   * 
   * @return string
   */
  protected function renderTemplateInformation($file, $parameters, $label = 'Template')
  {
    static $i = 0;

    $parameters = $this->filterCoreParameters($parameters);
    $i++;

    $html = array();
    $html[] = sprintf('<h2>%s: %s %s</h2>', $label, $this->formatFileLink($file, null, $this->shortenTemplatePath($file)), $this->getToggler('sfWebDebugViewTemplate'.$i));
    $html[] = '<div id="sfWebDebugViewTemplate'.$i.'" style="display:'.(1 == $i ? 'block' : 'none').'">';
    if (count($parameters))
    {
      $html[] = '<p>Parameters:</p>';
      $html[] = '<ul>';
      foreach ($parameters as $name => $parameter)
      {
        $presentation = '<li>'.$this->formatParameterAsHtml($name, $parameter).'</li>';
        $html[] = $this->webDebug->getEventDispatcher()->filter(new sfEvent($this, 'debug.web.view.filter_parameter_html', array('parameter' => $parameter)), $presentation)->getReturnValue();
      }
      $html[] = '</ul>';
    }
    else
    {
      $html[] = '<p>No parameters were passed to this template.</p>';
    }
    $html[] = '</div>';

    return join("\n", $html);
  }

  /**
   * Formats information about a parameter as HTML.
   * 
   * @param  string $name
   * @param  mixed  $parameter
   * 
   * @return string
   */
  protected function formatParameterAsHtml($name, $parameter)
  {
    if (!method_exists($this, $method = 'format'.ucwords(gettype($parameter)).'AsHtml'))
    {
      $method = 'getParameterDescription';
    }

    return $this->$method($name, $parameter);
  }

  /**
   * Formats object information as HTML.
   * 
   * @param  string $name
   * @param  object $parameter
   * 
   * @return string
   */
  protected function formatObjectAsHtml($name, $parameter)
  {
    if ($parameter instanceof sfForm)
    {
      return $this->formatFormAsHtml($name, $parameter);
    }
    else
    {
      return $this->getParameterDescription($name, $parameter);
    }
  }

  /**
   * Formats form information as HTML.
   * 
   * @param  string $name
   * @param  sfForm $form
   * 
   * @return string
   */
  protected function formatFormAsHtml($name, sfForm $form)
  {
    static $i = 0;

    $i++;

    $html = array();
    $html[] = $this->getParameterDescription($name, $form);
    $html[] = $this->getToggler('sfWebDebugViewForm'.$i);
    $html[] = '<div id="sfWebDebugViewForm'.$i.'" style="display:none">';
    $html[] = '<ul>'.$this->formatFormFieldSchemaAsHtml($form->getFormFieldSchema(), $name.'[%s]').'</ul>';
    $html[] = '</div>';

    return join("\n", $html);
  }

  /**
   * Formats form field schema information as HTML.
   * 
   * @param  sfFormFieldSchema $fieldSchema
   * @param  string            $nameFormat
   * 
   * @return string
   */
  protected function formatFormFieldSchemaAsHtml(sfFormFieldSchema $fieldSchema, $nameFormat = '%s')
  {
    $html = array();

    foreach ($fieldSchema as $field)
    {
      $name = sprintf($nameFormat, $this->varExport($field->getName()));
      if ($field instanceof sfFormFieldSchema)
      {
        $html[] = $this->formatFormFieldSchemaAsHtml($field, $name.'[%s]');
      }
      else
      {
        $html[] = '<li>'.$this->getParameterDescription($name, $field->getWidget()).'</li>';
      }
    }

    return join("\n", $html);
  }

  /**
   * Formats information about a parameter as HTML.
   * 
   * @param  string $name
   * @param  mixed  $parameter
   * 
   * @return string
   */
  protected function getParameterDescription($name, $parameter)
  {
    return sprintf('<code>$%s</code> <span class="sfWebDebugDataType">(%s)</span>', $name, is_object($parameter) ? $this->formatFileLink(get_class($parameter)) : gettype($parameter));
  }

  /**
   * Shortens an action's template path.
   * 
   * @param  string $path
   * 
   * @return string
   */
  protected function shortenTemplatePath($path)
  {
    $path = realpath($path);

    // application module
    if (preg_match('#apps'.DIRECTORY_SEPARATOR.'\w+'.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'(\w+)'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'(.*)$#', $path, $match))
    {
      return $match[1].'/&hellip;/'.$match[2];
    }

    return $path;
  }

  /**
   * Removes parameters prefixed with "sf_" from the array.
   * 
   * @param  array $parameters
   * 
   * @return array
   */
  protected function filterCoreParameters($parameters)
  {
    $filtered = array();

    foreach ($parameters as $name => $value)
    {
      if (0 !== strpos($name, 'sf_'))
      {
        $filtered[$name] = $value;
      }
    }

    return $filtered;
  }

  /**
   * Returns a string representation of a value.
   * 
   * @param  string $value
   * 
   * @return string
   */
  protected function varExport($value)
  {
    if (is_numeric($value))
    {
      $value = (integer) $value;
    }

    return var_export($value, true);
  }
}
