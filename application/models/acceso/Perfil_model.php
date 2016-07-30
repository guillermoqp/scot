<?php

class Perfil_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('general/Actividad_model');
    }

    public function get_one_rol($idRol) {
        $query = $this->db->query('SELECT * FROM "Rol" '
                . 'WHERE "RolId" = ? AND "RolEli" = FALSE', array($idRol));
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

    public function get_all_roles_x_tipo($idUsuario) {
        $tipo[0] = $this->get_roles_x_tipo(1,$idUsuario);
        $tipo[1] = $this->get_roles_x_tipo(0,$idUsuario);
        return $tipo;
    }
    
    public function get_roles_x_tipo($tipo, $idContratante){
        $query = $this->db->query('SELECT * FROM "Rol" WHERE "RolEli" = FALSE '
                . 'AND "RolCntId" = ? ORDER BY "RolDes" ASC',array(($idContratante)));
        return $query->result_array();
    }

    public function insert_rol($descripcion, $codigo, $rolPermisos, $idContratante) {
        $this->db->trans_start();
        $query = $this->db->query('INSERT into "Rol" ("RolDes", "RolCod", "RolEli", "RolFchRg", "RolFchAc", "RolCntId") VALUES (?,?,?,?,?,?); '
                , array($descripcion, $codigo, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idContratante));
        $idRol = $this->db->insert_id();
        $permisos = array();
        foreach ($rolPermisos as $rolPermiso) {
            $query = $this->db->query('INSERT into "Rol_Permiso" ("RlpEli", "RlpFchRg", "RlpFchAc", "RlpRolId", "RlpPerId") VALUES (?,?,?,?,?); '
                    , array(FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idRol, $rolPermiso));
        }
        $this->db->trans_complete();
        return $query;
    }

    public function update_rol($idRol, $descripcion, $rolPermisos) {
        $this->db->trans_start();
        $query = $this->db->query('UPDATE "Rol" SET "RolDes" = ?, "RolFchAc" = ? WHERE "RolId" = ? ;'
                , array($descripcion, date("Y-m-d H:i:s"), $idRol));
        $query = $this->db->query('DELETE FROM "Rol_Permiso" '
                . 'WHERE "RlpRolId" = ?', array($idRol));
        $permisos = array();
        foreach ($rolPermisos as $rolPermiso) {
            $query = $this->db->query('INSERT into "Rol_Permiso" ("RlpEli", "RlpFchRg", "RlpFchAc", "RlpRolId", "RlpPerId") VALUES (?,?,?,?,?); '
                    , array(FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idRol, $rolPermiso));
        }
        $this->db->trans_complete();
        return $query;
    }

    public function delete_rol($idRol) {
        $query = $this->db->query('UPDATE "Rol" SET "RolEli" = TRUE, "RolFchAc" = ? '
                . 'WHERE "RolId" = ?', array(date("Y-m-d H:i:s"), $idRol));
        return $query;
    }
    
    public function is_rol($roles, $codigo){
        $query = $this->db->query('SELECT * from "Rol" WHERE "RolCod" = ?',array($codigo));
        $row = $query->row_array();
        $esta = in_array($row['RolId'], $roles);
        return $esta;
    }

}
