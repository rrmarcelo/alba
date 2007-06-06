<?php
/**
 *    This file is part of Alba.
 *
 *    Alba is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 *
 *    Alba is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with Alba; if not, write to the Free Software
 *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


/**
 * asistencia actions.
 *
 * @package    alba
 * @author     José Luis Di Biase <josx@interorganic.com.ar>
 * @author     Héctor Sanchez <hsanchez@pressenter.com.ar>
 * @author     Fernando Toledo <ftoledo@pressenter.com.ar>
 * @version    SVN: $Id$
 * @filesource
 * @license GPL
 */

class asistenciaActions extends sfActions
{

    public function preExecute() {
        $this->vista = $this->getRequestParameter('vista');
    }

    /**
    * Executes index action
    *
    */
    public function executeIndex() {
        
        Misc::use_helper('Misc');
        
        //Iniciando Variables
        $alumno_id = -1;
        $cuenta_id = -1;
        $vista_id = 1;         // default para vista DIARIO
        $division_id =0;
        $datos = array();
        $idxAlumno= array(); 
        $aFeriado = array();
        $aIntervalo = array();               
        $optionsDivision = array();
        $aTemp = array();
        $aFechaTemp = array();
        $aTipoasistencias = array();
        $aPorcentajeAsistencia = array();
        $flag_error = 0;
        $nombre_completo_archivo = "";
        $bool_gd = array_search("gd", get_loaded_extensions());

        if ($this->getUser()->getAttribute('fk_ciclolectivo_id') == 0)
            return $this->redirect('ciclolectivo/sinciclolectivo?m=' . $this->getRequestParameter('module'));
        
        // tomando los datos del formulario y completando variable
        $ciclolectivo_id = $this->getUser()->getAttribute('fk_ciclolectivo_id');       
        $ciclolectivo = CiclolectivoPeer::retrieveByPK($ciclolectivo_id);

        $ciclolectivo_fecha_inicio = strtotime($ciclolectivo->getFechaInicio());
        $ciclolectivo_fecha_fin = strtotime($ciclolectivo->getFechaFin());

        // asigno la fecha de inicio del ciclo lectivo por defecto
        $aFechaActual = getdate($ciclolectivo_fecha_inicio);
        $d = $aFechaActual['mday'];
        $m = $aFechaActual['mon'];
        $y = $aFechaActual['year'];

        // tomo el año de la fecha de inicio y de fin del ciclo lectivo
        $anio_desde = date("Y",$ciclolectivo_fecha_inicio);
        $anio_hasta = date("Y",$ciclolectivo_fecha_fin);
        
        if ($this->getRequestParameter('alumno_id')) {
            $alumno_id = $this->getRequestParameter('alumno_id');   
            $a = AlumnoPeer::retrieveByPK($alumno_id);
            $cuenta_id = $a->getFkCuentaId();
            
            //$a->getRelAlumnoDivisions()
        }


        if ($this->getRequestParameter('vistas')) {
            $vista_id  = $this->getRequestParameter('vistas');              
        }

        if ($this->getRequestParameter('dia')) {
            $d = $this->getRequestParameter('dia');
        }

        if ($this->getRequestParameter('mes')) {
            $m = $this->getRequestParameter('mes');
        }

        if ($this->getRequestParameter('ano')) {
            $y = $this->getRequestParameter('ano');
        }

        $establecimiento_id = $this->getUser()->getAttribute('fk_establecimiento_id');

        $criteria = new Criteria();
        $criteria->add(AnioPeer::FK_ESTABLECIMIENTO_ID, $establecimiento_id);
        
        
        if ($this->getRequestParameter('alumno_id')){
            $criteria->add(RelAlumnoDivisionPeer::FK_ALUMNO_ID, $alumno_id);
            $criteria->addJoin(RelAlumnoDivisionPeer::FK_DIVISION_ID, DivisionPeer::ID );
        }

        $criteria->addAscendingOrderByColumn(AnioPeer::DESCRIPCION);
        $criteria->addAscendingOrderByColumn(DivisionPeer::ORDEN);     
        $criteria->addAscendingOrderByColumn(DivisionPeer::DESCRIPCION);
        $divisiones = DivisionPeer::doSelectJoinAnio($criteria);        // divisiones a mostrar

        foreach ($divisiones as $division)
             $optionsDivision[$division->getId()] = $division->getAnio()->getDescripcion()." ".$division->getDescripcion();        

        if ($this->getRequestParameter('division_id')) {
            $division_id = $this->getRequestParameter('division_id');    
        } else {
            if (count($optionsDivision) > 0){
                $aTemp = array_keys($optionsDivision);        
                $division_id = $aTemp[0];
            } else {
                // Ver si se puede hacer desde el validate
                $this->getRequest()->setError('Division', 'No hay Ninguna División cargada');
                $flag_error = 1;
            }
        }

        if(!checkdate($m,$d,$y)) {
            // Ver si se puede hacer desde el validate
            $this->getRequest()->setError('Fecha', 'La fecha ingresada es erronea');
            $flag_error = 1;
        } 

        if($flag_error == 0) {
            // devuelve un intervalo de dias según vista y fecha seleccionada
            $aIntervalo = diasxintervalo($d, $m, $y, $vista_id);  

            // chequeo si el intervalo de fechas generados esta o no dentro de las fechas validas del ciclo lectivo
            foreach($aIntervalo as $fecha) {
                $fecha_intervalo = strtotime($fecha);
                if($fecha_intervalo >= $ciclolectivo_fecha_inicio AND $fecha_intervalo <= $ciclolectivo_fecha_fin) {
                    $aFechaTemp[] = $fecha;
                }
            }
            $aIntervalo = $aFechaTemp;
        }

        // obteniendo los feriados actuales
        $criteria = new Criteria();
        $feriados = FeriadoPeer::doSelect($criteria);        
        foreach($feriados as $feriado) {
            $aFeriado[$feriado->getFecha()] = $feriado->getNombre(); 
        }

        // esto deberia estar hecho muy diferente guardar en un /tmp con archivo aleatorio
        if(file_exists(sfConfig::get('sf_upload_dir_name').'/grafico_asistencias.png'))
            unlink(sfConfig::get('sf_upload_dir_name').'/grafico_asistencias.png');

        if(count($aIntervalo) > 0 ) {
            //Obtener los alumnos de la division y asistencias en el rango de fecha
            $con = sfContext::getInstance()->getDatabaseConnection($connection='propel'); 
            $s = "SELECT alumno.id, alumno.nombre, alumno.apellido,";
            $s .= "tipoasistencia.descripcion, tipoasistencia.nombre asistencia, asistencia.fecha,";
            $s .= "fk_tipoasistencia_id ";
            $s .= "FROM (alumno, rel_alumno_division) ";
            $s .= "LEFT JOIN asistencia ON ( rel_alumno_division.FK_ALUMNO_ID = asistencia.FK_ALUMNO_ID ";
            $s .= "AND asistencia.FECHA ";
            $s .= "IN (";

            for($i=0, $max = count($aIntervalo); $i < $max ;$i++) { 
                $s .= "'".$aIntervalo[$i]." 00:00:00'";
                if  ($i < count($aIntervalo)-1 ) {
                    $s .= ",";
                }
            }

            $s .= ") ) ";
            $s .= "LEFT JOIN tipoasistencia ON ( asistencia.FK_TIPOASISTENCIA_ID = tipoasistencia.ID ) ";
            $s .= "WHERE ";

            if ($alumno_id >= 0) {
                $s .= "alumno.ID =".$alumno_id;
            } else {
                $s .= "rel_alumno_division.FK_DIVISION_ID =". $division_id;
            }

            $s .= " AND rel_alumno_division.FK_ALUMNO_ID = alumno.ID";    
            $s .= " ORDER BY alumno.nombre,asistencia.FECHA";
        
            $stmt = $con->createStatement();
            $alumnos = $stmt->executeQuery($s, ResultSet::FETCHMODE_ASSOC);
                
            $totales = array();  
            $tot = 0;
            foreach ($alumnos as $alumno){
                $idxAlumno[$alumno['id']] = $alumno['apellido']." ". $alumno['nombre'];
                if  ($alumno['fecha']) { 
                    $datos[$alumno['id']][$alumno['fecha']] = $alumno['asistencia'];
                    @$totales[$alumno['asistencia']]++;
                }
            }

            $aTipoasistencias = $this->getTiposasistencias();
            $aPorcentajeAsistencia = array();
            $flag = 0;
            $tot = 0;

            // cantidad de fechas sin fines de semana
            $cantFechas = 0;
            foreach($aIntervalo as $fecha) {
                if( !(date("w",strtotime($fecha)) == 6) AND !(date("w",strtotime($fecha)) == 0) ) {
                    $cantFechas++;
                }
            }

            $dias = $cantFechas*count($idxAlumno);
            foreach($aTipoasistencias as $idx => $Tipoasistencia) {
                $aPorcentajeAsistencia[$idx] = isset($totales[$idx])?$totales[$idx]:0;
                @$tot += $totales[$idx];
                if($aPorcentajeAsistencia[$idx] != 0) $flag = 1;
            }

            if($flag == 1) {

                $aTitulo = array_keys($aTipoasistencias);
                $aTitulo[] = "No Cargado";

                if($bool_gd) { // Si no tiene cargado la GD no muestra el grafico
                    include "graph.php";
                    putenv('GDFONTPATH=' . realpath(sfConfig::get('sf_lib_dir')."/font/"));
                    $graph = new graph();
                    $graph->setProp("font","FreeSerifBold.ttf");
                    $graph->setProp("keyfont","FreeSerifBold.ttf");
                    $graph->setProp("showkey",true);
                    $graph->setProp("type","pie");
                    $graph->setProp("showgrid",false);
                    $graph->setProp("key", $aTitulo);
                    $graph->setProp("keywidspc",-50);
                    $graph->setProp("keyinfo",2);
                    foreach($aPorcentajeAsistencia as $porcentaje)  {
                        $graph->addPoint($porcentaje);
                    }
                    $graph->addPoint($dias-$tot);
    
                    $graph->graphX();
                    $nombre_archivo = uniqid();
                    $nombre_completo_archivo = $nombre_archivo.'.png';
                    @$graph->showGraph(sfConfig::get('app_alba_tmpdir').DIRECTORY_SEPARATOR.$nombre_completo_archivo);
                } else {

                }
            }
        } else {
            // si pasa por aqui es que no hay fechas creadas en el intervalo
        }

        //Asignacion de variables para el template

        $this->bool_tmp = $this->tienePermisoEscritura(sfConfig::get('app_alba_tmpdir'));
        $this->bool_gd = $bool_gd;
        $this->nombre_completo_archivo = $nombre_completo_archivo;
        $this->d = $d;
        $this->m = $m;
        $this->y = $y;
        $this->aTipoasistencias = $aTipoasistencias;
        $this->aAlumnos = $idxAlumno;
        $this->aDatos = $datos;
        $this->optionsDivision = $optionsDivision;    
        $this->aVistas = repeticiones();
        $this->aMeses = Meses();
        $this->aIntervalo = $aIntervalo;
        $this->aPorcentajeAsistencia = $aPorcentajeAsistencia;
        $this->aFeriado = $aFeriado;
        $this->alumno_id = $alumno_id;
        $this->cuenta_id = $cuenta_id;
        $this->vista_id = $vista_id;
        $this->division_id = $division_id;
        $this->anio_desde = $anio_desde;
        $this->anio_hasta = $anio_hasta;

        //Verifico si muestro versión para imprimir   
        if ($this->getRequestParameter('vista')) {
            $this->vista = $this->getRequestParameter('vista');
        } else {
            $this->vista = "";
        }
  }
  
