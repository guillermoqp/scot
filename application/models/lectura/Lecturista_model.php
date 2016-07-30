<?php

class Lecturista_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('acceso/Perfil_model');
    }

    //OTTomaEstado_ctrllr -> avance_suborden
    public function get_one_lecturista($idLecturista) {
        $query = $this->db->query('SELECT * FROM "Lecturista" JOIN "Usuario" '
                . 'ON "LtaUsrId" = "UsrId" WHERE "LtaEli" = FALSE AND "LtaId" = ?', array($idLecturista));
        return $query->row_array();
    }

    //Lecturista_ctrllr -> lecturista_listar
    public function get_all_lecturista($idContratante) {
        $query = $this->db->query('SELECT * FROM "Lecturista" JOIN "Usuario" '
                . 'ON "LtaUsrId" = "UsrId" WHERE "LtaEli" = FALSE AND "LtaCntId" = ? ORDER BY "LtaSup" DESC, "UsrApePt" ASC', array($idContratante));
        return $query->result_array();
    }
    
    public function get_lecturista_supervisor($idContratante) {
        $query = $this->db->query('SELECT * FROM "Lecturista" JOIN "Usuario" ON "LtaUsrId" = "UsrId" WHERE "LtaEli" = FALSE AND "LtaSup" = TRUE AND "LtaCntId" = ?', array($idContratante));
        return $query->row_array();
    }
    
    
    //OTTomaEstado_ctrllr -> orden_nuevo
    public function get_cant_lecturista_x_contrato($idContrato) {
        $query = $this->db->query('SELECT count("Lecturista".*) as cantidad FROM "Lecturista" JOIN "Usuario" ON "LtaUsrId" = "UsrId" '
                . 'JOIN "Contratista" ON "UsrCstId" = "CstId" JOIN "Contrato" ON "ConCstId" = "CstId" WHERE "LtaEli" = FALSE AND "ConId" = ?', array($idContrato));
        $row = $query->row_array();
        return $row['cantidad'];
    }

    //OTTomaEstado_ctrllr -> orden_nuevo
    public function get_lecturista_disponibles($idContratante, $idContrato, $fechaEj) {
        $query = $this->db->query('SELECT DISTINCT "Lecturista".*, "Usuario".* FROM ("Lecturista" JOIN "Usuario" '
                . 'ON "LtaUsrId" = "UsrId") LEFT JOIN ("Lectura" JOIN "Suborden_lectura" ON "LecSolId" = "SolId" JOIN "Orden_trabajo" ON "SolOrtId" = "OrtId" '
                . 'AND "SolFchEj" = ? AND "OrtConId" = ? AND "LecEli" = FALSE) ON "LtaId" = "SolLtaId" '
                . 'WHERE "LtaEli" = FALSE AND "LtaCntId" = ? AND "LtaSup" = FALSE AND "SolLtaId" IS NULL ORDER BY "LtaId"', array($fechaEj, $idContrato, $idContratante));
        $rows = $query->result_array();
        return array_column($rows, null, 'LtaId');
    }
    
    //this -> update_lecturista
    public function insert_lecturista($idUsuario, $idContratante, $esSupervisor) {
        $query = $this->db->query('INSERT into "Lecturista" ("LtaEli", "LtaFchRg", "LtaFchAc", "LtaSup", "LtaUsrId", "LtaCntId") VALUES (?,?,?,?,?,?); '
                , array(FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $esSupervisor, $idUsuario, $idContratante));
        return $query;
    }

    // Usuario_ctrllr
    public function update_lecturista($idUsuario, $roles, $idContratante) {
        // boolean si tiene el rol de supervisor
        $chkSupervisor = $this->Perfil_model->is_rol($roles, 'supervisor_lectura');
        $chkLecturista = $this->Perfil_model->is_rol($roles, 'lecturista');
        if ($this->is_lecturista($idUsuario)) {
            // es o ha sido un lecturista o supervisor
            if ($this->is_activo($idUsuario)) {
                // esta activo
                if (!$chkLecturista) {
                    //se deselecciono lecturista
                    if ($chkSupervisor) {
                        $this->restore_lecturista($idUsuario, $idContratante, $chkSupervisor);
                    } else {
                        //no esta marcado lecturista
                        $this->delete_lecturista($idUsuario, $idContratante);
                    }
                }
            } else {
                //no esta activo
                if ($chkLecturista || $chkSupervisor) {
                    $this->restore_lecturista($idUsuario, $idContratante, $chkSupervisor);
                }
            }
        } else {
            // no esta registrado como lecturista
            if ($chkLecturista || $chkSupervisor) {
                $this->insert_lecturista($idUsuario, $idContratante, $chkSupervisor);
            }
        }
        return true;
    }

    public function delete_lecturista($idUsuario, $idContratante) {
        $query = $this->db->query('UPDATE "Lecturista" SET "LtaEli" = TRUE, "LtaFchAc" = ? '
                . 'WHERE "LtaUsrId" = ? AND "LtaCntId" = ?', array(date("Y-m-d H:i:s"), $idUsuario, $idContratante));
        return $query;
    }

    // this -> update_lecturista
    public function restore_lecturista($idUsuario, $idContratante, $esSupervisor) {
        $query = $this->db->query('UPDATE "Lecturista" SET "LtaEli" = FALSE, "LtaFchAc" = ?, "LtaSup" = ? '
                . 'WHERE "LtaUsrId" = ? AND "LtaCntId" = ?', array(date("Y-m-d H:i:s"), $esSupervisor, $idUsuario, $idContratante));
        return $query;
    }

    public function is_lecturista($idUsuario) {
        $query = $this->db->query('SELECT * FROM "Lecturista" WHERE "LtaUsrId" = ?', array($idUsuario));
        return ($query->num_rows() > 0) ? true : false;
    }

    public function is_activo($idUsuario) {
        $query = $this->db->query('SELECT * FROM "Lecturista" WHERE "LtaUsrId" = ? AND "LtaEli" = FALSE ', array($idUsuario));
        return ($query->num_rows() > 0) ? true : false;
    }

    //OTTomaEstado_model -> get_avance_x_orden
    public function get_lecturista_x_ciclo_x_orden($idOrden, $cicloCod) {
        $query = $this->db->query('SELECT DISTINCT "Usuario".*, "Lecturista".*,"SolCod","SolFchEj",cast("SolCod" as int) as "Numero" FROM "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId" 
        JOIN "Lecturista" ON "SolLtaId" = "LtaId" 
        JOIN "Usuario" ON "UsrId" = "LtaUsrId" 
        JOIN "Grupo_predio" ON "LecGprId" = "GprId"
        JOIN "Orden_trabajo" ON "SolOrtId" = "OrtId"
        WHERE "SolOrtId" = ? AND "LecEli" = FALSE AND "GprCod" = ? ORDER BY cast("SolCod" as int) ASC', array($idOrden, $cicloCod));
        return $query->result_array();
    }

}