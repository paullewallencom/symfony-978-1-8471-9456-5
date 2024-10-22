<?php


/**
 * This class adds structure of 'store_locations' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * Tue Aug 11 18:37:08 2009
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class StoreLocationMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.StoreLocationMapBuilder';

	/**
	 * The database map.
	 */
	private $dbMap;

	/**
	 * Tells us if this DatabaseMapBuilder is built so that we
	 * don't have to re-build it every time.
	 *
	 * @return     boolean true if this DatabaseMapBuilder is built, false otherwise.
	 */
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	/**
	 * Gets the databasemap this map builder built.
	 *
	 * @return     the databasemap
	 */
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	/**
	 * The doBuild() method builds the DatabaseMap
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap(StoreLocationPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(StoreLocationPeer::TABLE_NAME);
		$tMap->setPhpName('StoreLocation');
		$tMap->setClassname('StoreLocation');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addColumn('ADDRESS1', 'Address1', 'VARCHAR', true, 100);

		$tMap->addColumn('ADDRESS2', 'Address2', 'VARCHAR', true, 100);

		$tMap->addColumn('ADDRESS3', 'Address3', 'VARCHAR', true, 50);

		$tMap->addColumn('POSTCODE', 'Postcode', 'VARCHAR', true, 8);

		$tMap->addColumn('CITY', 'City', 'VARCHAR', true, 50);

		$tMap->addColumn('COUNTRY', 'Country', 'VARCHAR', true, 50);

		$tMap->addColumn('PHONE', 'Phone', 'VARCHAR', true, 20);

		$tMap->addColumn('FAX', 'Fax', 'VARCHAR', true, 20);

	} // doBuild()

} // StoreLocationMapBuilder
