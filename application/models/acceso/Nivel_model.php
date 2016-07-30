<?php

class Nivel_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_one_nivel($idNivel) {
        $query = $this->db->query('SELECT * FROM "Nivel" '
                . 'WHERE "NvlId" = ?', array($idNivel));
        return $query->row_array();
    }

    // tipo: del contratista o del contratante
    public function get_nivel_x_tipo($idTipo) {
        $query = $this->db->query('SELECT * FROM "Nivel" '
                . 'WHERE "NvlEli" = FALSE AND "NvlCst" = ? ORDER BY "NvlOrd" ASC', array($idTipo));
        return $query->result_array();
    }
    
    public function insert_nivel($tipoOrgId, $descripcion) {
        $query = $this->db->query('SELECT "NvlOrd" FROM "Nivel" '
                . 'WHERE "NvlEli" = FALSE AND "NvlCst" = ? ORDER BY "NvlOrd" DESC LIMIT 1', array($tipoOrgId));
        $lastOrden = $query->row_array();
        $query = $this->db->query('INSERT into "Nivel" ("NvlCst", "NvlDes", "NvlOrd", "NvlEli", "NvlFchRg", "NvlFchAc") VALUES (?,?,?,?,?,?); '
                , array($tipoOrgId, $descripcion, $lastOrden['NvlOrd'] + 1, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")));
        return $idUsuario;
    }

    public function update_nivel($idNivel, $descripcion) {
        $query = $this->db->query('UPDATE "Nivel" SET "NvlDes" = ?, "NvlFchAc" = ? WHERE "NvlId" = ? ;'
                , array($descripcion, date("Y-m-d H:i:s"), $idNivel));
        return $query;
    }

    public function delete_nivel($idNivel) {
        $query = $this->db->query('UPDATE "Nivel" SET "NvlEli" = TRUE, "NvlFchAc" = ? '
                . 'WHERE "NvlId" = ?', array(date("Y-m-d H:i:s"), $idNivel));
        return $query;
    }

    public function go_up_nivel($idNivel) {
        $nivelActual = $this->get_one_nivel($idNivel);
        $query = $this->db->query('SELECT * FROM "Nivel" '
                . 'WHERE "NvlEli" = FALSE AND "NvlCst" = ? AND "NvlOrd" < ? ORDER BY "NvlOrd" DESC LIMIT 1', array($nivelActual['NvlCst'], $nivelActual['NvlOrd']));
        $nivelSuperior = $query->row_array();
        if (isset($nivelSuperior)) {
            return $this->swap_order_nivel($nivelActual, $nivelSuperior);
        } else {
            return FALSE;
        }
    }

    public function go_down_nivel($idNivel) {
        $nivelActual = $this->get_one_nivel($idNivel);
        $query = $this->db->query('SELECT * FROM "Nivel" '
                . 'WHERE "NvlEli" = FALSE AND "NvlCst" = ? AND "NvlOrd" > ? ORDER BY "NvlOrd" ASC LIMIT 1', array($nivelActual['NvlCst'], $nivelActual['NvlOrd']));
        $nivelInferior = $query->row_array();
        if (isset($nivelInferior)) {
            return $this->swap_order_nivel($nivelActual, $nivelInferior);
        } else {
            return FALSE;
        }
    }

    public function swap_order_nivel($nivelActual, $otroNivel) 
    {
        $this->db->trans_start();
        $query = $this->db->query('UPDATE "Nivel" SET "NvlOrd" = ?, "NvlFchAc" = ? WHERE "NvlId" = ? ;'
                , array($otroNivel['NvlOrd'], date("Y-m-d H:i:s"), $nivelActual['NvlId']));
        $query = $this->db->query('UPDATE "Nivel" SET "NvlOrd" = ?, "NvlFchAc" = ? WHERE "NvlId" = ? ;'
                , array($nivelActual['NvlOrd'], date("Y-m-d H:i:s"), $otroNivel['NvlId']));
        $this->db->trans_complete();
        return $query;
    }

}
