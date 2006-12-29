<?php
		
require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'rel_division_actividad_docente' table to 'alba' DatabaseMap object.
 *
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an 
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive 
 * (i.e. if it's a text column type).
 *
 * @package model.map
 */	
class RelDivisionActividadDocenteMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'model.map.RelDivisionActividadDocenteMapBuilder';	

    /**
     * The database map.
     */
    private $dbMap;

	/**
     * Tells us if this DatabaseMapBuilder is built so that we
     * don't have to re-build it every time.
     *
     * @return boolean true if this DatabaseMapBuilder is built, false otherwise.
     */
    public function isBuilt()
    {
        return ($this->dbMap !== null);
    }

	/**
     * Gets the databasemap this map builder built.
     *
     * @return the databasemap
     */
    public function getDatabaseMap()
    {
        return $this->dbMap;
    }

    /**
     * The doBuild() method builds the DatabaseMap
     *
	 * @return void
     * @throws PropelException
     */
    public function doBuild()
    {
		$this->dbMap = Propel::getDatabaseMap('alba');
		
		$tMap = $this->dbMap->addTable('rel_division_actividad_docente');
		$tMap->setPhpName('RelDivisionActividadDocente');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, 11);

		$tMap->addForeignKey('FK_DIVISION_ID', 'FkDivisionId', 'int', CreoleTypes::INTEGER, 'division', 'ID', false, 11);

		$tMap->addForeignKey('FK_ACTIVIDAD_ID', 'FkActividadId', 'int', CreoleTypes::INTEGER, 'actividad', 'ID', true, 11);

		$tMap->addForeignKey('FK_DOCENTE_ID', 'FkDocenteId', 'int', CreoleTypes::INTEGER, 'docente', 'ID', false, 11);

		$tMap->addForeignKey('FK_TIPODOCENTE_ID', 'FkTipodocenteId', 'int', CreoleTypes::INTEGER, 'tipodocente', 'ID', false, 11);

		$tMap->addForeignKey('FK_CARGOBAJA_ID', 'FkCargobajaId', 'int', CreoleTypes::INTEGER, 'cargobaja', 'ID', false, 11);

		$tMap->addForeignKey('FK_REPETICION_ID', 'FkRepeticionId', 'int', CreoleTypes::INTEGER, 'repeticion', 'ID', false, 11);

		$tMap->addColumn('FECHA_INICIO', 'FechaInicio', 'int', CreoleTypes::TIMESTAMP, true);

		$tMap->addColumn('FECHA_FIN', 'FechaFin', 'int', CreoleTypes::TIMESTAMP, true);

		$tMap->addColumn('HORA_INICIO', 'HoraInicio', 'int', CreoleTypes::TIME, true);

		$tMap->addColumn('HORA_FIN', 'HoraFin', 'int', CreoleTypes::TIME, true);
				
    } // doBuild()

} // RelDivisionActividadDocenteMapBuilder
