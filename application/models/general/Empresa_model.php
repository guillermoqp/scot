<?php

class Empresa_model extends CI_Model 
{

    function __construct() 
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_one_empresa($idEmpresa) 
    {
        $query = $this->db->query('SELECT * FROM "Empresa" '
                . 'WHERE "EmpId" = ? AND "EmpEli" = FALSE', array($idEmpresa));
        return $query->row_array();
    }

    public function get_one_empresa_x_ruc($ruc) 
    {
        $query = $this->db->query('SELECT * FROM "Empresa" '
                . 'WHERE "EmpRuc" = ?', array($ruc));
        return $query->row_array();
    }
    
    public function get_one_empresa_contratante_x_ruc($ruc) 
    {
        $query = $this->db->query('SELECT * FROM "Empresa" JOIN "Contratante" ON "EmpId" = "CntEmpId" WHERE "EmpEli" = FALSE AND "EmpRuc" = ?', array($ruc));
        return $query->row_array();
    }
    //Empresas que son contratistas
    public function get_one_empresa_contratista_contratante_x_ruc($ruc) 
    {
        $query = $this->db->query('SELECT * FROM "Empresa" JOIN "Contratista" ON "EmpRuc" = "CstRuc" WHERE "EmpEli" = FALSE AND "EmpRuc" = ?', array($ruc));
        return $query->row_array();
    }
    
    //Empresas que son consorcios
    public function get_one_empresa_consorcio_contratante_x_ruc($ruc) 
    {
        $query = $this->db->query('SELECT * FROM "Empresa" JOIN "Detalle_contratista" ON "EmpId" = "DcoEmpId" WHERE "EmpEli" = FALSE AND "EmpRuc" = ?', array($ruc));
        return $query->row_array();
    }
    
    public function get_one_empresa_x_contratante($idContratante) 
    {
        $query = $this->db->query('SELECT * FROM "Empresa" WHERE "EmpId" = (SELECT "CntEmpId" FROM "Contratante" WHERE "CntId" = ?)', array($idContratante));
        return $query->row_array();
    }    
    
    public function get_one_id_empresa_x_ruc($ruc) 
    {
        $query = $this->db->query('SELECT "EmpId" FROM "Empresa" '
                . 'WHERE "EmpRuc" = ?', array($ruc));
        return $query->row_array();
    }
    

    public function get_one_empresa_detallecontratista($empresaId) 
    {
        $query = $this->db->query('SELECT * FROM "Empresa" JOIN "Detalle_contratista" ON "DcoEmpId" = "EmpId"'.
            'WHERE "EmpId" = ? AND "EmpEli" = FALSE;', array($empresaId));
        return $query->row_array();
    }

    public function get_all_empresa() 
    {
        $query = $this->db->query('SELECT * FROM "Empresa" '
                . 'WHERE "EmpEli" = FALSE');
        return $query->result_array();
    }
 
    public function insert_empresa($ruc, $razSocial, $direccion , $tel , $email , $repLegalNomb , $repLegalDni ) 
    {
        $query = $this->db->query('INSERT into "Empresa" ("EmpRuc", "EmpRaz", "EmpDir", "EmpTel", '
                . '"EmpCor", "EmpNomRp" , "EmpDniRp" , "EmpEli" , "EmpFchRg", "EmpFchAc") VALUES (?,?,?,?,?,?,?,?,?,?); '
                , array($ruc, $razSocial, $direccion , $tel , $email ,  $repLegalNomb , $repLegalDni , false , date("Y-m-d H:i:s"), 
                   date("Y-m-d H:i:s")));
        $query = $this->db->insert_id();
        return $query;
    }
    
