<?php

class Distribuidor_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('acceso/Perfil_model');
    }
    //OTDistribucion_ctrllr -> avance_suborden
    public function get_one_distribuidor($idDistribuidor) {
        $query = $this->db->query('SELECT * FROM "Distribuidor" JOIN "Usuario" ON "DbrUsrId" = "UsrId" WHERE "DbrEli" = FALSE AND "DbrId" = ?', array($idDistribuidor));
        return $query->row_array();
    }
    //Distribuidor_ctrllr -> distribuidor_listar
    public function get_all_distribuidor($idContratante) {
        $query = $this->db->query('SELECT * FROM "Distribuidor" JOIN "Usuario" ON "DbrUsrId" = "UsrId" WHERE "DbrEli" = FALSE AND "DbrCntId" = ? ORDER BY "DbrSup" DESC, "UsrApePt" ASC', array($idContratante));
        return $query->result_array();
    }
    public function get_distribuidor_supervisor($idContratante) {
        $query = $this->db->query('SELECT * FROM "Distribuidor" JOIN "Usuario" ON "DbrUsrId" = "UsrId" WHERE "DbrEli" = FALSE AND "DbrSup" = TRUE AND "DbrCntId" = ?', array($idContratante));
        return $query->row_array();
    }
    //OTDistribucion_ctrllr -> orden_nuevo
    public function get_cant_distribuidores_x_contrato($idContrato) {
        $query = $this->db->query('SELECT count("Distribuidor".*) as cantidad FROM "Distribuidor" JOIN "Usuario" ON "DbrUsrId" = "UsrId"
        JOIN "Contratista" ON "UsrCstId" = "CstId" JOIN "Contrato" ON "ConCstId" = "CstId" WHERE "DbrEli" = FALSE AND "ConId" = ?', array($idContrato));
        $row = $query->row_array();
        return $row['cantidad'];
    }
    //OTDistribucion_ctrllr -> orden_nuevo
    public function get_distribuidores_disponibles($idContratante, $idContrato, $fechaEj) {
        $query = $this->db->query('SELECT DISTINCT "Distribuidor".*, "Usuario".* FROM ("Distribuidor" JOIN "Usuario" 
        ON "DbrUsrId" = "UsrId") LEFT JOIN ("Distribucion" JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId" JOIN "Orden_trabajo" ON "SodOrtId" = "OrtId" 
        AND "SodFchEj" = ? AND "OrtConId" = ? AND "DbnEli" = FALSE) ON "DbrId" = "SodDbrId" 
        WHERE "DbrEli" = FALSE AND "DbrCntId" = ? AND "DbrSup" = FALSE AND "SodDbrId" IS NULL ORDER BY "DbrId"', array($fechaEj, $idContrato, $idContratante) );
        $rows = $query->result_array();
        return array_column($rows, null, 'DbrId');
    }
    //this -> update_lecturista
    public function insert_distribuidor($idUsuario, $idContratante, $esSupervisor) {
        $query = $this->db->query('INSERT INTO "Distribuidor" ("DbrEli", "DbrFchRg", "DbrFchAc", "DbrSup", "DbrUsrId", "DbrCntId") VALUES (?,?,?,?,?,?); '
                , array(FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $esSupervisor, $idUsuario, $idContratante));
        return $query;
    }
    
    // Usuario_ctrllr
    public function update_distribuidor($idUsuario, $roles, $idContratante) {
        
        // boolean si tiene el rol de supervisor
        $chkSupervisor = $this->Perfil_model->is_rol($roles, 'supervisor_distribucion');
        $chkDistribuidor = $this->Perfil_model->is_rol($roles, 'distribuidor');
        
        if ($this->is_distribuidor($idUsuario)) {
            // es o ha sido un distribuidor o supervisor de distribucion
            if ($this->is_activo($idUsuario)) {
                // esta activo
                if (!$chkDistribuidor) {
                    //se deselecciono Distribuidor
                    if ($chkSupervisor) {
                        $this->restore_distribuidor($idUsuario, $idContratante, $chkSupervisor);
                    } else {
                        //no esta marcado distribuidor
                        $this->delete_distribuidor($idUsuario, $idContratante);
                    }
                }
            } else {
                //no esta activo
                if ($chkDistribuidor || $chkSupervisor) {
                    $this->restore_distribuidor($idUsuario, $idContratante, $chkSupervisor);
                }
            }
        } else {
            // no esta registrado como distribuidor
            if ($chkDistribuidor || $chkSupervisor) {
                $this->insert_distribuidor($idUsuario, $idContratante, $chkSupervisor);
            }
        }
        return true;
    }
    public function delete_distribuidor($idUsuario, $idContratante) {
        $query = $this->db->query('UPDATE "Distribuidor" SET "DbrEli" = TRUE, "DbrFchAc" = ? '
                . 'WHERE "DbrUsrId" = ? AND "DbrCntId" = ?', array(date("Y-m-d H:i:s"), $idUsuario, $idContratante));
        return $query;
    }
    // this -> update_distribuidor
    public function restore_distribuidor($idUsuario, $idContratante, $esSupervisor) {
        $query = $this->db->query('UPDATE "Distribuidor" SET "DbrEli" = FALSE, "DbrFchAc" = ?, "DbrSup" = ? '
                . 'WHERE "DbrUsrId" = ? AND "DbrCntId" = ?', array(date("Y-m-d H:i:s"), $esSupervisor, $idUsuario, $idContratante));
        return $query;
    }
    public function is_distribuidor($idUsuario) {
        $query = $this->db->query('SELECT * FROM "Distribuidor" WHERE "DbrUsrId" = ?', array($idUsuario));
        return ($query->num_rows() > 0) ? true : false;
    }
    public function is_activo($idUsuario) {
        $query = $this->db->query('SELECT * FROM "Distribuidor" WHERE "DbrUsrId" = ? AND "DbrEli" = FALSE ', array($idUsuario));
        return ($query->num_rows() > 0) ? true : false;
    }
    //OTDistribucion_model -> get_avance_x_orden
    public function get_distribuidor_x_ciclo_x_orden($idOrden, $cicloCod) {
        $query = $this->db->query('SELECT DISTINCT "Usuario".*, "Distribuidor".*,"SodCod","SodFchEj",cast("SodCod" as int) as "Numero" FROM "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId" 
        JOIN "Distribuidor" ON "SodDbrId" = "DbrId" 
        JOIN "Usuario" ON "UsrId" = "DbrUsrId" 
        JOIN "Grupo_predio" ON "DbnGprId" = "GprId"
        JOIN "Orden_trabajo" ON "SodOrtId" = "OrtId"
        WHERE "SodOrtId" = ? AND "DbnEli" = FALSE AND "GprCod" = ? ORDER BY cast("SodCod" as int) ASC', array($idOrden, $cicloCod));
        return $query->result_array();
    }
}