<?php

class Contratista_model extends CI_Model 
{

    function __construct() 
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('general/Empresa_model');
    }

    public function get_one_contratista($idContratista) 
    {
        $query = $this->db->query('SELECT * FROM "Contratista" WHERE "CstId" = ? AND "CstEli" = FALSE', array($idContratista));
        return $query->row_array();
    }

    public function get_one_contratista_x_ruc($ruc) 
    {
        $query = $this->db->query('SELECT * FROM "Contratista" WHERE "CstRuc" = ? AND "CstEli" = FALSE', array($ruc));
        return $query->row_array();
    }
    
    public function get_one_contratista_x_contrato($idContrato) 
    {
        $query = $this->db->query('SELECT "Contratista".* FROM "Contratista" JOIN "Contrato" ON "ConCstId" = "CstId" '
                . 'WHERE "ConId" = ? AND "CstEli" = FALSE', array($idContrato));
        return $query->row_array();
    }

    public function get_all_contratista() 
    {
        $query = $this->db->query('SELECT * FROM "Contratista" WHERE "CstEli" = FALSE ORDER BY "CstRaz" ASC', array());
        return $query->result_array();
    }

    public function get_all_contratista_empresa() 
    {
        $contratistas = $this->get_all_contratista();

        foreach ($contratistas as $key => $contratista) 
        {
            $empresas = $this->Empresa_model->get_empresas_x_contratista($contratista['CstId']);

            $contratistas[$key]['empresas']= $empresas;
        }
        return $contratistas;
    }
    
    public function get_contratista_x_contratante($idContratante) 
    {
        $query = $this->db->query('SELECT DISTINCT "Contratista".* FROM "Contratista" JOIN "Contrato" '
                . 'ON "ConCstId" = "CstId" JOIN "Contratante" ON "ConCntId" = "CntId" '
                . 'WHERE "ConEli" = FALSE AND "ConCntId" = ? ORDER BY "CstRaz" ASC;',array($idContratante));
        return $query->result_array();
    }

    public function insert_contratista($CstRuc,$CstRaz, $direccion, $tel11 , $tel12 , $email11 , $email12, $CstDniRp ,$CstNomRp,$CstLog1,$CstLog2,$CstCio, $empresas) 
    {
        $this->db->trans_start();
        $CstCio=($CstCio=="consorcio");
        if ($CstCio!="consorcio") 
        {   /* ES EMPRESA INDIVIDUAL */
            
            if($this->Empresa_model->get_one_id_empresa_x_ruc($CstRuc)!=false)
            {
                $idEmpresaContratista = $this->Empresa_model->get_one_id_empresa_x_ruc($CstRuc);
            }
            else 
            {
                $idEmpresaContratista = $this->Empresa_model->insert_empresa($CstRuc,$CstRaz, $direccion,$tel11 ,$email11 ,$CstNomRp , $CstDniRp);
            }
            
        }
        $query = $this->db->query('INSERT into "Contratista" ("CstRuc", "CstRaz", "CstDir" , "CstTel1" , "CstTel2" , "CstCor1" , "CstCor2" , "CstDniRp" ,  "CstNomRp" , "CstLog1","CstLog2", '
                . '"CstCio", "CstEli" , "CstFchRg" , "CstFchAc") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?); '
                , array($CstRuc,$CstRaz, $direccion, $tel11 , $tel12 , $email11 , $email12, $CstDniRp ,$CstNomRp,$CstLog1,$CstLog2,$CstCio , false ,  date("Y-m-d H:i:s"), 
                   date("Y-m-d H:i:s") ));
        $idContratista = $this->db->insert_id();
        
        if($CstCio!="consorcio") 
        {
            $query = $this->db->query('INSERT into "Detalle_contratista" ("DcoPrt" , "DcoEli" ,"DcoFchRg", "DcoFchAc", "DcoEmpId", "DcoCstId") VALUES (?,?,?,?,?,?); '
                , array(100, false , date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idEmpresaContratista , $idContratista));
        }
        
        foreach ($empresas as $empresa)      
        {
            if($this->Empresa_model->get_one_id_empresa_x_ruc($empresa['ruc'])!=false)
            {
                $idEmpresa = $this->Empresa_model->get_one_id_empresa_x_ruc($empresa['ruc']);
            }
            else 
            {
                $idEmpresa = $this->Empresa_model->insert_empresa($empresa['ruc'],$empresa['razSocial'], $empresa['direccion'],$empresa['tel'] ,$empresa['email'],$empresa['repLegalNomb'],$empresa['repLegalDni']);
            }
            $query = $this->db->query('INSERT into "Detalle_contratista" ("DcoPrt" , "DcoEli" ,"DcoFchRg", "DcoFchAc", "DcoEmpId", "DcoCstId") VALUES (?,?,?,?,?,?); '
                , array($empresa['porcentaje'], false , date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idEmpresa , $idContratista));
        }
        $this->db->trans_complete();

        return $query;
    }

    public function update_contratista($CstRuc,$CstRaz, $direccion, $tel11 , $tel12 , $email11 , $email12, $CstDniRp ,$CstNomRp,$CstLog1,$CstLog2,$CstCio, $empresas, $idContratista) 
    {
        $this->db->trans_start();
        $CstCio=($CstCio=="consorcio");
        if ($CstCio!="consorcio") 
        {   /* ES EMPRESA INDIVIDUAL */
            /*  ACTUALIZA LOS DATOS DE LA CONTRATISTA EN EMPRESA, CUANDO ES INDIVIDUAL*/
            $this->Empresa_model->update_empresa_x_ruc($CstRuc, $CstRaz, $direccion , $tel11 , $email11 , $CstNomRp , $CstDniRp, $CstRuc);
        }
        /* ACTUALIZA SOLO LOS DATOS DE LA CONTRATISTA */
        $this->db->query('UPDATE "Contratista" SET "CstRuc" = ? , "CstRaz" = ?, "CstDir" = ?, "CstTel1" = ?, "CstTel2" = ?, "CstCor1" = ?, "CstCor2" = ?, "CstNomRp" = ?, "CstDniRp" = ? , "CstLog1" = ? , "CstLog2" = ? ,"CstCio" = ? , "CstFchAc" = ?, "CstUsrId" = ? , "CstIpAdd" = ? , "CstHosNm" = ? WHERE "CstId" = ?'
                , array($CstRuc,$CstRaz, $direccion, $tel11 , $tel12 , $email11 , $email12, $CstNomRp , $CstDniRp ,$CstLog1,$CstLog2, $CstCio , date("Y-m-d H:i:s"), $_SESSION['usuario_id'] , $_SERVER['REMOTE_ADDR'] , gethostbyaddr($_SERVER['REMOTE_ADDR']) ,  $idContratista ));
        
        /* SI ES UN CONSORCIO */
        foreach ($empresas as $empresa)      
        {
            /* ACTUALIZA LOS DATOS DEL CONSORCIADO EN LA CONTRATISTA */
            $this->Empresa_model->update_empresa_x_ruc($empresa['ruc'],$empresa['razSocial'], $empresa['direccion'],$empresa['tel'] ,$empresa['email'],$empresa['repLegalNomb'],$empresa['repLegalDni'], $empresa['ruc']);
            /* ACTUALIZA EL PORCENTAJE DE PARTICIPACION DEL CONSORCIADO EN LA CONTRATISTA */
            $query = $this->db->query('UPDATE "Detalle_contratista" SET "DcoPrt" = ?, "DcoEli" = ?, "DcoFchAc" = ? WHERE "DcoCstId" = ?', array($empresa['porcentaje'], false , date("Y-m-d H:i:s"), $idContratista));
        }
        $this->db->trans_complete();
        return $query;
    }
    
    public function delete_contratista($idContratista) 
    {
        $query = $this->db->query('UPDATE "Contratista" SET "CstEli" = TRUE, "CstFchAc" = ? '
                . 'WHERE "CstId" = ?', array(date("Y-m-d H:i:s"),$idContratista));
        if ($query) 
        {
            $query2 = $this->delete_all_empresas_x_contratista($idContratista);
        }
        return $query2;
    }

    public function delete_all_empresas_x_contratista($idContratista) 
    {
        $query = $this->db->query('UPDATE "Detalle_contratista" SET "DcoEli" = TRUE WHERE "DcoCstId" = ?', array($idContratista));
        return $query;
    }
    
    
    public function delete_consorciado($idContratista , $idConsorciado)
    {
        $query = $this->db->query('UPDATE "Detalle_contratista" SET "DcoEli" = TRUE WHERE "DcoCstId" = ? AND "DcoEmpId" = ?', array($idContratista,$idConsorciado));
        return $query;
    }
    
    
}
