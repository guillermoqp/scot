<?php

class Observacion_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('general/Actividad_model');
    }
    
    //GRUPO DE OBSERVACIÓN

    public function get_one_grupoObservacion($idGrupoObservacion) {
        $query = $this->db->query('SELECT * FROM "Grupo_observacion_Actividad" '
                . 'WHERE "GoaId" = ? AND "GoaEli" = FALSE', array($idGrupoObservacion));
        return $query->row_array();
    }

    public function get_all_grupoObservacion() {
        $query = $this->db->query('SELECT * FROM "Grupo_observacion_Actividad" '
                . 'WHERE "GoaEli" = FALSE ORDER BY "GoaDes" ASC');
        return $query->result_array();
    }

    public function get_all_grupoObservacion_x_actividad($idActividad, $idContratante) {
        $query = $this->db->query('SELECT * FROM "Grupo_observacion_Actividad" '
                . 'WHERE "GoaEli" = FALSE AND "GoaActId" = ? AND "GoaCntId" = ? '
                . 'ORDER BY "GoaDes" ASC;', array($idActividad, $idContratante));
        return $query->result_array();
    }

    public function get_all_grupoObservacion_actividad($idContratante) {
        $actividades = $this->Actividad_model->get_all_Actividad();
        foreach ($actividades as $key => $actividad) {
            $actividades[$key]['gruposObservacion'] = $this->get_all_grupoObservacion_x_actividad($actividad['ActId'], $idContratante);
        }
        return $actividades;
    }
    
    public function insert_grupoObservacion($descripcion, $idActividad, $idContratista) {
        $query = $this->db->query('INSERT into "Grupo_observacion_Actividad" ("GoaDes", "GoaEli", "GoaFchRg", "GoaFchAc", "GoaActId", "GoaCntId") VALUES (?,?,?,?,?,?); '
                , array($descripcion, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idActividad, $idContratista));
        return $query;
    }
    
    public function update_grupoObservacion($idGrupoObservacion, $idActividad, $descripcion) {
        $query = $this->db->query('UPDATE "Grupo_observacion_Actividad" SET "GoaDes" = ?, "GoaActId" = ?, "GoaFchAc" = ? WHERE "GoaId" = ? ;'
                , array($descripcion, $idActividad, date("Y-m-d H:i:s"), $idGrupoObservacion));
        return $query;
    }
    
    public function delete_grupoObservacion($idGrupoObservacion) {
        $query = $this->db->query('UPDATE "Grupo_observacion_Actividad" SET "GoaEli" = TRUE, "GoaFchAc" = ? '
                . 'WHERE "GoaId" = ?', array(date("Y-m-d H:i:s"), $idGrupoObservacion));
        return $query;
    }
    
    //OBSERVACIÓN

    public function get_one_observacion($idObservacion) {
        $query = $this->db->query('SELECT "Observacion".*, "Grupo_observacion_Actividad"."GoaActId" as "AuxActId" FROM "Observacion" '
                . 'JOIN "Grupo_observacion_Actividad" ON "ObsGoaId" = "GoaId"  '
                . 'WHERE "ObsId" = ? AND "ObsEli" = FALSE ', array($idObservacion));
        return $query->row_array();
    }
    
    public function get_all_observacion($idContratante) 
    {
        $actividades = $this->get_all_grupoObservacion_actividad($idContratante);
        foreach ($actividades as $key => $actividad) {
            foreach ($actividades[$key]['gruposObservacion'] as $key2 => $grupoObservacion) {
                $observaciones = $this->get_observacion_x_grupo($grupoObservacion['GoaId']);
                $actividades[$key]['gruposObservacion'][$key2]['observaciones'] = $observaciones;
            }
        }
        return $actividades;
    }

    public function get_observacion_x_grupo($idGrupo) {
        $query = $this->db->query('SELECT * FROM "Observacion" '
                . 'WHERE "ObsEli" = FALSE AND "ObsGoaId" = ? ORDER BY "ObsCod" ASC', array($idGrupo));
        return $query->result_array();
    }

    public function insert_observacion($codigo, $descripcion, $idGrupo, $idContratante) {
        $query = $this->db->query('INSERT into "Observacion" ("ObsCod", "ObsDes", "ObsEli", "ObsFchRg", "ObsFchAc", "ObsGoaId", "ObsCntId") VALUES (?,?,?,?,?,?,?); '
                , array($codigo, $descripcion, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idGrupo, $idContratante));
        return $query;
    }

    public function update_observacion($codigo, $descripcion, $idGrupo, $idObservacion) {
        $query = $this->db->query('UPDATE "Observacion" SET "ObsCod" = ?, "ObsDes" = ?, "ObsFchAc" = ?, "ObsGoaId" = ? WHERE "ObsId" = ? ;'
                , array($codigo, $descripcion, date("Y-m-d H:i:s"), $idGrupo, $idObservacion));
        return $query;
    }
    
    public function delete_observacion($idObservacion) {
        $query = $this->db->query('UPDATE "Observacion" SET "ObsEli" = TRUE, "ObsFchAc" = ? '
                . 'WHERE "ObsId" = ?', array(date("Y-m-d H:i:s"), $idObservacion));
        return $query;
    }
    

    /*  METODO PARA OBTENER LAS OBSERVACIONES POR ID DE LA ACTIVIDAD Y ID DE LA CONTRATANTE   */
    //ConsultasMovil_cst_ctrllr -> obtener_observaciones_lectura_json
    public function get_all_observacion_movil($idActividad,$idContratante) 
    {
        $query = $this->db->query('SELECT "Observacion"."ObsId","Observacion"."ObsCod","Observacion"."ObsDes" FROM "Observacion" '
                . 'JOIN "Grupo_observacion_Actividad" ON "ObsGoaId" = "GoaId" '
                . 'JOIN "Actividad" ON "GoaActId" = "ActId" '
                . 'WHERE "ActId" = ? AND "ObsCntId" = ? AND "ObsEli" = FALSE', array($idActividad,$idContratante));
        return $query->result_array();
    }
    
    

}
