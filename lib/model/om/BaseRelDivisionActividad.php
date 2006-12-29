<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'model/RelDivisionActividadPeer.php';

/**
 * Base class that represents a row from the 'rel_division_actividad' table.
 *
 * 
 *
 * @package model.om
 */
abstract class BaseRelDivisionActividad extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var RelDivisionActividadPeer
	 */
	protected static $peer;


	/**
	 * The value for the id field.
	 * @var int
	 */
	protected $id;


	/**
	 * The value for the fk_division_id field.
	 * @var int
	 */
	protected $fk_division_id = 0;


	/**
	 * The value for the fk_actividad_id field.
	 * @var int
	 */
	protected $fk_actividad_id = 0;


	/**
	 * The value for the fk_repeticion_id field.
	 * @var int
	 */
	protected $fk_repeticion_id = 0;


	/**
	 * The value for the fecha_inicio field.
	 * @var int
	 */
	protected $fecha_inicio;


	/**
	 * The value for the fecha_fin field.
	 * @var int
	 */
	protected $fecha_fin;


	/**
	 * The value for the hora_inicio field.
	 * @var int
	 */
	protected $hora_inicio;


	/**
	 * The value for the hora_fin field.
	 * @var int
	 */
	protected $hora_fin;

	/**
	 * @var Division
	 */
	protected $aDivision;

	/**
	 * @var Actividad
	 */
	protected $aActividad;

	/**
	 * @var Repeticion
	 */
	protected $aRepeticion;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var boolean
	 */
	protected $alreadyInValidation = false;

	/**
	 * Get the [id] column value.
	 * 
	 * @return int
	 */
	public function getId()
	{

		return $this->id;
	}

	/**
	 * Get the [fk_division_id] column value.
	 * 
	 * @return int
	 */
	public function getFkDivisionId()
	{

		return $this->fk_division_id;
	}

	/**
	 * Get the [fk_actividad_id] column value.
	 * 
	 * @return int
	 */
	public function getFkActividadId()
	{

		return $this->fk_actividad_id;
	}

	/**
	 * Get the [fk_repeticion_id] column value.
	 * 
	 * @return int
	 */
	public function getFkRepeticionId()
	{

		return $this->fk_repeticion_id;
	}

	/**
	 * Get the [optionally formatted] [fecha_inicio] column value.
	 * 
	 * @param string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getFechaInicio($format = 'Y-m-d H:i:s')
	{

		if ($this->fecha_inicio === null || $this->fecha_inicio === '') {
			return null;
		} elseif (!is_int($this->fecha_inicio)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->fecha_inicio);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [fecha_inicio] as date/time value: " . var_export($this->fecha_inicio, true));
			}
		} else {
			$ts = $this->fecha_inicio;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}

	/**
	 * Get the [optionally formatted] [fecha_fin] column value.
	 * 
	 * @param string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getFechaFin($format = 'Y-m-d H:i:s')
	{

		if ($this->fecha_fin === null || $this->fecha_fin === '') {
			return null;
		} elseif (!is_int($this->fecha_fin)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->fecha_fin);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [fecha_fin] as date/time value: " . var_export($this->fecha_fin, true));
			}
		} else {
			$ts = $this->fecha_fin;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}

	/**
	 * Get the [optionally formatted] [hora_inicio] column value.
	 * 
	 * @param string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getHoraInicio($format = '%X')
	{

		if ($this->hora_inicio === null || $this->hora_inicio === '') {
			return null;
		} elseif (!is_int($this->hora_inicio)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->hora_inicio);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [hora_inicio] as date/time value: " . var_export($this->hora_inicio, true));
			}
		} else {
			$ts = $this->hora_inicio;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}

	/**
	 * Get the [optionally formatted] [hora_fin] column value.
	 * 
	 * @param string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getHoraFin($format = '%X')
	{

		if ($this->hora_fin === null || $this->hora_fin === '') {
			return null;
		} elseif (!is_int($this->hora_fin)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->hora_fin);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [hora_fin] as date/time value: " . var_export($this->hora_fin, true));
			}
		} else {
			$ts = $this->hora_fin;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setId($v)
	{

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = RelDivisionActividadPeer::ID;
		}

	} // setId()

	/**
	 * Set the value of [fk_division_id] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setFkDivisionId($v)
	{

		if ($this->fk_division_id !== $v || $v === 0) {
			$this->fk_division_id = $v;
			$this->modifiedColumns[] = RelDivisionActividadPeer::FK_DIVISION_ID;
		}

		if ($this->aDivision !== null && $this->aDivision->getId() !== $v) {
			$this->aDivision = null;
		}

	} // setFkDivisionId()

	/**
	 * Set the value of [fk_actividad_id] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setFkActividadId($v)
	{

		if ($this->fk_actividad_id !== $v || $v === 0) {
			$this->fk_actividad_id = $v;
			$this->modifiedColumns[] = RelDivisionActividadPeer::FK_ACTIVIDAD_ID;
		}

		if ($this->aActividad !== null && $this->aActividad->getId() !== $v) {
			$this->aActividad = null;
		}

	} // setFkActividadId()

	/**
	 * Set the value of [fk_repeticion_id] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setFkRepeticionId($v)
	{

		if ($this->fk_repeticion_id !== $v || $v === 0) {
			$this->fk_repeticion_id = $v;
			$this->modifiedColumns[] = RelDivisionActividadPeer::FK_REPETICION_ID;
		}

		if ($this->aRepeticion !== null && $this->aRepeticion->getId() !== $v) {
			$this->aRepeticion = null;
		}

	} // setFkRepeticionId()

	/**
	 * Set the value of [fecha_inicio] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setFechaInicio($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [fecha_inicio] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->fecha_inicio !== $ts) {
			$this->fecha_inicio = $ts;
			$this->modifiedColumns[] = RelDivisionActividadPeer::FECHA_INICIO;
		}

	} // setFechaInicio()

	/**
	 * Set the value of [fecha_fin] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setFechaFin($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [fecha_fin] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->fecha_fin !== $ts) {
			$this->fecha_fin = $ts;
			$this->modifiedColumns[] = RelDivisionActividadPeer::FECHA_FIN;
		}

	} // setFechaFin()

	/**
	 * Set the value of [hora_inicio] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setHoraInicio($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [hora_inicio] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->hora_inicio !== $ts) {
			$this->hora_inicio = $ts;
			$this->modifiedColumns[] = RelDivisionActividadPeer::HORA_INICIO;
		}

	} // setHoraInicio()

	/**
	 * Set the value of [hora_fin] column.
	 * 
	 * @param int $v new value
	 * @return void
	 */
	public function setHoraFin($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [hora_fin] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->hora_fin !== $ts) {
			$this->hora_fin = $ts;
			$this->modifiedColumns[] = RelDivisionActividadPeer::HORA_FIN;
		}

	} // setHoraFin()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (1-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param ResultSet $rs The ResultSet class with cursor advanced to desired record pos.
	 * @param int $startcol 1-based offset column which indicates which restultset column to start with.
	 * @return int next starting column
	 * @throws PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->fk_division_id = $rs->getInt($startcol + 1);

			$this->fk_actividad_id = $rs->getInt($startcol + 2);

			$this->fk_repeticion_id = $rs->getInt($startcol + 3);

			$this->fecha_inicio = $rs->getTimestamp($startcol + 4, null);

			$this->fecha_fin = $rs->getTimestamp($startcol + 5, null);

			$this->hora_inicio = $rs->getTime($startcol + 6, null);

			$this->hora_fin = $rs->getTime($startcol + 7, null);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 8; // 8 = RelDivisionActividadPeer::NUM_COLUMNS - RelDivisionActividadPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating RelDivisionActividad object", $e);
		}
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param Connection $con
	 * @return void
	 * @throws PropelException
	 * @see BaseObject::setDeleted()
	 * @see BaseObject::isDeleted()
	 */
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(RelDivisionActividadPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			RelDivisionActividadPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Stores the object in the database.  If the object is new,
	 * it inserts it; otherwise an update is performed.  This method
	 * wraps the doSave() worker method in a transaction.
	 *
	 * @param Connection $con
	 * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws PropelException
	 * @see doSave()
	 */
	public function save($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(RelDivisionActividadPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Stores the object in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param Connection $con
	 * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws PropelException
	 * @see save()
	 */
	protected function doSave($con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


			// We call the save method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aDivision !== null) {
				if ($this->aDivision->isModified()) {
					$affectedRows += $this->aDivision->save($con);
				}
				$this->setDivision($this->aDivision);
			}

			if ($this->aActividad !== null) {
				if ($this->aActividad->isModified()) {
					$affectedRows += $this->aActividad->save($con);
				}
				$this->setActividad($this->aActividad);
			}

			if ($this->aRepeticion !== null) {
				if ($this->aRepeticion->isModified()) {
					$affectedRows += $this->aRepeticion->save($con);
				}
				$this->setRepeticion($this->aRepeticion);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = RelDivisionActividadPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += RelDivisionActividadPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			$this->alreadyInSave = false;
		}
		return $affectedRows;
	} // doSave()

	/**
	 * Array of ValidationFailed objects.
	 * @var array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return array ValidationFailed[]
	 * @see validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param mixed $columns Column name or an array of column names.
	 * @return boolean Whether all columns pass validation.
	 * @see doValidate()
	 * @see getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param array $columns Array of column names to validate.
	 * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aDivision !== null) {
				if (!$this->aDivision->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aDivision->getValidationFailures());
				}
			}

			if ($this->aActividad !== null) {
				if (!$this->aActividad->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aActividad->getValidationFailures());
				}
			}

			if ($this->aRepeticion !== null) {
				if (!$this->aRepeticion->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aRepeticion->getValidationFailures());
				}
			}


			if (($retval = RelDivisionActividadPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param string $name name
	 * @param string $type The type of fieldname the $name is of:
	 *                     one of the class type constants TYPE_PHPNAME,
	 *                     TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = RelDivisionActividadPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param int $pos position in xml schema
	 * @return mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getFkDivisionId();
				break;
			case 2:
				return $this->getFkActividadId();
				break;
			case 3:
				return $this->getFkRepeticionId();
				break;
			case 4:
				return $this->getFechaInicio();
				break;
			case 5:
				return $this->getFechaFin();
				break;
			case 6:
				return $this->getHoraInicio();
				break;
			case 7:
				return $this->getHoraFin();
				break;
			default:
				return null;
				break;
		} // switch()
	}

	/**
	 * Exports the object as an array.
	 *
	 * You can specify the key type of the array by passing one of the class
	 * type constants.
	 *
	 * @param string $keyType One of the class type constants TYPE_PHPNAME,
	 *                        TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = RelDivisionActividadPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getFkDivisionId(),
			$keys[2] => $this->getFkActividadId(),
			$keys[3] => $this->getFkRepeticionId(),
			$keys[4] => $this->getFechaInicio(),
			$keys[5] => $this->getFechaFin(),
			$keys[6] => $this->getHoraInicio(),
			$keys[7] => $this->getHoraFin(),
		);
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param string $name peer name
	 * @param mixed $value field value
	 * @param string $type The type of fieldname the $name is of:
	 *                     one of the class type constants TYPE_PHPNAME,
	 *                     TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = RelDivisionActividadPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param int $pos position in xml schema
	 * @param mixed $value field value
	 * @return void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setFkDivisionId($value);
				break;
			case 2:
				$this->setFkActividadId($value);
				break;
			case 3:
				$this->setFkRepeticionId($value);
				break;
			case 4:
				$this->setFechaInicio($value);
				break;
			case 5:
				$this->setFechaFin($value);
				break;
			case 6:
				$this->setHoraInicio($value);
				break;
			case 7:
				$this->setHoraFin($value);
				break;
		} // switch()
	}

	/**
	 * Populates the object using an array.
	 *
	 * This is particularly useful when populating an object from one of the
	 * request arrays (e.g. $_POST).  This method goes through the column
	 * names, checking to see whether a matching key exists in populated
	 * array. If so the setByName() method is called for that column.
	 *
	 * You can specify the key type of the array by additionally passing one
	 * of the class type constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME,
	 * TYPE_NUM. The default key type is the column's phpname (e.g. 'authorId')
	 *
	 * @param array  $arr     An array to populate the object from.
	 * @param string $keyType The type of keys the array uses.
	 * @return void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = RelDivisionActividadPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setFkDivisionId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setFkActividadId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setFkRepeticionId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setFechaInicio($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setFechaFin($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setHoraInicio($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setHoraFin($arr[$keys[7]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(RelDivisionActividadPeer::DATABASE_NAME);

		if ($this->isColumnModified(RelDivisionActividadPeer::ID)) $criteria->add(RelDivisionActividadPeer::ID, $this->id);
		if ($this->isColumnModified(RelDivisionActividadPeer::FK_DIVISION_ID)) $criteria->add(RelDivisionActividadPeer::FK_DIVISION_ID, $this->fk_division_id);
		if ($this->isColumnModified(RelDivisionActividadPeer::FK_ACTIVIDAD_ID)) $criteria->add(RelDivisionActividadPeer::FK_ACTIVIDAD_ID, $this->fk_actividad_id);
		if ($this->isColumnModified(RelDivisionActividadPeer::FK_REPETICION_ID)) $criteria->add(RelDivisionActividadPeer::FK_REPETICION_ID, $this->fk_repeticion_id);
		if ($this->isColumnModified(RelDivisionActividadPeer::FECHA_INICIO)) $criteria->add(RelDivisionActividadPeer::FECHA_INICIO, $this->fecha_inicio);
		if ($this->isColumnModified(RelDivisionActividadPeer::FECHA_FIN)) $criteria->add(RelDivisionActividadPeer::FECHA_FIN, $this->fecha_fin);
		if ($this->isColumnModified(RelDivisionActividadPeer::HORA_INICIO)) $criteria->add(RelDivisionActividadPeer::HORA_INICIO, $this->hora_inicio);
		if ($this->isColumnModified(RelDivisionActividadPeer::HORA_FIN)) $criteria->add(RelDivisionActividadPeer::HORA_FIN, $this->hora_fin);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(RelDivisionActividadPeer::DATABASE_NAME);

		$criteria->add(RelDivisionActividadPeer::ID, $this->id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return int
	 */
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	/**
	 * Generic method to set the primary key (id column).
	 *
	 * @param int $key Primary key.
	 * @return void
	 */
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param object $copyObj An object of RelDivisionActividad (or compatible) type.
	 * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setFkDivisionId($this->fk_division_id);

		$copyObj->setFkActividadId($this->fk_actividad_id);

		$copyObj->setFkRepeticionId($this->fk_repeticion_id);

		$copyObj->setFechaInicio($this->fecha_inicio);

		$copyObj->setFechaFin($this->fecha_fin);

		$copyObj->setHoraInicio($this->hora_inicio);

		$copyObj->setHoraFin($this->hora_fin);


		$copyObj->setNew(true);

		$copyObj->setId(NULL); // this is a pkey column, so set to default value

	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return RelDivisionActividad Clone of current object.
	 * @throws PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return RelDivisionActividadPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new RelDivisionActividadPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Division object.
	 *
	 * @param Division $v
	 * @return void
	 * @throws PropelException
	 */
	public function setDivision($v)
	{


		if ($v === null) {
			$this->setFkDivisionId('0');
		} else {
			$this->setFkDivisionId($v->getId());
		}


		$this->aDivision = $v;
	}


	/**
	 * Get the associated Division object
	 *
	 * @param Connection Optional Connection object.
	 * @return Division The associated Division object.
	 * @throws PropelException
	 */
	public function getDivision($con = null)
	{
		// include the related Peer class
		include_once 'model/om/BaseDivisionPeer.php';

		if ($this->aDivision === null && ($this->fk_division_id !== null)) {

			$this->aDivision = DivisionPeer::retrieveByPK($this->fk_division_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = DivisionPeer::retrieveByPK($this->fk_division_id, $con);
			   $obj->addDivisions($this);
			 */
		}
		return $this->aDivision;
	}

	/**
	 * Declares an association between this object and a Actividad object.
	 *
	 * @param Actividad $v
	 * @return void
	 * @throws PropelException
	 */
	public function setActividad($v)
	{


		if ($v === null) {
			$this->setFkActividadId('0');
		} else {
			$this->setFkActividadId($v->getId());
		}


		$this->aActividad = $v;
	}


	/**
	 * Get the associated Actividad object
	 *
	 * @param Connection Optional Connection object.
	 * @return Actividad The associated Actividad object.
	 * @throws PropelException
	 */
	public function getActividad($con = null)
	{
		// include the related Peer class
		include_once 'model/om/BaseActividadPeer.php';

		if ($this->aActividad === null && ($this->fk_actividad_id !== null)) {

			$this->aActividad = ActividadPeer::retrieveByPK($this->fk_actividad_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = ActividadPeer::retrieveByPK($this->fk_actividad_id, $con);
			   $obj->addActividads($this);
			 */
		}
		return $this->aActividad;
	}

	/**
	 * Declares an association between this object and a Repeticion object.
	 *
	 * @param Repeticion $v
	 * @return void
	 * @throws PropelException
	 */
	public function setRepeticion($v)
	{


		if ($v === null) {
			$this->setFkRepeticionId('0');
		} else {
			$this->setFkRepeticionId($v->getId());
		}


		$this->aRepeticion = $v;
	}


	/**
	 * Get the associated Repeticion object
	 *
	 * @param Connection Optional Connection object.
	 * @return Repeticion The associated Repeticion object.
	 * @throws PropelException
	 */
	public function getRepeticion($con = null)
	{
		// include the related Peer class
		include_once 'model/om/BaseRepeticionPeer.php';

		if ($this->aRepeticion === null && ($this->fk_repeticion_id !== null)) {

			$this->aRepeticion = RepeticionPeer::retrieveByPK($this->fk_repeticion_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = RepeticionPeer::retrieveByPK($this->fk_repeticion_id, $con);
			   $obj->addRepeticions($this);
			 */
		}
		return $this->aRepeticion;
	}

} // BaseRelDivisionActividad
