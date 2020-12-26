<?php

/**
 * StoreLocation form base class.
 *
 * @package    milkshake
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseStoreLocationForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'       => new sfWidgetFormInputHidden(),
      'address1' => new sfWidgetFormInput(),
      'address2' => new sfWidgetFormInput(),
      'address3' => new sfWidgetFormInput(),
      'postcode' => new sfWidgetFormInput(),
      'city'     => new sfWidgetFormInput(),
      'country'  => new sfWidgetFormInput(),
      'phone'    => new sfWidgetFormInput(),
      'fax'      => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'       => new sfValidatorPropelChoice(array('model' => 'StoreLocation', 'column' => 'id', 'required' => false)),
      'address1' => new sfValidatorString(array('max_length' => 100)),
      'address2' => new sfValidatorString(array('max_length' => 100)),
      'address3' => new sfValidatorString(array('max_length' => 50)),
      'postcode' => new sfValidatorString(array('max_length' => 8)),
      'city'     => new sfValidatorString(array('max_length' => 50)),
      'country'  => new sfValidatorString(array('max_length' => 50)),
      'phone'    => new sfValidatorString(array('max_length' => 20)),
      'fax'      => new sfValidatorString(array('max_length' => 20)),
    ));

    $this->widgetSchema->setNameFormat('store_location[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'StoreLocation';
  }


}
