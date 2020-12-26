<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Milkshake filter form base class.
 *
 * @package    milkshake
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseMilkshakeFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                  => new sfWidgetFormFilterInput(),
      'image_url'             => new sfWidgetFormFilterInput(),
      'thumb_url'             => new sfWidgetFormFilterInput(),
      'calories'              => new sfWidgetFormFilterInput(),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'url_slug'              => new sfWidgetFormFilterInput(),
      'views'                 => new sfWidgetFormFilterInput(),
      'milkshake_flavor_list' => new sfWidgetFormPropelChoice(array('model' => 'Flavor', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'name'                  => new sfValidatorPass(array('required' => false)),
      'image_url'             => new sfValidatorPass(array('required' => false)),
      'thumb_url'             => new sfValidatorPass(array('required' => false)),
      'calories'              => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'url_slug'              => new sfValidatorPass(array('required' => false)),
      'views'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'milkshake_flavor_list' => new sfValidatorPropelChoice(array('model' => 'Flavor', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('milkshake_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addMilkshakeFlavorListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(MilkshakeFlavorPeer::MILKSHAKE_ID, MilkshakePeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(MilkshakeFlavorPeer::FLAVOR_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(MilkshakeFlavorPeer::FLAVOR_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'Milkshake';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'name'                  => 'Text',
      'image_url'             => 'Text',
      'thumb_url'             => 'Text',
      'calories'              => 'Number',
      'created_at'            => 'Date',
      'updated_at'            => 'Date',
      'url_slug'              => 'Text',
      'views'                 => 'Number',
      'milkshake_flavor_list' => 'ManyKey',
    );
  }
}
