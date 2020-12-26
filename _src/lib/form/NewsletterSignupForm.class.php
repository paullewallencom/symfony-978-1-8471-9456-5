<?php

/**
 * NewsletterSignup form.
 *
 * @package    milkshake
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class NewsletterSignupForm extends BaseNewsletterSignupForm
{
  public function configure()
  {
    //Removed unneeded widgets
    unset(
      $this['created_at'], $this['updated_at'],
      $this['activation_key'], $this['activated'], $this['id']
    );


    //Set widgets

	//Modify widgets
    $this->widgetSchema['first_name'] = new sfWidgetFormInput();
    $this->widgetSchema['newsletter_adverts_id'] = new sfWidgetFormPropelChoice(array('model' => 'NewsletterAds', 'add_empty' => true, 'label'=>'Where did you find us?'));
    $this->widgetSchema['email'] = new sfWidgetFormInput(array('label' => 'Email Address'));

    //Add validation
    $this->setValidators(array(
      'first_name'     => new sfValidatorString(array('required' => true), array('required' => 'Enter your firstname')),
      'surname'        => new sfValidatorString(array('required' => true), array('required' => 'Enter your surname')),
      'email'        => new sfValidatorEmail(array('required' => true), array('invalid' => 'Provide a valid email', 'required' => 'Enter your email')),
      'newsletter_adverts_id' => new sfValidatorPropelChoice(array('model' => 'NewsletterAds', 'column' => 'newsletter_adverts_id'), array('required' => 'Select where you found us')),
    ));

    //Set post validators
    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'NewsletterSignup', 'column' => array('email')), array('invalid' => 'Email address is already registered'))
    );

    //Set form name
    $this->widgetSchema->setNameFormat('newsletter_signup[%s]');

    //Set the form format
    $this->widgetSchema->setFormFormatterName('div');
  }
}
