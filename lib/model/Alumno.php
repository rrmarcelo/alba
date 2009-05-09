<?php

require_once 'lib/model/om/BaseAlumno.php';


/**
 * Skeleton subclass for representing a row from the 'alumno' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class Alumno extends BaseAlumno {
    public function __toString() {
        return $this->getApellido() . " ".$this->getApellidoMaterno()." ". $this->getNombre() ;
    } 
        
    public function getApellidos(){
        return $this->getApellido().' '.$this->getApellidoMaterno();
    }

    public function toArrayInforme($keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = AlumnoPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getNombre(),
            $keys[2] => $this->getApellidoMaterno(),
            $keys[3] => $this->getApellido(),
            $keys[4] => $this->getFechaNacimiento(),
            $keys[5] => $this->getDireccion(),
            $keys[6] => $this->getCiudad(),
            $keys[7] => $this->getCodigoPostal(),
            'Provincia' => ($this->getProvincia())?$this->getProvincia()->getNombreCorto():'' ,
            $keys[9] => $this->getTelefono(),
            $keys[10] => $this->getLugarNacimiento(),
            'TipoDocumento' => ($this->getTipodocumento())?$this->getTipodocumento()->getNombre():'',
            $keys[12] => $this->getNroDocumento(),
            $keys[13] => $this->getSexo(),
            $keys[14] => $this->getEmail(),
            $keys[15] => $this->getDistanciaEscuela(),
            $keys[16] => $this->getHermanosEscuela(),
            $keys[17] => $this->getHijoMaestroEscuela(),
            'Establecimiento' => ($this->getEstablecimiento())?$this->getEstablecimiento()->getNombre():'',
            'Cuenta' => ($this->getCuenta())?$this->getCuenta()->getNombre():'',
            $keys[20] => $this->getCertificadoMedico(),
            $keys[21] => $this->getActivo(),
            $keys[22] => $this->getFkConceptobajaId(),
            'Pais' => ($this->getPais())?$this->getPais()->getNombreLargo():'',
        );
        return $result;
    }


    public function getNotasConcepto() {
        $conceptoAlumno = array();
        $criteria = new Criteria();
        $criteria->add(BoletinConceptualPeer::FK_ALUMNO_ID, $this->getId());
        $aBoletinConceptual = BoletinConceptualPeer::doSelect($criteria);
        foreach($aBoletinConceptual as $boletinConceptual ) {
            if($boletinConceptual->getFkEscalanotaId()) {
                $conceptoAlumno[$boletinConceptual->getFkPeriodoId()][$boletinConceptual->getFkConceptoId()] = $boletinConceptual->getEscalanota()->getNombre();
            }
            if($boletinConceptual->getObservacion() != null) {
               $conceptoAlumno[$boletinConceptual->getFkPeriodoId()][$boletinConceptual->getFkConceptoId()] = $boletinConceptual->getObservacion();
            }
        }
        return $conceptoAlumno;
    }

    public function getNotas() {
        $notaAlumno = array();
        // notas del alumno
        $criteria = new Criteria();
        $criteria->add(BoletinActividadesPeer::FK_ALUMNO_ID, $this->getId());
        $criteria->addJoin(BoletinActividadesPeer::FK_ESCALANOTA_ID, EscalanotaPeer::ID);
        $criteria->addAsColumn("boletinActividades_periodo_id", BoletinActividadesPeer::FK_PERIODO_ID);
        $criteria->addAsColumn("boletinActividades_actividad_id", BoletinActividadesPeer::FK_ACTIVIDAD_ID);
        $criteria->addAsColumn("escalanota_nombre", EscalanotaPeer::NOMBRE);
        $aBoletinActividades = BasePeer::doSelect($criteria);
        foreach($aBoletinActividades as $boletinActividades) {
            $notaAlumno[$boletinActividades[0]][$boletinActividades[1]] = $boletinActividades[2];
        }

        return $notaAlumno;
    }


    public function getAsistenciasPorFechas($fecha_inicio, $fecha_fin) {
        $aAsistencia = array();

        // En teoria esta dos consultas pueden reemplazarse con una solo usando LEFT JOIN y CASE

        $c = new Criteria();
        $c->addGroupByColumn(TipoasistenciaPeer::GRUPO);
        $c->addSelectColumn(TipoasistenciaPeer::GRUPO);
        $rsColumna = TipoasistenciaPeer::doSelectStmt($c);

        $c = new Criteria();
        $c->clearSelectColumns();
        $c->addGroupByColumn(TipoasistenciaPeer::GRUPO);
        $c->addSelectColumn(TipoasistenciaPeer::GRUPO);
        $c->addSelectColumn('SUM('.TipoasistenciaPeer::VALOR.') AS valor');
        $c->addJoin(TipoasistenciaPeer::ID, AsistenciaPeer::FK_TIPOASISTENCIA_ID);
        $c->add(AsistenciaPeer::FK_ALUMNO_ID, $this->getId(), Criteria::EQUAL);
        $c2 = new Criteria();
        $criterion = $c2->getNewCriterion(AsistenciaPeer::FECHA, $fecha_inicio, Criteria::GREATER_EQUAL);
        $criterion->addAnd($c2->getNewCriterion(AsistenciaPeer::FECHA, $fecha_fin, Criteria::LESS_EQUAL));
        $c->add($criterion);
        $rsValor = TipoasistenciaPeer::doSelectStmt($c);

        if($rsColumna) {
            while( $res = $rsColumna->fetchColumn(1)) {
                $aAsistencia[$res] = 0;  // indice: nombre del Grupo, contenido: 
            }
        }

        if($rsValor) {
            while($rsValor->fetch()) {
                // indice: nombre del Grupo, contenido: sumatoria de valor
                $aAsistencia[$rsValor->fetchColumn(1)] = $rsValor->fetchColumn(2); 
            }
        }

        return $aAsistencia;


    }

} // Alumno
