<?php

/**
 * MilkshakeFlavor form base class.
 *
 * @package    milkshake
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseMilkshakeFlavorForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'milkshake_id' => new sfWidgetFormInputHidden(),
      'flavor_id'    => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'milkshake_id' => new sfValidatorPropelChoice(array('model' => 'Milkshake', 'column' => 'id', 'required' => false)),
      'flavor_id'    => new sfValidatorPropelChoice(array('model' => 'Flavor', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('milkshake_flavor[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MilkshakeFlavor';
  }


}
