<?php

class FeedbackForm extends BaseForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'name'    => new sfWidgetFormInput(),
      'email'   => new sfWidgetFormInput(),
      'message' => new sfWidgetFormTextarea(),
    ));
    //Add validation
    $this->setValidators(array(
      'name'     => new sfValidatorString(array('required' => true), array('required' => 'Enter your firstname')),
      'email'        => new sfValidatorEmail(array('required' => true), array('invalid' => 'Provide a valid email', 'required' => 'Enter your email')),
      'message'        => new sfValidatorString(array('required' => true), array('required' => 'Enter your surname')),
      ));
    $this->widgetSchema->setNameFormat('feedback[%s]');
    //Set the form format
    $this->widgetSchema->setFormFormatterName('div');
  }
}