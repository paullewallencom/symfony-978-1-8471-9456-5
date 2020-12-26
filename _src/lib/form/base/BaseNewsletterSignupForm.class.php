<?php

/**
 * NewsletterSignup form base class.
 *
 * @package    milkshake
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseNewsletterSignupForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'first_name'            => new sfWidgetFormInput(),
      'surname'               => new sfWidgetFormInput(),
      'email'                 => new sfWidgetFormInput(),
      'activation_key'        => new sfWidgetFormInput(),
      'activated'             => new sfWidgetFormInputCheckbox(),
      'newsletter_adverts_id' => new sfWidgetFormPropelChoice(array('model' => 'NewsletterAds', 'add_empty' => false)),
      'created_at'            => new sfWidgetFormDateTime(),
      'updated_at'            => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorPropelChoice(array('model' => 'NewsletterSignup', 'column' => 'id', 'required' => false)),
      'first_name'            => new sfValidatorString(array('max_length' => 20)),
      'surname'               => new sfValidatorString(array('max_length' => 20)),
      'email'                 => new sfValidatorString(array('max_length' => 100)),
      'activation_key'        => new sfValidatorString(array('max_length' => 100)),
      'activated'             => new sfValidatorBoolean(),
      'newsletter_adverts_id' => new sfValidatorPropelChoice(array('model' => 'NewsletterAds', 'column' => 'newsletter_adverts_id')),
      'created_at'            => new sfValidatorDateTime(),
      'updated_at'            => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('newsletter_signup[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'NewsletterSignup';
  }


}
