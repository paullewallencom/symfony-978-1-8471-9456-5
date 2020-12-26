<?php

/**
 * AlSignupNewsletterAds form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseAlSignupNewsletterAdsForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'newsletter_adverts_id' => new sfWidgetFormInputHidden(),
      'advertised'            => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'newsletter_adverts_id' => new sfValidatorPropelChoice(array('model' => 'AlSignupNewsletterAds', 'column' => 'newsletter_adverts_id', 'required' => false)),
      'advertised'            => new sfValidatorString(array('max_length' => 30)),
    ));

    $this->widgetSchema->setNameFormat('al_signup_newsletter_ads[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'AlSignupNewsletterAds';
  }


}
