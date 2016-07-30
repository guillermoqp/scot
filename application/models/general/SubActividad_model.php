<?php

class SubActividad_model extends CI_Model 
{

    function __construct() {
        parent::__construct();
        $this->load->database();
        
    }

    public function get_one_subactividad($idSubActividad) {
        $query = $this->db->query('SELECT * FROM "Subactividad" '
                . 'WHERE "SacId" = ? AND "SacEli" = FALSE', array($idSubActividad));
        return $query->row_array();
    }
    
    public function get_one_subactividad_x_codigo($codSubActividad) {
        $query = $this->db->query('SELECT * FROM "Subactividad" '
                . 'WHERE "SacCod" = ? AND "SacEli" = FALSE', array($codSubActividad));
        return $query->row_array();
    }
    

    public function get_all_subactividad() 
    {
        $query = $this->db->query('SELECT * FROM "Subactividad" '
                . 'WHERE "SacEli" = FALSE ORDER BY "SacDes" ASC');
        return $query->result_array();
    }
    
    // SubActividad_ctrllr ->
    // Valorizacion_model -> insertar_valorizacion
    public function get_subactividad_x_actividad($idActividad)
    {
        $query = $this->db->query('SELECT * FROM "Subactividad" '
                . 'WHERE "SacActId" = ? AND "SacEli" = FALSE ORDER BY "SacDes" ASC',array($idActividad));
        return $query->result_array();
    }

    public function insert_subactividad($idActividad, $idUnidad , $descripcion ) 
    {
        $query = $this->db->query('INSERT into "Subactividad" ( "SacDes", "SacEli", "SacFchRg", "SacFchAc", "SacUniId", "SacActId") VALUES (?,?,?,?,?,?); '
                , array( $descripcion, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idUnidad , $idActividad));
        return $query;
    }

    public function update_subactividad($idSubActividad, $idActividad, $idUnidad , $descripcion)
    {
        $query = $this->db->query('UPDATE "Subactividad" SET "SacDes" = ?, "SacFchAc" = ?, "SacActId" = ? , "SacUniId" = ? WHERE "SacId" = ? ;'
                , array( $descripcion, date("Y-m-d H:i:s"),$idActividad, $idUnidad , $idSubActividad));
        return $query;
    }

    public function delete_subactividad($idSubActividad) 
    {
        $query = $this->db->query('UPDATE "Subactividad" SET "SacEli" = TRUE, "SacFchAc" = ? '
                . 'WHERE "SacId" = ?', array(date("Y-m-d H:i:s"), $idSubActividad));
        return $query;
    }

     public function delete_subactividad_x_actividad($idActividad) 
    {
        $query = $this->db->query('UPDATE "Subactividad" SET "SacEli" = TRUE, "SacFchAc" = ? '
                . 'WHERE "SacActId" = ? AND "SacEli" = FALSE',array(date("Y-m-d H:i:s"),$idActividad));
        return $query;
    }

}
