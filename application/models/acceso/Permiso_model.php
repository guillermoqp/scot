<?php

class Permiso_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function get_all_permiso_grupos() {
        $query = $this->db->query('SELECT * FROM "Grupo_permiso" '
                . 'WHERE "GpeEli" = FALSE AND "GpeGpeId" IS NULL ORDER BY "GpeDes" ASC');
        $grupos = $query->result_array();
        foreach ($grupos as $key => $grupo) {
            $query = $this->db->query('SELECT * FROM "Grupo_permiso" WHERE "GpeEli" = FALSE AND "GpeGpeId" = ? ORDER BY "GpeDes" ASC', array($grupo['GpeId']));
            $grupos[$key]['subgrupos'] = $query->result_array();
            foreach ($grupos[$key]['subgrupos'] as $key2 => $subgrupo) {
                $query = $this->db->query('SELECT * FROM "Permiso" WHERE "PerEli" = FALSE AND "PerGpeId" = ? ORDER BY "PerId" ASC', array($subgrupo['GpeId']));
                $grupos[$key]['subgrupos'][$key2]['permisos'] = $query->result_array();
            }
        }
        return $grupos;
    }

    public function get_permisos_x_usuario($idUsuario) {
        $query = $this->db->query('SELECT * from "Grupo_permiso" Where "GpeId" IN (SELECT "GpeGpeId" FROM "Grupo_permiso" '
                . 'JOIN "Permiso" ON "PerGpeId" = "GpeId" JOIN "Rol_Permiso" ON "RlpPerId" = "PerId" '
                . 'JOIN "Rol" ON "RlpRolId" = "RolId"  JOIN "Rol_Usuario" ON "RusRolId" = "RolId" '
                . 'WHERE "GpeEli" = FALSE AND "RusUsrId" = ?) ', array($idUsuario));
        $grupos = $query->result_array();
        foreach ($grupos as $key => $grupo) {
            $query = $this->db->query('SELECT "Grupo_permiso".* FROM "Grupo_permiso" '
                    . 'JOIN "Permiso" ON "PerGpeId" = "GpeId" JOIN "Rol_Permiso" ON "RlpPerId" = "PerId" '
                    . 'JOIN "Rol" ON "RlpRolId" = "RolId"  JOIN "Rol_Usuario" ON "RusRolId" = "RolId" '
                    . 'WHERE "GpeEli" = FALSE AND "RusUsrId" = ? AND "GpeGpeId" = ?', array($idUsuario, $grupo['GpeId']));
            $subgrupos = $query->result_array();
            foreach ($subgrupos as $key2 => $subgrupo) {
                $query = $this->db->query('SELECT "Permiso".* FROM "Permiso" JOIN "Rol_Permiso" ON "RlpPerId" = "PerId" JOIN "Rol" ON "RlpRolId" = "RolId" '
                        . 'JOIN "Rol_Usuario" ON "RusRolId" = "RolId" WHERE "PerEli" = FALSE AND "RusUsrId" = ? AND "PerGpeId" = ? ', array($idUsuario, $subgrupo['GpeId']));
                $permisos = $query->result_array();
                $subgrupos[$key2]['permisos'] = $permisos;
            }
            $grupos[$key]['subgrupos'] = array_column($subgrupos, 'permisos', 'GpeVal');
        }
        return array_column($grupos, 'subgrupos', 'GpeVal');
    }

    public function get_permisos_x_rol($idRol) {
        $query = $this->db->query('SELECT * FROM "Permiso" JOIN "Rol_Permiso" ON "PerId" = "RlpPerId" '
                . ' WHERE "RlpRolId" = ?', array($idRol));
        return $query->result_array();
    }
}
