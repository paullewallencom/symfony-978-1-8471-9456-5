<?php

/**
 * Vacancy form base class.
 *
 * @package    milkshake
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseVacancyForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'locations_id' => new sfWidgetFormPropelChoice(array('model' => 'StoreLocation', 'add_empty' => false)),
      'closing_date' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorPropelChoice(array('model' => 'Vacancy', 'column' => 'id', 'required' => false)),
      'locations_id' => new sfValidatorPropelChoice(array('model' => 'StoreLocation', 'column' => 'id')),
      'closing_date' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('vacancy[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Vacancy';
  }

  public function getI18nModelName()
  {
    return 'VacancyI18n';
  }

  public function getI18nFormClass()
  {
    return 'VacancyI18nForm';
  }

}
