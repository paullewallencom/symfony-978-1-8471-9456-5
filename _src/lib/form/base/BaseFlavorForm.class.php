<?php

/**
 * Flavor form base class.
 *
 * @package    milkshake
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseFlavorForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'name'                  => new sfWidgetFormInput(),
      'created_at'            => new sfWidgetFormDateTime(),
      'milkshake_flavor_list' => new sfWidgetFormPropelChoiceMany(array('model' => 'Milkshake')),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorPropelChoice(array('model' => 'Flavor', 'column' => 'id', 'required' => false)),
      'name'                  => new sfValidatorString(array('max_length' => 20)),
      'created_at'            => new sfValidatorDateTime(),
      'milkshake_flavor_list' => new sfValidatorPropelChoiceMany(array('model' => 'Milkshake', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('flavor[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Flavor';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['milkshake_flavor_list']))
    {
      $values = array();
      foreach ($this->object->getMilkshakeFlavors() as $obj)
      {
        $values[] = $obj->getMilkshakeId();
      }

      $this->setDefault('milkshake_flavor_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveMilkshakeFlavorList($con);
  }

  public function saveMilkshakeFlavorList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['milkshake_flavor_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(MilkshakeFlavorPeer::FLAVOR_ID, $this->object->getPrimaryKey());
    MilkshakeFlavorPeer::doDelete($c, $con);

    $values = $this->getValue('milkshake_flavor_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new MilkshakeFlavor();
        $obj->setFlavorId($this->object->getPrimaryKey());
        $obj->setMilkshakeId($value);
        $obj->save();
      }
    }
  }

}