  /**
  * Accion para mostrar los datos cambiados en el form
  */
    function executeMostrar() {
        $vista_id  = $this->getRequestParameter('vistas');
        $vista  = $this->getRequestParameter('vista');
        $d = $this->getRequestParameter('dia');
        $m = $this->getRequestParameter('mes');
        $y = $this->getRequestParameter('ano');
        return $this->forward('asistencia','index',"vista_id=$vista_id&dia=$d&mes=$m&ano=$y");   
    }
  
    public function handleErrorMostrar(){
        $this->forward('asistencia', 'index');
    }  
  
  /**
  * Graba las asistencias
  */
    public function executeGrabar() {

        // inicializando variables
        $aDatosTipoasistencia = array();

        // tomando los datos del formulario
        $division_id = $this->getRequestParameter('division_id');
        $vista_id = $this->getRequestParameter('vista_id');
        $d = $this->getRequestParameter('dia');
        $m = $this->getRequestParameter('mes');
        $y = $this->getRequestParameter('ano');
        $destino = "asistencia?division_id=$division_id&dia=$d&mes=$m&ano=$y&vistas=$vista_id";
        if ($this->getRequestParameter('alumno_id')) {
            $alumno_id = $this->getRequestParameter('alumno_id');   
            $destino .= "&alumno_id=$alumno_id";
        }    
        $aAsistencia = $this->getRequestParameter('asistencia');
        
        $cantAsistencia = count($aAsistencia);
        if($cantAsistencia > 0) {
            // tomo los tipos de asistencias
            $aDatosTablaTipoAsistencias = $this->getTiposasistencias();

            //grabo al disco
            $con = Propel::getConnection();
            try {
                $con->begin();
                $criteria = new Criteria();

                foreach($aAsistencia as $alumno_id => $aPeriodo ) {
                    foreach($aPeriodo as $fecha => $Tipoasistencia) {
                        $cton1 = $criteria->getNewCriterion(AsistenciaPeer::FK_ALUMNO_ID, $alumno_id);
                        $cton2 = $criteria->getNewCriterion(AsistenciaPeer::FECHA, $fecha);
                        $cton1->addAnd($cton2);
                        $criteria->addOr($cton1);
                    }
                }
                AsistenciaPeer::doDelete($criteria);

                foreach($aAsistencia as $alumno_id => $aPeriodo ) {
                    foreach($aPeriodo as $fecha => $Tipoasistencia) {
                        $Asistencia = new Asistencia();
                        $Tipoasistencia = strtoupper($Tipoasistencia);
                        
                        if(array_key_exists($Tipoasistencia, $aDatosTablaTipoAsistencias)){
                            $Asistencia->setFkAlumnoId($alumno_id);
                            list($y, $m, $d) = split("[/. -]",$fecha);        
                            $fecha = "$y-$m-$d";
                            $Asistencia->setFecha($fecha);
                            $Asistencia->setFkTipoasistenciaId($aDatosTablaTipoAsistencias[$Tipoasistencia][0]);
                            $Asistencia->save();
                         }                          
                    }
                }
                $con->commit();
             }
             catch (Exception $e){
                 $con->rollback();
                 throw $e;
            }
        }
        return $this->redirect($destino);    
    }
        
     /**
     * Obtiene los tipos de asistencias
     */   
     protected function getTiposasistencias() {
        $aDatosTablaTipoAsistencias = array();
        $criteria = new Criteria();
        $aTipoasistencias = TipoasistenciaPeer::doSelect($criteria);
        foreach($aTipoasistencias as $tipoasistencia) {
            $aDatosTablaTipoAsistencias[$tipoasistencia->getNombre()] = array($tipoasistencia->getId(),$tipoasistencia->getDescripcion());
        }
        return $aDatosTablaTipoAsistencias;
     }


    function tienePermisoEscritura($dir) {
        $octalPermiso = substr(sprintf('%o', @fileperms($dir)), -4);
        return ($octalPermiso == "0777" OR $octalPermiso == "1777");
    }

}
?>
