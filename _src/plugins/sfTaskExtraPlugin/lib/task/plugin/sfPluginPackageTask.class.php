<?php

/**
 * Packages a plugin.
 * 
 * @package     sfTaskExtraPlugin
 * @subpackage  task
 * @author      Kris Wallsmith <kris.wallsmith@symfony-project.com>
 * @version     SVN: $Id: sfPluginPackageTask.class.php 12765 2008-11-08 12:05:17Z Kris.Wallsmith $
 */
class sfPluginPackageTask extends sfTaskExtraPluginBaseTask
{
  protected
    $pluginDir   = null,
    $interactive = true;

  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('plugin', sfCommandArgument::REQUIRED, 'The plugin name'),
    ));

    $this->addOptions(array(
      new sfCommandOption('plugin-version', null, sfCommandOption::PARAMETER_REQUIRED, 'The plugin version'),
      new sfCommandOption('plugin-stability', null, sfCommandOption::PARAMETER_REQUIRED, 'The plugin stability'),
      new sfCommandOption('non-interactive', null, sfCommandOption::PARAMETER_NONE, 'Skip interactive prompts'),
      new sfCommandOption('nocompress', null, sfCommandOption::PARAMETER_NONE, 'Do not compress the package'),
    ));

    $this->namespace = 'plugin';
    $this->name = 'package';

    $this->briefDescription = 'Create a plugin PEAR package';

    $this->detailedDescription = <<<EOF
The [plugin:package|INFO] task creates a plugin PEAR package:

  [./symfony plugin:package sfExamplePlugin|INFO]

If your plugin includes a package.xml file, it will be used. If not, the task
will look for a package.xml.tmpl file in your plugin and use either that or a
default template to dynamically generate your package.xml file.

You can either edit your plugin's package.xml.tmpl file or use the
[--plugin-version|COMMENT] or [--plugin-stability|COMMENT] options to set the
release version and stability, respectively:

  [./symfony plugin:package sfExamplePlugin --plugin-version=0.5.0 --plugin-stability=alpha|INFO]

To disable any interactive prompts in the packaging process, include the
[--non-interactive|COMMENT] option:

  [./symfony plugin:package sfExamplePlugin --non-interactive|INFO]

