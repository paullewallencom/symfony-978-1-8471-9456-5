<?php

/**
 * Milkshake form base class.
 *
 * @package    milkshake
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseMilkshakeForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'name'                  => new sfWidgetFormInput(),
      'image_url'             => new sfWidgetFormInput(),
      'thumb_url'             => new sfWidgetFormInput(),
      'calories'              => new sfWidgetFormInput(),
      'created_at'            => new sfWidgetFormDateTime(),
      'updated_at'            => new sfWidgetFormDateTime(),
      'url_slug'              => new sfWidgetFormInput(),
      'views'                 => new sfWidgetFormInput(),
      'milkshake_flavor_list' => new sfWidgetFormPropelChoiceMany(array('model' => 'Flavor')),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorPropelChoice(array('model' => 'Milkshake', 'column' => 'id', 'required' => false)),
      'name'                  => new sfValidatorString(array('max_length' => 100)),
      'image_url'             => new sfValidatorString(array('max_length' => 255)),
      'thumb_url'             => new sfValidatorString(array('max_length' => 255)),
      'calories'              => new sfValidatorNumber(),
      'created_at'            => new sfValidatorDateTime(),
      'updated_at'            => new sfValidatorDateTime(),
      'url_slug'              => new sfValidatorString(array('max_length' => 100)),
      'views'                 => new sfValidatorInteger(array('required' => false)),
      'milkshake_flavor_list' => new sfValidatorPropelChoiceMany(array('model' => 'Flavor', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('milkshake[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Milkshake';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['milkshake_flavor_list']))
    {
      $values = array();
      foreach ($this->object->getMilkshakeFlavors() as $obj)
      {
        $values[] = $obj->getFlavorId();
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
    $c->add(MilkshakeFlavorPeer::MILKSHAKE_ID, $this->object->getPrimaryKey());
    MilkshakeFlavorPeer::doDelete($c, $con);

    $values = $this->getValue('milkshake_flavor_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new MilkshakeFlavor();
        $obj->setMilkshakeId($this->object->getPrimaryKey());
        $obj->setFlavorId($value);
        $obj->save();
      }
    }
  }

}
