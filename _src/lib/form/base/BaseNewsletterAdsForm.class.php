<?php

/**
 * NewsletterAds form base class.
 *
 * @package    milkshake
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseNewsletterAdsForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'newsletter_adverts_id' => new sfWidgetFormInputHidden(),
      'advertised'            => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'newsletter_adverts_id' => new sfValidatorPropelChoice(array('model' => 'NewsletterAds', 'column' => 'newsletter_adverts_id', 'required' => false)),
      'advertised'            => new sfValidatorString(array('max_length' => 30)),
    ));

    $this->widgetSchema->setNameFormat('newsletter_ads[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'NewsletterAds';
  }


}
