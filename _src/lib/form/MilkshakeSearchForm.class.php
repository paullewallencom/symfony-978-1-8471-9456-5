<?php

class MilkshakeSearchForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'url_slug' => new sfWidgetFormChoice(array(
        'choices' => array(),
        'renderer_class' => 'sfWidgetFormJQueryAutocompleter',
        'renderer_options' => array(
          'url' => '/menu/search',
        ),
      ))
    ));

    $this->widgetSchema->setFormFormatterName('div');
  }
}

?>
