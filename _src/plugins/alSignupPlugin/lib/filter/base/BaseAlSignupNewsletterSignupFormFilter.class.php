<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * AlSignupNewsletterSignup filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseAlSignupNewsletterSignupFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'first_name'            => new sfWidgetFormFilterInput(),
      'surname'               => new sfWidgetFormFilterInput(),
      'email'                 => new sfWidgetFormFilterInput(),
      'activation_key'        => new sfWidgetFormFilterInput(),
      'activated'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'newsletter_adverts_id' => new sfWidgetFormPropelChoice(array('model' => 'AlSignupNewsletterAds', 'add_empty' => true)),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'first_name'            => new sfValidatorPass(array('required' => false)),
      'surname'               => new sfValidatorPass(array('required' => false)),
      'email'                 => new sfValidatorPass(array('required' => false)),
      'activation_key'        => new sfValidatorPass(array('required' => false)),
      'activated'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'newsletter_adverts_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'AlSignupNewsletterAds', 'column' => 'newsletter_adverts_id')),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('al_signup_newsletter_signup_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'AlSignupNewsletterSignup';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'first_name'            => 'Text',
      'surname'               => 'Text',
      'email'                 => 'Text',
      'activation_key'        => 'Text',
      'activated'             => 'Boolean',
      'newsletter_adverts_id' => 'ForeignKey',
      'created_at'            => 'Date',
      'updated_at'            => 'Date',
    );
  }
}
