<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * AlSignupNewsletterAds filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseAlSignupNewsletterAdsFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'advertised'            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'advertised'            => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('al_signup_newsletter_ads_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'AlSignupNewsletterAds';
  }

  public function getFields()
  {
    return array(
      'newsletter_adverts_id' => 'Number',
      'advertised'            => 'Text',
    );
  }
}
