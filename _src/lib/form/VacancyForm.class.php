<?php

/**
 * Vacancy form.
 *
 * @package    milkshake
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class VacancyForm extends BaseVacancyForm
{
  public function configure()
  {
    // setting closing_date as calendar widget
    $this->widgetSchema['closing_date'] = new sfWidgetFormJQueryDate();

    // adding TinyMCE widget
    $this->widgetSchema['position_description'] =
      new sfWidgetFormTextareaTinyMCE(array(
        'width' => '500', 'height' => '150'
    ));
  }
}