To disable compression of the package tar, use the [--nocompress|COMMENT]
option:

  [./symfony plugin:package sfExamplePlugin --nocompress|INFO]
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $this->checkPluginExists($arguments['plugin']);

    $this->pluginDir = sfConfig::get('sf_plugins_dir').'/'.$arguments['plugin'];
    $this->interactive = !$options['non-interactive'];

    $cleanup = array();

    if (!file_exists($this->pluginDir.'/package.xml'))
    {
      $cleanup['package_file'] = true;
      $this->generatePackageFile($arguments, $options);
    }

    $cwd = getcwd();
    chdir($this->pluginDir);

    $this->getPluginManager()->configure();

    require_once 'PEAR/Packager.php';
    $packager = new PEAR_Packager();
    $package = $packager->package($this->pluginDir.'/package.xml', !$options['nocompress']);

    chdir($cwd);

    if (PEAR::isError($package))
    {
      if (isset($cleanup['package_file']))
      {
        $cleanup['package_file'] = '.error';
      }
      $this->cleanup($cleanup);

      throw new sfCommandException($package->getMessage());
    }

    $this->cleanup($cleanup);
  }

  /**
   * Cleanup files.
   * 
   * Available options:
   * 
   *  * package_file
   * 
   * @param array $options
   */
  protected function cleanup(array $options = array())
  {
    $options = array_merge(array(
      'package_file' => false,
    ), $options);

    if ($extension = $options['package_file'])
    {
      if (is_string($extension))
      {
        $this->getFilesystem()->copy($this->pluginDir.'/package.xml', $this->pluginDir.'/package.xml'.$extension, array('override' => true));
      }

      $this->getFilesystem()->remove($this->pluginDir.'/package.xml');
    }
  }

  /**
   * Generates a package.xml file in the plugin directory.
   * 
   * @todo Move this into its own task
   */
  protected function generatePackageFile(array $arguments, array $options)
  {
    if (!file_exists($templatePath = $this->pluginDir.'/package.xml.tmpl'))
    {
      $templatePath = dirname(__FILE__).'/../generator/skeleton/plugin/plugin/package.xml.tmpl';
    }

    $template = file_get_contents($templatePath);
    $properties = parse_ini_file(sfConfig::get('sf_config_dir').'/properties.ini', true);

    $tokens = array(
      'PLUGIN_NAME'  => $arguments['plugin'],
      'CURRENT_DATE' => date('Y-m-d'),
    );

    if (false !== strpos($template, '##SUMMARY##'))
    {
      $tokens['SUMMARY'] = $this->askAndValidate('Summarize your plugin in one line:', new sfValidatorCallback(array(
        'required' => true,
        'callback' => create_function('$a, $b', 'return htmlentities($b);'),
      ), array(
        'required' => 'You must provide a summary of your plugin.',
      )), array(
        'value'    => isset($properties['symfony']['author']) ? $properties['symfony']['author'] : null,
      ));
    }

    if (false !== strpos($template, '##LEAD_NAME##'))
    {
      $tokens['LEAD_NAME'] = $this->askAndValidate('Lead developer name:', new sfValidatorString(array(), array(
        'required' => 'A lead developer name is required.',
      )), array(
        'value'    => isset($properties['symfony']['author']) ? $properties['symfony']['author'] : null,
      ));
    }

    if (false !== strpos($template, '##LEAD_EMAIL##'))
    {
      $tokens['LEAD_EMAIL'] = $this->askAndValidate('Lead developer email:', new sfValidatorEmail(array(), array(
        'required' => 'A valid lead developer email address is required.',
        'invalid'  => '"%value%" is not a valid email address.',
      )), array(
        'value'    =>isset($properties['symfony']['email']) ? $properties['symfony']['email'] : null,
      ));
    }

    if (false !== strpos($template, '##LEAD_USERNAME##'))
    {
      $tokens['LEAD_USERNAME'] = $this->askAndValidate('Lead developer username:', new sfValidatorString(array(), array(
        'required' => 'A lead developer username is required.'
      )), array(
        'value'    =>isset($properties['symfony']['username']) ? $properties['symfony']['username'] : null,
      ));
    }

    if (false !== strpos($template, '##PLUGIN_VERSION##'))
    {
      $tokens['PLUGIN_VERSION'] = $this->askAndValidate('Plugin version number (i.e. "1.0.5"):', new sfValidatorRegex(array(
        'pattern'  => '/\d+\.\d+\.\d+/',
      ), array(
        'required' => 'A valid version number is required.',
        'invalid'  => '"%value%" is not a valid version number.',
      )), array(
        'value'    => $options['plugin-version'],
      ));
      $tokens['API_VERSION'] = version_compare($tokens['PLUGIN_VERSION'], '0.1.0', '>') ? join('.', array_slice(explode('.', $tokens['PLUGIN_VERSION']), 0, 2)).'.0' : $tokens['PLUGIN_VERSION'];
    }

    if (false !== strpos($template, '##STABILITY##'))
    {
      $tokens['STABILITY'] = $this->askAndValidate('Plugin stability:', new sfValidatorChoice(array(
        'choices'  => $choices = array('devel', 'alpha', 'beta', 'stable'),
      ), array(
        'required' => 'A valid stability is required.',
        'invalid'  => '"%value%" is not a valid stability ('.join('|', $choices).').',
      )), array(
        'value'    => $options['plugin-stability'],
      ));
    }

    $finder = sfFinder::type('any')->maxdepth(0)->prune('test')->discard('test', 'package.xml.tmpl');
    $tokens['CONTENTS'] = $this->buildContents($this->pluginDir, $finder);

    $this->getFilesystem()->copy($templatePath, $this->pluginDir.'/package.xml');
    $this->getFilesystem()->replaceTokens($this->pluginDir.'/package.xml', '##', '##', $tokens);

    unset($tokens['CURRENT_DATE'], $tokens['PLUGIN_VERSION'], $tokens['API_VERSION'], $tokens['STABILITY'], $tokens['CONTENTS']);
    if ($tokens)
    {
      // create or update package.xml template
      $this->getFilesystem()->copy($templatePath, $this->pluginDir.'/package.xml.tmpl');
      $this->getFilesystem()->replaceTokens($this->pluginDir.'/package.xml.tmpl', '##', '##', $tokens);
    }
  }

  /**
   * Returns an XML string for the contents of the supplied directory.
   * 
   * @param   string           $directory
   * @param   sfFinder         $finder
   * @param   SimpleXMLElement $baseXml
   * 
   * @return  string
   */
  protected function buildContents($directory, sfFinder $finder = null, SimpleXMLElement $baseXml = null)
  {
    if (is_null($finder))
    {
      $finder = sfFinder::type('any')->maxdepth(0);
    }

    if (is_null($baseXml))
    {
      $baseXml = simplexml_load_string('<dir name="/"/>');
    }

    foreach ($finder->in($directory) as $entry)
    {
      if (is_dir($entry))
      {
        $entryXml = $baseXml->addChild('dir');
        $entryXml['name'] = basename($entry);

        $this->buildContents($entry, null, $entryXml);
      }
      else
      {
        $entryXml = $baseXml->addChild('file');
        $entryXml['name'] = basename($entry);
        $entryXml['role'] = 'data';
      }
    }

    $xml = $baseXml->asXml();

    // remove the xml declaration
    $xml = trim(substr($xml, strpos($xml, PHP_EOL)));

    return $xml;
  }

  /**
   * @see sfTaskExtraBaseTask
   */
  public function askAndValidate($question, sfValidatorBase $validator, array $options = array())
  {
    if ($this->interactive)
    {
      return sfTaskExtraBaseTask::doAskAndValidate($this, $question, $validator, $options);
    }
    else
    {
      return $validator->clean(isset($options['value']) ? $options['value'] : null);
    }
  }
}
