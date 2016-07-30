<?php
class Contrato_model extends CI_Model 
{
    function __construct() 
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('general/Contratista_model');
        $this->load->model('general/Contratante_model');
    }

    public function get_one_contrato($idContrato) 
    {
        $query = $this->db->query('SELECT * FROM "Contrato" WHERE "ConId" = ? AND "ConEli" = FALSE', array($idContrato));
        return $query->row_array();
    }
   
    public function get_one_contrato_x_num($numContrato) 
    {
        $query = $this->db->query('SELECT * FROM "Contrato" '. 'WHERE "ConNum" = ? AND "ConEli" = FALSE;', array($numContrato));
        return $query->row_array();
    }
    
    public function get_all_contrato() 
    {
        $query = $this->db->query('SELECT * FROM "Contrato" WHERE "ConEli" = FALSE ORDER BY "ConNum" ASC;');
        return $query->result_array();
    }
    
    public function get_all_contrato_contratista()
    {
       $query = $this->db->query('SELECT * FROM "Contrato" JOIN "Contratista" ON "ConCstId" = "CstId" WHERE "ConEli" = FALSE;');
       return $query->result_array();
    }
    
    public function get_all_contrato_x_contratante($idContratante)
    {
       $query = $this->db->query('SELECT * FROM "Contrato" JOIN "Contratista" ON "ConCstId" = "CstId" WHERE "ConCntId" = ? AND "ConEli" = FALSE ORDER BY "ConFchIn" ASC;', array($idContratante));
       return $query->result_array();
    }
    
    public function get_contrato_vigente_x_actividad($idActividad, $idContratante) 
    {
        $contratosVigentes = $this->get_contratos_x_contratante($idContratante);
        foreach ($contratosVigentes as $contratoVigente) 
        {
            $actividadesContratadas = $this->get_actividades_x_contrato_id($contratoVigente['ConId']);
            $idActividadesContratadas = array_column($actividadesContratadas, 'ActId');
            /*      VALIDAR CONTRATO VIGENTE        */
            if (in_array($idActividad, $idActividadesContratadas) && $contratoVigente['ConVig'] == true) {
                $idContrato = $contratoVigente['ConId'];
                return $idContrato;
            } 
        }
        return FALSE;
    }

    public function insert_contrato($ConNum,$ConDes, $ConFchIn, $ConFchFn ,$ConCntId , $ConCstId , $ContratoSubActividades)
    {
        $idContrato = false;
        $this->db->trans_start();
        $query = $this->db->query('INSERT into "Contrato" ("ConNum", "ConDes", "ConFchIn" , "ConFchFn" , "ConVig" , "ConEli" , "ConFchRg" , "ConFchAc" ,  "ConCstId" , "ConCntId") '
                . ' VALUES (?,?,?,?,?,?,?,?,?,?);', array($ConNum, $ConDes, $ConFchIn, $ConFchFn , true , false ,  date("Y-m-d H:i:s"), date("Y-m-d H:i:s") , $ConCstId , $ConCntId));
        $idContrato = $this->db->insert_id();
        foreach ($ContratoSubActividades as $ContratoSubActividad) 
        {
            $idSubActividad = $ContratoSubActividad['idSubActividad'];
            $metrado = $ContratoSubActividad['metrado'];
            $precioUnitario = $ContratoSubActividad['precioUnitario'];
            $rendimiento = $ContratoSubActividad['rendimiento'];
            
            $query2 = $this->db->query('INSERT into "Detalle_contrato_Subactividad" ("DcsMet", "DcsPreUn" , "DcsRen" , "DcsEli" , "DcsFchRg" , "DcsFchAc" ,  "DcsConId" , "DcsSacId") '
                . ' VALUES (?,?,?,?,?,?,?,?);', array($metrado, $precioUnitario, $rendimiento , false ,  date("Y-m-d H:i:s"), date("Y-m-d H:i:s") , $idContrato , $idSubActividad));
            $idDetalleContrato = $this->db->insert_id();
        }
        $this->db->trans_complete();
        
        return $idContrato;
    }
    
    public function update_contrato($ConNum,$ConDes, $ConFchIn, $ConFchFn , $ConVig , $ConCntId , $ConCstId , $ContratoSubActividades , $idContrato)
    {
        $ConVig=($ConVig=="vigente");
        $this->db->trans_start();
        
        $query = $this->db->query('UPDATE "Contrato" SET "ConNum" = ?, "ConDes" = ?, "ConFchIn" = ?, "ConFchFn" = ?, "ConVig" = ?, "ConFchAc" = ?, "ConCstId" = ?, "ConCntId" = ? WHERE "ConId" = ?;'
               , array($ConNum, $ConDes, $ConFchIn, $ConFchFn , $ConVig ,  date("Y-m-d H:i:s"),  $ConCstId , $ConCntId , $idContrato));
  
        foreach($ContratoSubActividades as $ContratoSubActividad) 
        {
            $idSubActividad = $ContratoSubActividad['idSubActividad'];
            $metrado = $ContratoSubActividad['metrado'];
            $precioUnitario = $ContratoSubActividad['precioUnitario'];
            $rendimiento = $ContratoSubActividad['rendimiento'];
            
            $query2 = $this->db->query('UPDATE "Detalle_contrato_Subactividad" SET "DcsMet" = ?, "DcsPreUn" = ?, "DcsRen" = ?, "DcsFchAc" = ? WHERE "DcsConId" = ? AND "DcsSacId" = ?;'
                    , array($metrado, $precioUnitario, $rendimiento , date("Y-m-d H:i:s") , $idContrato , $idSubActividad ));
        }
        $this->db->trans_complete();
        return $query2;
    }

    public function delete_contrato($idContrato) 
    {
        $query = $this->db->query('UPDATE "Contrato" SET "ConEli" = TRUE, "ConFchAc" = ? '
                . 'WHERE "ConId" = ?', array(date("Y-m-d H:i:s"),$idContrato));
        return $query;
    }

    public function get_contratos_x_contratista($idContratista)
    {
        $query = $this->db->query('SELECT * FROM "Contrato" WHERE "ConCstId" = ? AND "ConEli" = FALSE ORDER BY "ConNum" ASC', array($idContratista));
        return $query->result_array();
    }
    
    public function autoCompleteContratista($q)
    {
        $consulta = sprintf("SELECT * FROM \"Contratista\" WHERE \"CstRaz\" ILIKE '%%$q%%';");
        $query = $this->db->query($consulta);
        if($query->num_rows >= 0)
        {
            foreach ($query->result_array() as $row)
            {
                $row_set[] = array('label'=>$row['CstRaz'].' | Ruc : '.$row['CstRuc'],'id'=>$row['CstId'],'razSocial'=>$row['CstRaz'],'ruc'=>$row['CstRuc'],'dir'=>$row['CstDir'],'tel'=>$row['CstTel1'],'email'=>$row['CstCor1'],'NomRp'=>$row['CstNomRp']);
            }
            echo json_encode($row_set);
        }
    }
    
    public function get_detalle_contrato_x_id($idContrato)
    {
        $query = $this->db->query('SELECT "ActDes","ActId","SacId","SacDes","UniDes","DcsMet","DcsPreUn","DcsRen" '
                . 'FROM "Detalle_contrato_Subactividad" '
                . 'JOIN "Subactividad" on "DcsSacId"="SacId" '
                . 'JOIN "Actividad" on "SacActId" = "ActId" '
                . 'JOIN "Unidad_medida" on "SacUniId" = "UniId" '
                . 'WHERE "DcsConId" = ? AND "DcsEli" = FALSE ORDER BY "ActId";', array($idContrato));
        return $query->result_array();
    }
    
    public function get_actividades_x_contrato_id($idContrato)
    {
        $query = $this->db->query('SELECT DISTINCT "ActId","ActDes", "ActCod" '
                . 'FROM "Detalle_contrato_Subactividad" '
                . 'JOIN "Subactividad" on "DcsSacId"="SacId" '
                . 'JOIN "Actividad" on "SacActId" = "ActId" '
                . 'JOIN "Unidad_medida" on "SacUniId" = "UniId" '
                . 'WHERE "DcsConId" = ? AND "DcsEli" = FALSE ORDER BY "ActId";', array($idContrato));
        return $query->result_array();
    }
    
    public function get_contratos_x_contratante($idContratante)
    {
        $query = $this->db->query('SELECT * FROM "Contrato" WHERE "ConVig" = TRUE AND "ConCntId" = ?;', array($idContratante));
        return $query->result_array();
    }
    
    public function get_rendimientos_x_contrato_x_actividad($idContrato,$idActividad)
    {
        $query = $this->db->query('SELECT "DcsRen" FROM "Detalle_contrato_Subactividad" '
                . 'JOIN "Subactividad" on "DcsSacId"="SacId" '
                . 'JOIN "Actividad" on "SacActId" = "ActId" '
                . 'JOIN "Unidad_medida" on "SacUniId" = "UniId" '
                . 'WHERE "DcsConId" = ? AND "ActId" = ? AND "DcsEli" = FALSE ORDER BY "ActId";', array($idContrato,$idActividad));
        return $query->result_array();
    }
    
    //valorizacion_model -> insertar_valorizacion
    public function get_precio_x_subactividad($idContrato,$idSubActividad)
    {
        $query = $this->db->query('SELECT "DcsPreUn" FROM "Detalle_contrato_Subactividad" '
                . 'WHERE "DcsConId" = ? AND "DcsSacId" = ? AND "DcsEli" = FALSE', array($idContrato,$idSubActividad));
        return $query->row_array()['DcsPreUn'];
    }
    
    
    /*      PRECIOS PENALIDADES  POR CONTRATO   */
    
    public function get_one_detalle_contrato_penalidad($DcpId) 
    {
        $query = $this->db->query('SELECT * FROM "Detalle_contrato_Penalidad" WHERE "DcpId" = ? AND "DcpEli" = FALSE', array($DcpId));
        return $query->row_array();
    }
    
    public function get_all_detalle_contrato_penalidad()
    {
        $query = $this->db->query('SELECT * FROM "Detalle_contrato_Penalidad" '
                . 'JOIN "Penalidad" ON "DcpPenId" = "PenId" '
                . 'JOIN "Grupo_penalidad" ON "PenGpeId" = "GpeId" '
                . 'JOIN "Contrato" ON "DcpConId" = "ConId" WHERE "DcpEli" = FALSE;');
        return $query->result_array();
    }
    
    public function get_all_grupo_penalidad_x_contrato($idContrato)
    {
        $query = $this->db->query('SELECT DISTINCT "Grupo_penalidad".* FROM "Detalle_contrato_Penalidad" '
                . 'JOIN "Penalidad" ON "DcpPenId" = "PenId" '
                . 'JOIN "Grupo_penalidad" ON "PenGpeId" = "GpeId" '
                . 'JOIN "Contrato" ON "DcpConId" = "ConId" WHERE "DcpEli" = FALSE AND "DcpConId" = ? ORDER BY "GpeId" ASC;', array($idContrato));
        return $query->result_array();
    }
    
    
    public function get_detalle_contrato_penalidad($idContrato)
    {
        $query = $this->db->query('SELECT * FROM "Detalle_contrato_Penalidad" '
                . 'JOIN "Penalidad" ON "DcpPenId" = "PenId" '
                . 'JOIN "Grupo_penalidad" ON "PenGpeId" = "GpeId" '
                . 'JOIN "Contrato" ON "DcpConId" = "ConId" WHERE "ConId" = ?;', array($idContrato));
        return $query->result_array();
    }
    
    public function insert_detalle_contrato_penalidades($DcpConId ,  $ContratoPreciosPenalidades )
    {
        foreach ( $ContratoPreciosPenalidades as  $ContratoPrecioPenalidad) 
        {
            $idPenalidad = $ContratoPrecioPenalidad['idPenalidad'];
            $multa = $ContratoPrecioPenalidad['multa'];
            $query = $this->db->query('INSERT into "Detalle_contrato_Penalidad" ("DcpVal", "DcpEli" , "DcpFchRg" , "DcpFchAc" , "DcpConId" , "DcpPenId" ) '
                . ' VALUES (?,?,?,?,?,?);', array($multa, false ,  date("Y-m-d H:i:s"), date("Y-m-d H:i:s") , $DcpConId , $idPenalidad));
        }
        return $query;
    }
    
    public function get_one_detalle_contrato_penalidad_precio( $idContrato , $idPrecioPenalidad)
    {
        $query = $this->db->query('SELECT * FROM "Detalle_contrato_Penalidad" '
                . 'JOIN "Penalidad" ON "DcpPenId" = "PenId" '
                . 'JOIN "Grupo_penalidad" ON "PenGpeId" = "GpeId" '
                . 'JOIN "Contrato" ON "DcpConId" = "ConId" WHERE "ConId" = ? AND "DcpId" = ?;', array( $idContrato , $idPrecioPenalidad));
        return $query->row_array();
    }
   
    public function update_precio_penalidad($DcpId , $DcpVal)
    {
        $query = $this->db->query('UPDATE "Detalle_contrato_Penalidad" SET "DcpVal" = ?, "DcpFchAc" = ? WHERE "DcpId" = ?;'
                , array($DcpVal, date("Y-m-d H:i:s"), $DcpId ));
        return $query;
    }
    
    public function delete_precio_penalidad( $DcoConId , $DcpId)
    {
        $query = $this->db->query('UPDATE "Detalle_contrato_Penalidad" SET "DcpEli" = FALSE, "DcpFchAc" = ? WHERE "DcpId" = ? ADN "DcpConId" = ?;'
                , array(date("Y-m-d H:i:s"), $DcpId , $DcoConId));
        return $query;
    }
    
   
    /* HISTORIAL DE LOS CONTRATOS RELACIONADOS A UNA CONTRATISTA 
    public function get_all_contrato_contratista() 
    {
        $contratistas = $this->Contratista_model->get_all_contratista();

        foreach ($contratistas as $key => $contratista) 
        {
            $contratos = $this->get_contratos_x_contratista($contratista['CstId']);

            $contratistas[$key]['contratos'] = $contratos;
        }
        return $contratistas;
    }*/
    
    
}
