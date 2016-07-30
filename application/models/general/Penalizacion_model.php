<?php
class Penalizacion_model extends CI_Model 
{
    function __construct() 
    {
        parent::__construct();
        $this->load->model('sistema/Variable_model');
    }

    public function get_one_penalizacion($idPenalizacion) 
    {
        $query = $this->db->query('SELECT * FROM "Penalizacion" WHERE "PnzId" = ? AND "PnzEli" = FALSE;', array($idPenalizacion));
        return $query->row_array();
    }
    
    public function get_one_penalizacion_x_cod($codPenalizacion) 
    {
        $query = $this->db->query('SELECT * FROM "Penalizacion" WHERE "PnzCod" = ? AND "PnzEli" = FALSE;', array($codPenalizacion));
        return $query->row_array();
    }
   
    //Valorizacion_ctrllr -> valorizacion_orden
    public function get_all_penalizaciones_x_orden($idOrdenTrabajo) 
    {
        $query = $this->db->query('SELECT * FROM "Penalizacion" WHERE "PnzOrtId" = ? AND "PnzEli" = FALSE;', array($idOrdenTrabajo));
        return $query->result_array();
    }

    public function insert_penalizacion( $PnzCod,$PnzDes,$PnzCas,$PnzDia,$Dcp,$PnzOrtId ) 
    {
        $PnzDcpId = $Dcp['DcpId'];
        $DcpVal =  $Dcp['DcpVal'];
        $K = $this->Variable_model->get_one_variable_cod('k');
        $uit = $this->Variable_model->get_one_variable_cod('uit');
        
        $PnzMon = intval($PnzCas)  * intval($PnzDia) * floatval($DcpVal) * floatval($K) * floatval($uit); 

        $query = $this->db->query('INSERT into "Penalizacion" ("PnzDes", "PnzCod", "PnzCas" , "PnzDia" , "PnzMon" , "PnzEli", "PnzFchRg" ,  "PnzFchAc" , "PnzDcpId" , "PnzOrtId" )'
                . ' VALUES (?,?,?,?,?,?,?,?,?,?);'
                , array($PnzDes , $PnzCod , $PnzCas  , $PnzDia , $PnzMon ,  false , date("Y-m-d H:i:s") , date("Y-m-d H:i:s")  ,  $PnzDcpId , $PnzOrtId ));
        return $query;
    }
    
    public function update_penalizacion($PnzCod,$PnzDes,$PnzCas,$PnzDia,$Dcp,$idPenalizacion)
    {
        $DcpVal =  $Dcp['DcpVal'];
        $varK = $this->Variable_model->get_one_variable_cod('k');
        $K = $varK['VarVal'];
        $varUit = $this->Variable_model->get_one_variable_cod('uit');
        $uit = $varUit['VarVal'];
  
        $PnzMon = intval($PnzCas)  * intval($PnzDia) * floatval($DcpVal) * floatval($K) * floatval($uit); 
    
        $query = $this->db->query('UPDATE "Penalizacion" SET "PnzDes" = ?, "PnzCod" = ? , "PnzCas" = ? , "PnzDia" = ? , "PnzMon" = ? , "PnzFchAc" = ? WHERE "PnzId" = ?;'
                , array($PnzDes,$PnzCod,$PnzCas,$PnzDia, $PnzMon , date("Y-m-d H:i:s"), $idPenalizacion ));
        return $query;
    }
    
    public function delete_penalizacion($idPenalizacion)
    {
        $query = $this->db->query('UPDATE "Penalizacion" SET "PnzEli" = TRUE, "PnzFchAc" = ? '
                . 'WHERE "PnzId" = ?', array(date("Y-m-d H:i:s") , $idPenalizacion ));
        return $query;
    }
    
    
    // obsoletos
    /*      METODOS PARA CALCULAR EL DESCUENTO Y MONTO BRUTO    */
 
    public function get_descuento_x_idOrden($idOrdenTrabajo)
    {
        $query = $this->db->query('SELECT SUM("PnzMon") as "Descuento" '
                . 'FROM "Penalizacion" WHERE "PnzOrtId" = ?;', array( $idOrdenTrabajo ));
        return $query->row_array();
    }
    
    public function get_montoBruto_x_subactividad_x_idOrden($idOrdenTrabajo)
    {
        $query = $this->db->query('SELECT "Liquidacion_Orden"."LioEj","Detalle_contrato_Subactividad"."DcsPreUn", '
                . '("Liquidacion_Orden"."LioEj"*"Detalle_contrato_Subactividad"."DcsPreUn") as "MonBru" FROM "Liquidacion_Orden" '
                . 'JOIN "Subactividad" ON "LioSacId" = "SacId" '
                . 'JOIN "Detalle_contrato_Subactividad" ON "DcsSacId" = "SacId" '
                . 'WHERE "LioOrtId" = ?;', array( $idOrdenTrabajo ));
        return $query->result_array();
    }
    
    public function get_datos_penalizacion($idPenalizacion)
    {
        $query = $this->db->query('SELECT "PnzId", "GpeId", "GpeDes", "DcpId", "DcpVal", "PenDes"  FROM "Penalizacion" 
            JOIN "Detalle_contrato_Penalidad" ON "PnzDcpId" = "DcpId" 
            JOIN "Penalidad" ON "DcpPenId" = "PenId" 
            JOIN "Grupo_penalidad" ON "PenGpeId" = "GpeId" 
            WHERE "PnzId" = ? AND "PnzEli" = FALSE;', array( $idPenalizacion ));
        return $query->row_array();
    }
    
}