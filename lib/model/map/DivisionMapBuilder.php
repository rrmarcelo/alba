<?php



class DivisionMapBuilder {

	
	const CLASS_NAME = 'lib.model.map.DivisionMapBuilder';

	
	private $dbMap;

	
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap('alba');

		$tMap = $this->dbMap->addTable('division');
		$tMap->setPhpName('Division');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addForeignKey('FK_ANIO_ID', 'FkAnioId', 'int', CreoleTypes::INTEGER, 'anio', 'ID', true, null);

		$tMap->addColumn('DESCRIPCION', 'Descripcion', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addForeignKey('FK_TURNO_ID', 'FkTurnoId', 'int', CreoleTypes::INTEGER, 'turno', 'ID', true, null);

		$tMap->addForeignKey('FK_ORIENTACION_ID', 'FkOrientacionId', 'int', CreoleTypes::INTEGER, 'orientacion', 'ID', false, null);

		$tMap->addColumn('ORDEN', 'Orden', 'int', CreoleTypes::INTEGER, false, null);

	} 
} 