<?php

/**
 * Milkshake form.
 *
 * @package    milkshake
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class MilkshakeForm extends BaseMilkshakeForm
{
  public function configure()
  {
    // removing fields we don't want to display
    unset($this['thumb_url']);
    unset($this['views']);

    // setting image_url as input type="file"
    $this->widgetSchema['image_url'] = new sfWidgetFormInputFile();
    
    // setting AdminDoubleList View
    $this->widgetSchema['milkshake_flavor_list']
      ->setOption('renderer_class', 'sfWidgetFormSelectDoubleList');

    // setting validator for file upload
    $this->validatorSchema['image_url'] = new sfValidatorFile(array(
      'path' => sfConfig::get('sf_upload_dir').'/milkshakes',
      'required' => true
    ));
  }
}
