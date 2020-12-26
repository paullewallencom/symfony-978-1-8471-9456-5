<?php

class StoreLocationPeer extends BaseStoreLocationPeer
{

  /*
	 * Get all locations
	 *
	 * @return Array Array of result objects.
	 */
	public static function getAllLocations()
	{
		$locations = DbFinder::from('StoreLocation')->orderBy('Country')->orderBy('City')->find();
		return	$locations;
	}
}
