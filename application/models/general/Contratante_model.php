<?php

class Contratante_model extends CI_Model 
{

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('general/Empresa_model');
        
    }

    public function get_one_contratante($idContratante) {
        $query = $this->db->query('SELECT * FROM "Contratante" '
                . 'WHERE "CntId" = ? AND "CntEli" = FALSE', array($idContratante));
        return $query->row_array();
    }
    
    public function get_one_contratante_empresa($idContratante) {
        $query = $this->db->query('SELECT * FROM "Contratante" JOIN "Empresa" ON "CntEmpId" = "EmpId" WHERE "EmpEli" = FALSE AND "CntEli" = FALSE AND "CntId" = ?',array($idContratante));
        return $query->row_array();
    }
    
    public function get_all_contratante() 
    {
        $query = $this->db->query('SELECT * FROM "Contratante" WHERE "CntEli" = FALSE');
        return $query->result_array();
    }
    
    public function insert_contratante($EmpRuc,$EmpRaz, $direccion, $tel11 , $email11 , $EmpDniRp ,$EmpNomRp,$CntLog1,$CntLog2,$EmpId)
    {
        $this->db->trans_start();
        /* ES EMPRESA INDIVIDUAL */
        if($this->Empresa_model->get_one_id_empresa_x_ruc($EmpRuc)!=false)
        {
            $idEmpresaContratante = $this->Empresa_model->get_one_id_empresa_x_ruc($EmpRuc);
        }
        else 
        {
            $idEmpresaContratante = $this->Empresa_model->insert_empresa($EmpRuc,$EmpRaz, $direccion,$tel11 ,$email11 ,$EmpNomRp , $EmpDniRp); 
        }
        
        $query = $this->db->query('INSERT into "Contratante" ("CntLog1","CntLog2", "CntEli" , "CntFchRg" , "CntFchAc","CntEmpId") VALUES (?,?,?,?,?,?); '
                , array($CntLog1,$CntLog2, false ,  date("Y-m-d H:i:s"), 
                   date("Y-m-d H:i:s"), $idEmpresaContratante));
        $this->db->trans_complete();

        return $query;
    }
    
    public function update_contratante($EmpRuc,$EmpRaz, $direccion, $tel11 , $email11 , $EmpDniRp ,$EmpNomRp,$CntLog1,$CntLog2, $EmpId, $idContratante) 
    {
        $this->db->trans_start();
        /* ES EMPRESA INDIVIDUAL */
            /*  ACTUALIZA LOS DATOS DE LA CONTRATISTA EN EMPRESA, CUANDO ES INDIVIDUAL*/
            echo(json_encode($EmpId));
            $this->Empresa_model->update_empresa_x_id($EmpRuc, $EmpRaz, $direccion , $tel11 , $email11 , $EmpNomRp , $EmpDniRp, $EmpId);
        
        /* ACTUALIZA SOLO LOS DATOS DE LA CONTRATISTA */
        $this->db->query('UPDATE "Contratante" SET "CntLog1" = ?,"CntLog2" = ?, "CntFchAc" = ? WHERE "CntId" = ? ;'
                , array($CntLog1,$CntLog2, date("Y-m-d H:i:s"), $idContratante ));
       
        $this->db->trans_complete();
        return $query;
    }

    public function delete_contratante($idContratante) 
    {
        $query = $this->db->query('UPDATE "Contratante" SET "CntEli" = TRUE, "CntFchAc" = ? '
                . 'WHERE "CntId" = ?', array(date("Y-m-d H:i:s"),$idContratante));
        return $query;
    }
}
