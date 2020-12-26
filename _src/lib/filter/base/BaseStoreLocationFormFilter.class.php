<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * StoreLocation filter form base class.
 *
 * @package    milkshake
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseStoreLocationFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'address1' => new sfWidgetFormFilterInput(),
      'address2' => new sfWidgetFormFilterInput(),
      'address3' => new sfWidgetFormFilterInput(),
      'postcode' => new sfWidgetFormFilterInput(),
      'city'     => new sfWidgetFormFilterInput(),
      'country'  => new sfWidgetFormFilterInput(),
      'phone'    => new sfWidgetFormFilterInput(),
      'fax'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'address1' => new sfValidatorPass(array('required' => false)),
      'address2' => new sfValidatorPass(array('required' => false)),
      'address3' => new sfValidatorPass(array('required' => false)),
      'postcode' => new sfValidatorPass(array('required' => false)),
      'city'     => new sfValidatorPass(array('required' => false)),
      'country'  => new sfValidatorPass(array('required' => false)),
      'phone'    => new sfValidatorPass(array('required' => false)),
      'fax'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('store_location_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'StoreLocation';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'address1' => 'Text',
      'address2' => 'Text',
      'address3' => 'Text',
      'postcode' => 'Text',
      'city'     => 'Text',
      'country'  => 'Text',
      'phone'    => 'Text',
      'fax'      => 'Text',
    );
  }
}