    public function update_empresa_x_id($ruc, $razSocial, $direccion , $tel , $email , $repLegalNomb , $repLegalDni, $IdEmpresa) 
    {
        $query = $this->db->query('UPDATE "Empresa" SET "EmpRuc" = ?, "EmpRaz" = ?, "EmpDir" = ?, '
                . '"EmpTel" = ?, "EmpCor" = ? , "EmpNomRp" = ? , "EmpDniRp" = ?, "EmpFchAc" = ? WHERE "EmpId" = ? ;'
                , array($ruc, $razSocial, $direccion , $tel , $email , $repLegalNomb , $repLegalDni , date("Y-m-d H:i:s"), $IdEmpresa));
        return $query;
    }

    public function update_empresa_x_ruc($ruc, $razSocial, $direccion , $tel , $email , $repLegalNomb , $repLegalDni, $RucEmpresa) 
    {
        $query = $this->db->query('UPDATE "Empresa" SET "EmpRuc" = ?, "EmpRaz" = ?, "EmpDir" = ?, '
                . '"EmpTel" = ?, "EmpCor" = ? , "EmpNomRp" = ? , "EmpDniRp" = ?, "EmpFchAc" = ? WHERE "EmpRuc" = ? ;'
                , array($ruc, $razSocial, $direccion , $tel , $email , $repLegalNomb , $repLegalDni , date("Y-m-d H:i:s"), $RucEmpresa));
        return $query;
    }

    
    public function delete_empresa($idEmpresa) 
    {
        $query = $this->db->query('UPDATE "Empresa" SET "EmpEli" = TRUE, "EmpFchAc" = ? '
                . 'WHERE "EmpId" = ?', array(date("Y-m-d H:i:s"),$idEmpresa));
        return $query;
    }

    public function get_empresas_x_contratista($idContratista) 
    {
        $query = $this->db->query('SELECT * FROM "Detalle_contratista" JOIN "Empresa" ON "DcoEmpId" = "EmpId" '
                . 'WHERE "DcoCstId" = ? AND "EmpEli" = FALSE AND "DcoEli" = FALSE', array($idContratista));
        return $query->result_array();
    }
    
    public function get_empresa_contratante() 
    {
        $query = $this->db->query('SELECT * FROM "Contratante" JOIN "Empresa" ON "CntEmpId" = "EmpId" WHERE "EmpEli" = FALSE AND "CntEli" = FALSE');
        return $query->result_array();
    }
    
    public function get_empresa_x_contratante($idContratante) 
    {
        $query = $this->db->query('SELECT * FROM "Contratante" JOIN "Empresa" ON "CntEmpId" = "EmpId" WHERE "EmpEli" = FALSE AND "CntEli" = FALSE AND "CntId" = ?',array($idContratante));
        return $query->result_array();
    }
    
    public function get_empresas_libres() 
    {
        $query = $this->db->query('SELECT * FROM "Empresa" '
                . 'WHERE NOT "EmpId" IN (SELECT "DcoEmpId" FROM "Detalle_contratista" WHERE "DcoEli" = FALSE) AND '
                . 'NOT "EmpId" IN (SELECT "CntEmpId" FROM "Contratante" WHERE "CntEli" = FALSE) AND '
                . 'NOT "EmpId" IN (SELECT "DcoCstId" FROM "Detalle_contratista" WHERE "DcoEli" = FALSE) AND '
                . 'NOT "EmpRuc" IN (SELECT "CstRuc" FROM "Contratista" WHERE "CstEli" = FALSE);');
        return $query->result_array();
    }
    

    /*"EmpRuc","EmpRaz","EmpRepNo","CntLog1","CntLog2"
    public function get_empresas_x_contratista($idContratista,$CodtipoContratista)
    {
        // 1 = INDIVIDUAL , 0 = CONSORCIO 
        if($CodtipoContratista==0)
        {
            $query = $this->db->query('SELECT "Empresa".* FROM "Empresa" JOIN "Detalle_contratista" ON "EmpId" = "DcoEmpId"'.
        'JOIN "Contratista" ON "DcoCstId" = "CstId" WHERE "CstId" = ?  AND "CstEli" = FALSE;', array($idContratista));
        }
        else
        {
            $query = $this->db->query('SELECT * FROM "Contratista"');
        }
        return $query->result_array();

    }
    */


    
}
 