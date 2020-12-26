<?php

/**
 * VacancyI18n form base class.
 *
 * @package    milkshake
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseVacancyI18nForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'culture'              => new sfWidgetFormInputHidden(),
      'position'             => new sfWidgetFormInput(),
      'position_description' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorPropelChoice(array('model' => 'Vacancy', 'column' => 'id', 'required' => false)),
      'culture'              => new sfValidatorPropelChoice(array('model' => 'VacancyI18n', 'column' => 'culture', 'required' => false)),
      'position'             => new sfValidatorString(array('max_length' => 30)),
      'position_description' => new sfValidatorString(array('max_length' => 100)),
    ));

    $this->widgetSchema->setNameFormat('vacancy_i18n[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'VacancyI18n';
  }


}
