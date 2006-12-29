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
 * docenteHorario Acciones
 *
 * @package    alba
 * @author     José Luis Di Biase <josx@interorganic.com.ar>
 * @author     Héctor Sanchez <hsanchez@pressenter.com.ar>
 * @author     Fernando Toledo <ftoledo@pressenter.com.ar>
 * @version    SVN: $Id$
 * @filesource
 * @license GPL
 */

class DocenteHorarioActions extends sfActions
{
  public function preExecute ()
  {
    $this->getResponse()->addStylesheet('/sf/css/sf_admin/main', 'first');
  }

  public function executeIndex ()
  {
    return $this->forward('docenteHorario', 'list');
  }

  public function executeList ()
  {
    if($this->getRequestParameter('idDocente')) {

        $aRepeticion  = RepeticionPeer::doSelect(new Criteria());
        $this->aRepeticion = array();
        foreach($aRepeticion  as $repeticion) {
            $this->aRepeticion[$repeticion->getId()] = $repeticion->getDescripcion();
        }
    
        $c = new Criteria();
        $c->add(DocenteHorarioPeer::FK_DOCENTE_ID, $this->getRequestParameter('idDocente'));
        $aHorario  = DocenteHorarioPeer::doSelect($c);
        $this->aHorario = $aHorario;

        $c = new Criteria();
        $aDocente  = DocentePeer::doSelect($c);
        $this->optionsDocente = array();
        foreach($aDocente as $docente) {
            $this->optionsDocente[$docente->getId()] = $docente->getApellido().' '.$docente->getNombre();
        }
    } 

  }

    public function executeDeleteHorario ()  {
        $this->docenteHorario = DocenteHorarioPeer::retrieveByPk($this->getRequestParameter('id'));
        $this->forward404Unless($this->docenteHorario);
        $idDocente = $this->docenteHorario->getFkDocenteId();
        $this->docenteHorario->delete();
        $link = 'docenteHorario/list?idDocente='.$idDocente;
        return $this->redirect($link);
    }



    public function executeGrabarDocenteHorario() {
        
        $aHorario = $this->getRequestParameter('horario');
        if(is_array($aHorario)) {        
            foreach($aHorario as $horario) {
                
                $horaInicio = $this->_add_zeros($horario['hora_inicio']['hour'],2).":".$this->_add_zeros($horario['hora_inicio']['minute'],2)." ".$horario['hora_inicio']['ampm'];
                $horaFin = $this->_add_zeros($horario['hora_fin']['hour'],2).":".$this->_add_zeros($horario['hora_fin']['minute'],2)." ".$horario['hora_fin']['ampm'];
                
                if($horaInicio != $horaFin) {
                    if(isset($horario['id']) AND is_numeric($horario['id'])) {
                        $this->horario = DocenteHorarioPeer::retrieveByPk($horario['id']);
                    } else {
                        $this->horario = new DocenteHorario();
                    }
                    $this->horario->setHoraInicio($horaInicio);
                    $this->horario->setHoraFin($horaFin);
                    $this->horario->setDia($horario['dia']);
                    $this->horario->setFkDocenteId($this->getRequestParameter('idDocente'));
                    $this->horario->setFkRepeticionId($horario['fk_repeticion_id']);
                    $this->horario->save();
                }
            }    
        }
        
        return $this->forward('docenteHorario','list','idDocente/'.$this->getRequestParameter('idDocente'));        
    }
    

    function _add_zeros($string, $strlen) {
        if ($strlen > strlen($string))  {
            for ($x = strlen($string); $x < $strlen; $x++) {
                $string = '0' . $string;
            }
        }
        return $string;
    }

}

?>