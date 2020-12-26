<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * VacancyI18n filter form base class.
 *
 * @package    milkshake
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseVacancyI18nFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'position'             => new sfWidgetFormFilterInput(),
      'position_description' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'position'             => new sfValidatorPass(array('required' => false)),
      'position_description' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('vacancy_i18n_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'VacancyI18n';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'ForeignKey',
      'culture'              => 'Text',
      'position'             => 'Text',
      'position_description' => 'Text',
    );
  }
}
