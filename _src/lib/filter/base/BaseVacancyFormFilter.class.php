<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Vacancy filter form base class.
 *
 * @package    milkshake
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseVacancyFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'locations_id' => new sfWidgetFormPropelChoice(array('model' => 'StoreLocation', 'add_empty' => true)),
      'closing_date' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'locations_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'StoreLocation', 'column' => 'id')),
      'closing_date' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('vacancy_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Vacancy';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'locations_id' => 'ForeignKey',
      'closing_date' => 'Date',
    );
  }
}
