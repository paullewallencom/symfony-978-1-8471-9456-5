<?php

class VacancyPeer extends BaseVacancyPeer
{
  public static function getVacancies($culture)
	{
		return self::doSelectWithI18n(new Criteria, $culture);
	}
}
