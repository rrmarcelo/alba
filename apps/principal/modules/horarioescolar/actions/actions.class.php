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
 * horarioescolar Acciones
 *
 * @package    alba
 * @author     José Luis Di Biase <josx@interorganic.com.ar>
 * @author     Héctor Sanchez <hsanchez@pressenter.com.ar>
 * @author     Fernando Toledo <ftoledo@pressenter.com.ar>
 * @version    SVN: $Id$
 * @filesource
 * @license GPL
 */

class horarioescolarActions extends autohorarioescolarActions
{
    

    public function executeEdit()  {
        $evento_generico = new miEvento();
        $this->horarioescolar = $this->getHorarioescolarOrCreate();
        $this->evento = $evento_generico->getEventoOrCreate($this->horarioescolar->getFkEventoId());
        if ($this->getRequest()->getMethod() == sfRequest::POST) {
            $this->evento = $evento_generico->updateEventoFromRequest($this->evento, $this->getRequestParameter('evento'), $this->getUser()->getCulture());
            $this->evento->save();
            $this->forward404Unless($this->evento);
            $this->updateHorarioescolarFromRequest($this->evento->getId());
            $this->saveHorarioescolar($this->horarioescolar);
            $this->setFlash('notice', 'Your modifications have been saved');
            if ($this->getRequestParameter('save_and_add')) {
                return $this->redirect('horarioescolar/create');
            } else if ($this->getRequestParameter('save_and_list')) {
                return $this->redirect('horarioescolar/list');
            } else {
                return $this->redirect('horarioescolar/edit?id='.$this->horarioescolar->getId());
            }
        } else {
            $this->labels = $this->getLabels();
        }
    }



    protected function updateHorarioescolarFromRequest($fk_evento_id = '') {
        $horarioescolar = $this->getRequestParameter('horarioescolar');

    if ($fk_evento_id) {
            $this->horarioescolar->setFkEventoId($fk_evento_id);
    } else {
            $this->horarioescolar->setFkEventoId(null);
    }

    if (isset($horarioescolar['nombre']))
    {
      $this->horarioescolar->setNombre($horarioescolar['nombre']);
    }
    if (isset($horarioescolar['descripcion']))
    {
      $this->horarioescolar->setDescripcion($horarioescolar['descripcion']);
    }
    if (isset($horarioescolar['fk_horarioescolartipo_id']))
    {
      $this->horarioescolar->setFkHorarioescolartipoId($horarioescolar['fk_horarioescolartipo_id']);
    }
    if (isset($horarioescolar['fk_establecimiento_id']))
    {
      $this->horarioescolar->setFkEstablecimientoId($horarioescolar['fk_establecimiento_id']);
    }
    if (isset($horarioescolar['fk_turnos_id']))
    {
      $this->horarioescolar->setFkTurnosId($horarioescolar['fk_turnos_id']);
    }
  }


    function addFiltersCriteria($c) {
        $c->add(horarioescolarPeer::FK_ESTABLECIMIENTO_ID,$this->getUser()->getAttribute('fk_establecimiento_id'));
    }


    function saveHorarioescolar($horarioescolar) {
        $horarioescolar->setFkEstablecimientoId($this->getUser()->getAttribute('fk_establecimiento_id'));
        $horarioescolar->save();
    }


    public function executeExportToIcal() {
        $this->executeList();
        include("miExportadorIcal.class.php");
        $e  = new miExportadorIcal();
        $e->exportar($this->pager->getResults());
    }


    public function executeVerCalendario() {
        $this->executeList();
        include("miExportadorIcal.class.php");
        $e  = new miExportadorIcal();
        $this->archivo = sfConfig::get('app_alba_tmpdir')."/".$e->exportar($this->pager->getResults(), 0);
        if($this->getRequestParameter('date')) {
            $this->date_component = $this->getRequestParameter('date');
        } else {
            $this->date_component = "";
        }

        if($this->getRequestParameter('view')) {
            switch($this->getRequestParameter('view')) {
                case 'week': $this->view = 'verPorSemana'; break;
                case 'day': $this->view = 'verPorDia'; break;
                default: $this->view = 'verPorDia';
            }
        } else {
            $this->view = "verPorDia";
        }
    }


    
    protected function getLabels() {
        return array(
        'horarioescolar{nombre}' => 'Nombre:',
        'horarioescolar{descripcion}' => 'Descripcion:',
        'horarioescolar{fk_horarioescolartipo_id}' => 'Tipo Horario Escolar:',
        'horarioescolar{fk_turnos_id}' => 'Turno:',
        'horarioescolar{fk_evento_id}' => 'Evento:',
        );
    }
}
?>
