<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * NewsletterAds filter form base class.
 *
 * @package    milkshake
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseNewsletterAdsFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'advertised'            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'advertised'            => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('newsletter_ads_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'NewsletterAds';
  }

  public function getFields()
  {
    return array(
      'newsletter_adverts_id' => 'Number',
      'advertised'            => 'Text',
    );
  }
}
