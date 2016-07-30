<?php

class Cargo_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('general/Actividad_model');
    }

    public function get_one_cargo($idCargo) {
        $query = $this->db->query('SELECT * FROM "Cargo" '
                . 'WHERE "CarId" = ? AND "CarEli" = FALSE', array($idCargo));
        return $query->row_array();
    }

    public function get_all_rol($idContratante) {
        $query = $this->db->query('SELECT * FROM "Rol" '
                . 'WHERE "RolEli" = FALSE AND "RolCntId" = ?', array($idContratante));
        return $query->result_array();
    }

    public function get_roles_x_usuario($idUsuario) {
        $query = $this->db->query('SELECT * FROM "Rol_Usuario" JOIN "Rol" ON "RusRolId" = "RolId" '
                . 'WHERE "RusUsrId" = ? AND "RolEli" = FALSE', array($idUsuario));
        return $query->result_array();
    }

    public function get_all_cargos_x_tipo($idUsuario) {
        $tipo[0] = $this->get_cargos_x_tipo(1,$idUsuario);
        $tipo[1] = $this->get_cargos_x_tipo(0,$idUsuario);
        return $tipo;
    }
    
    public function get_cargos_x_tipo($tipo, $idContratante){
        $esContratante = ($tipo == 1);
        $query = $this->db->query('SELECT * FROM "Cargo" JOIN "Nivel" ON "CarNvlId" = "NvlId" WHERE "CarEli" = FALSE '
                . 'AND "CarCst" = ? AND "CarCntId" = ? ORDER BY "CarDes" ASC',array((!$esContratante), ($idContratante)));
        return $query->result_array();
    }

    public function insert_cargo($descripcion, $idNivel, $esContratista, $idContratante) {
        $query = $this->db->query('INSERT into "Cargo" ("CarDes", "CarCst", "CarEli", "CarFchRg", "CarFchAc", "CarNvlId", "CarCntId") VALUES (?,?,?,?,?,?,?); '
                , array($descripcion, $esContratista, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idNivel, $idContratante));
        return $query;
    }

    public function update_cargo($idCargo, $descripcion) {
        $query = $this->db->query('UPDATE "Cargo" SET "CarDes" = ?, "CarFchAc" = ? WHERE "CarId" = ? ;'
                , array($descripcion, date("Y-m-d H:i:s"), $idCargo));
        return $query;
    }

    public function delete_cargo($idCargo) {
        $query = $this->db->query('UPDATE "Cargo" SET "CarEli" = TRUE, "CarFchAc" = ? '
                . 'WHERE "CarId" = ?', array(date("Y-m-d H:i:s"), $idCargo));
        return $query;
    }

}
