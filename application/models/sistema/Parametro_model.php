<?php

class Parametro_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_Parametro() {
        $query = $this->db->query('SELECT * FROM "Parametro" WHERE "ParEli" = FALSE ORDER BY "ParDes" ASC');
        return $query->result_array();
    }
    
    public function get_one_parametro_x_id($idParametro) {
        $query = $this->db->query('SELECT * FROM "Parametro" '
                . 'WHERE "ParId" = ? AND "ParEli" = FALSE', array($idParametro));
        return $query->row_array();
    }

    public function get_one_parametro($codParametro) {
        $query = $this->db->query('SELECT * FROM "Parametro" '
                . 'WHERE "ParCod" = ? AND "ParEli" = FALSE', array($codParametro));
        return $query->row_array();
    }

    public function get_one_detParam($idDetParam) {
        $query = $this->db->query('SELECT * FROM "Detalle_parametro" '
                . 'WHERE "DprId" = ? AND "DprEli" = FALSE', array($idDetParam));
        return $query->row_array();
    }

    public function get_one_detParam_x_codigo($codDetParam) 
    {
        $query = $this->db->query('SELECT * FROM "Detalle_parametro" '
                . 'WHERE "DprCod" = ? AND "DprEli" = FALSE', array($codDetParam));
        return $query->row_array();
    }

    public function get_all_detParam($idParametro) {
        $query = $this->db->query('SELECT * FROM "Detalle_parametro" '
                . 'WHERE "DprParId" = ? AND "DprEli" = FALSE ORDER BY "DprDes" ASC', array($idParametro));
        return $query->result_array();
    }

    public function insert_detParam($idParametro, $descripcion) {
        $query = $this->db->query('INSERT into "Detalle_parametro" ("DprDes", "DprEli", "DprFchRg", "DprFchAc", "DprParId") VALUES (?,?,?,?,?); '
                , array($descripcion, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"),$idParametro));
        return $query;
    }

    public function update_detParam($idDetParam, $descripcion) {
        $query = $this->db->query('UPDATE "Detalle_parametro" SET "DprDes" = ?, "DprFchAc" = ? WHERE "DprId" = ? ;'
                , array($descripcion, date("Y-m-d H:i:s"), $idDetParam));
        return $query;
    }

    public function delete_detParam($idDetParam) {
        $query = $this->db->query('UPDATE "Detalle_parametro" SET "DprEli" = TRUE, "DprFchAc" = ? '
                . 'WHERE "DprId" = ?', array(date("Y-m-d H:i:s"), $idDetParam));
        return $query;
    }
    
    public function delete_parametro($idParametro) {
        $query = $this->db->query('UPDATE "Parametro" SET "ParEli" = TRUE, "ParFchAc" = ? '
                . 'WHERE "ParId" = ?', array( date("Y-m-d H:i:s"), $idParametro));
        return $query;
    }
}
