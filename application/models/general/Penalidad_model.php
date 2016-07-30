<?php

class Penalidad_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // GRUPO PENALIDAD
    
    public function get_one_grpPenalidad($idGrpPenalidad) {
        $query = $this->db->query('SELECT * FROM "Grupo_penalidad" '
                . 'WHERE "GpeId" = ?', array($idGrpPenalidad));
        return $query->row_array();
    }

    public function get_all_grpPenalidad($idContratante) {
        $query = $this->db->query('SELECT * FROM "Grupo_penalidad" '
                . 'WHERE "GpeEli" = FALSE AND "GpeCntId" = ? ORDER BY "GpeCod"::int ASC;', array($idContratante));
        return $query->result_array();
    }

    public function get_all_grpPenalidad_penalidad($idContratante) {
        $grupos = $this->get_all_grpPenalidad($idContratante);
        foreach ($grupos as $key => $grupo) {
            $penalidades = $this->get_penalidad_x_grupo($grupo['GpeId']);
            $grupos[$key]['penalidades'] = $penalidades;
        }
        return $grupos;
    }

    public function insert_grpPenalidad($codigo, $descripcion, $idContratante) {
        $query = $this->db->query('INSERT into "Grupo_penalidad" ("GpeCod", "GpeDes", "GpeEli", "GpeFchRg", "GpeFchAc", "GpeCntId") VALUES (?,?,?,?,?,?); '
                , array($codigo, $descripcion, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idContratante));
        return $query;
    }

    public function update_grpPenalidad($idGrpPenalidad, $descripcion, $codigo) {
        $query = $this->db->query('UPDATE "Grupo_penalidad" SET "GpeCod" = ?, "GpeDes" = ?, "GpeFchAc" = ? WHERE "GpeId" = ? ;'
                , array($codigo, $descripcion, date("Y-m-d H:i:s"), $idGrpPenalidad));
        return $query;
    }

    public function delete_grpPenalidad($idGrpPenalidad) {
        $query = $this->db->query('UPDATE "Grupo_penalidad" SET "GpeEli" = TRUE, "GpeFchAc" = ? '
                . 'WHERE "GpeId" = ?', array(date("Y-m-d H:i:s"), $idGrpPenalidad));
        return $query;
    }
    
    //PENALIDAD
    
    public function get_one_penalidad($idPenalidad) {
        $query = $this->db->query('SELECT * FROM "Penalidad" '
                . 'WHERE "PenId" = ?', array($idPenalidad));
        return $query->row_array();
    }

    public function get_all_penalidad() {
        $query = $this->db->query('SELECT * FROM "Penalidad" '
                . 'WHERE "PenEli" = FALSE ORDER BY "PenDes" ASC;');
        return $query->result_array();
    }

    public function get_penalidad_x_grupo($idGrupo) {
        $query = $this->db->query('SELECT * FROM "Penalidad" '
                . 'WHERE "PenGpeId" = ? AND "PenEli" = FALSE ORDER BY "PenCod" ASC;', array($idGrupo));
        return $query->result_array();
    }

    public function insert_penalidad($idGrupo, $codigo, $descripcion, $idContratante) {
        $query = $this->db->query('INSERT into "Penalidad" ("PenCod", "PenDes", "PenEli", "PenFchRg", "PenFchAc", "PenGpeId", "PenCntId") VALUES (?,?,?,?,?,?,?); '
                , array($codigo, $descripcion, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idGrupo, $idContratante));
        return $query;
    }

    public function update_penalidad($idPenalidad, $idGrupo, $codigo, $descripcion) {
        $query = $this->db->query('UPDATE "Penalidad" SET "PenCod" = ?, "PenDes" = ?, "PenFchAc" = ?, "PenGpeId" = ? WHERE "PenId" = ? ;'
                , array($codigo, $descripcion, date("Y-m-d H:i:s"), $idGrupo, $idPenalidad));
        return $query;
    }

    public function delete_penalidad($idPenalidad) {
        $query = $this->db->query('UPDATE "Penalidad" SET "PenEli" = TRUE, "PenFchAc" = ? '
                . 'WHERE "PenId" = ?', array(date("Y-m-d H:i:s"), $idPenalidad));
        return $query;
    }

}
