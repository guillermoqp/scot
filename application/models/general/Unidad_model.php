<?php

class Unidad_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_one_unidad($idUnidad) {
        $query = $this->db->query('SELECT * FROM "Unidad_medida" '
                . 'WHERE "UniId" = ?', array($idUnidad));
        return $query->row_array();
    }

    public function get_all_unidad() 
    {
        $query = $this->db->query('SELECT * FROM "Unidad_medida" '
                . 'WHERE "UniEli" = FALSE ORDER BY "UniDes" ASC');
        return $query->result_array();
    }

    public function insert_unidad($descripcion) {
        $query = $this->db->query('INSERT into "Unidad_medida" ("UniDes", "UniEli", "UniFchRg", "UniFchAc") VALUES (?,?,?,?); '
                , array($descripcion, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")));
        return $query;
    }

    public function update_unidad($idUnidad, $descripcion) {
        $query = $this->db->query('UPDATE "Unidad_medida" SET "UniDes" = ?, "UniFchAc" = ? WHERE "UniId" = ? ;'
                , array($descripcion, date("Y-m-d H:i:s"), $idUnidad));
        return $query;
    }

    public function delete_unidad($idUnidad) {
        $query = $this->db->query('UPDATE "Unidad_medida" SET "UniEli" = TRUE, "UniFchAc" = ? '
                . 'WHERE "UniId" = ?', array(date("Y-m-d H:i:s"), $idUnidad));
        return $query;
    }

}
