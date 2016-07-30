<?php

class OTDistribucion_cst_model extends CI_Model {

    function __construct() 
    {
        parent::__construct();
        $this->load->model('general/Periodo_model');
        $this->load->model('sistema/Parametro_model');
    }

    
    //OTDistribucion_cst_ctrllr -> orden_ver()
    public function get_one_orden_trabajo_all_datos($idOrdenTrabajo) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" '
                . 'JOIN "Actividad" ON "OrtActId" = "ActId" '
                . 'JOIN "Contrato" ON "OrtConId" = "ConId" '
                . 'JOIN "Contratante" ON "ConCntId" = "CntId" '
                . 'WHERE "OrtId" = ? AND "OrtEli" = FALSE', array($idOrdenTrabajo));
        $orden = $query->row_array();
        //AGREGAR LOS CICLOS
        $query2 = $this->db->query('SELECT DISTINCT "GprCod" FROM "Suborden_distribucion" JOIN "Grupo_predio" ON "SodGprId" = "GprId" WHERE "SodOrtId" = ? ', array($orden['OrtId']));
        $ciclos = $query2->result_array();
        $orden['ciclos'] = implode(', ', array_column($ciclos, 'GprCod'));
        //agregar el periodo
        $orden['periodo'] = $this->Periodo_model->get_one_periodo($orden['OrtPrdId']);
        
        //AGREGAR LA CANTIDAD
        $cantidad = $this->Distribucion_model->get_cant_dist_x_orden($orden['OrtId']);
        $ordenes['cantidad'] = $cantidad;
        
        //AGREGAR AVANCE
        $validos = $this->Distribucion_model->get_cant_dist_ejec_x_orden($orden['OrtId']);
        $avance = 0;
        if ($cantidad != 0) 
            {
                $avance = round($validos * 100 / $cantidad, 2);
            }
        $ordenes['avance'] = $avance;
        
        return $orden;
    }
    
    
    public function get_one_orden_trabajo($idOrdenTrabajo) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" WHERE "OrtId" = ? AND "OrtEli" = FALSE;', array($idOrdenTrabajo));
        return $query->row_array();
    }
    
    
    //ConsultasMovil_cst_ctrllr -> obtener_distribucion_en_ejec_x_lecturista_json() 
    public function get_one_suborden_trabajo_all_datos_movil($OrtFchEj, $LecLtaId) {
        $query = $this->db->query('SELECT * FROM "Suborden_distribucion" 
        WHERE "SodFchEj" = ? AND "SodDbrId" = ? AND "SodEli" = FALSE', array($OrtFchEj, $LecLtaId));
        return $query->row_array();
    }
   

    public function get_ciclo_orden_trabajo($idOrdenTrabajo) {
        $query = $this->db->query('SELECT "Grupo_predio"."GprCod","SodGprId" FROM "Suborden_distribucion"
        JOIN "Grupo_predio" ON "SodGprId" = "GprId"
        WHERE "SodOrtId" = ? GROUP By "SodGprId","Grupo_predio"."GprCod"', array($idOrdenTrabajo));
        return $query->row_array();
    }

    public function get_all_orden_trabajo_x_contratista($idContratista) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" JOIN "Contrato" ON "OrtConId" = "ConId" '
                . 'JOIN "Contratista" ON "ConCstId" = "CstId" '
                . 'WHERE "CstId" = ? AND "OrtEli" = FALSE ORDER BY "OrtNum" ASC;', array($idContratista));
        return $query->result_array();
    }
    
    //OrdenTrabajo_ctrllr -> orden_listar()
    public function get_subactividad_x_orden($idActividad) {
        $query = $this->db->query('SELECT "SacDes", "OrtId" FROM "Subactividad" JOIN "Orden_trabajo_Subactividad" ON "SacId" = "OtsSacId" 
        JOIN "Orden_trabajo" ON "OtsOrtId" = "OrtId" 
        WHERE "OrtActId" = ? AND "SacEli" = FALSE ORDER BY "OrtNum" ASC;', array($idActividad));
        return $query->result_array();
    }
    
    // OTDistribucion_cst_ctrllr -> orden_ver($idOrdenTrabajo)
    public function update_one_subordenDistribucion_id( $SodDbrId , $SodId ) {
        $query = $this->db->query('UPDATE "Suborden_distribucion" SET "SodDbrId" = ? , "SodFchAc" = ? WHERE "SodId" = ?'
                , array($SodDbrId,date("Y-m-d H:i:s"),$SodId));
        return $query;
    }
    
    
    
    
    public function update_usr_rs_orden_trabajo($idOrdenTrabajo, $OrtUsrRs) {
        $query = $this->db->query('UPDATE "Orden_trabajo" SET "OrtFchAc" = ? , "OrtUsrRs" = ? WHERE "OrtId" = ?;'
                , array(date("Y-m-d H:i:s"), $OrtUsrRs, $idOrdenTrabajo));
        return $query;
    }
    public function update_estado_orden_trabajo($idOrdenTrabajo, $OrtEstId) {
        $query = $this->db->query('UPDATE "Orden_trabajo" SET "OrtFchRs" = ? , "OrtFchAc" = ? , "OrtEstId" = ? WHERE "OrtId" = ?;'
                , array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $OrtEstId, $idOrdenTrabajo));
        return $query;
    }
    public function update_orden_trabajo($archivos, $idOrdenTrabajo) {
        $this->db->trans_start();
        $query = $this->db->query('UPDATE "Orden_trabajo" SET "OrtFchAc" = ?, "OrtFchEr" = ? WHERE "OrtId" = ?;'
                , array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idOrdenTrabajo));
        $retorno = $this->save_files_info($archivos, $idOrdenTrabajo);
        if ($retorno) {
            $this->db->trans_complete();
        }
        return $query;
    }
    public function delete_orden($idOrdenTrabajo) {
        $query = $this->db->query('UPDATE "Orden_trabajo" SET "OrtEli" = TRUE, "OrtFchAc" = ? '
                . 'WHERE "OrtId" = ?', array(date("Y-m-d H:i:s"), $idOrdenTrabajo));
        return $query;
    }
    
    public function get_cantSubords_por_ordenTrabajo_porDia($idOrdenTrabajo) {
        $idSubCntrlRegular = $this->Parametro_model->get_one_detParam_x_codigo('sol_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT(*) AS cantidad ,"SodFchEj" FROM "Suborden_distribucion"
        WHERE "SodEli" = FALSE AND "SodTipId" = ? AND "SodOrtId" = ? GROUP BY "SodFchEj" ORDER BY "SodFchEj"', array($idSubCntrlRegular,$idOrdenTrabajo));
        return $query->result_array();
    }
    
    //OTTomaEstado_cst_ctrllr->orden_ver($idOrdenTrabajo)
    public function get_cantLec_por_suborden_por_ordenTrabajo_porDia($idOrdenTrabajo) {
        $idSubCntrlRegular = $this->Parametro_model->get_one_detParam_x_codigo('sol_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT("Distribucion".*) AS cantidad,"SodFchEj" FROM "Distribucion"
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId"
        WHERE "SodEli" = FALSE AND "SodTipId" = ? AND "SodOrtId" = ? GROUP BY "SodFchEj" ORDER BY "SodFchEj"', array($idSubCntrlRegular,$idOrdenTrabajo));
        return $query->result_array();
    }
    
    //OTTomaEstado_cst_ctrllr->orden_nuevo_detalle_ciclo($idOrdenTrabajo,$fechaEj)
    public function get_cantLec_por_suborden_por_ordenTrbj_porDia($idOrdenTrabajo,$fecha) {
        $idSubCntrlRegular = $this->Parametro_model->get_one_detParam_x_codigo('sol_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT("Distribucion".*) AS cantidad FROM "Distribucion"
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId"
        WHERE "SodEli" = FALSE AND "SodTipId" = ? AND "SodOrtId" = ? AND "SodFchEj" = ?;', array($idSubCntrlRegular,$idOrdenTrabajo,$fecha));
        return $query->row_array()['cantidad'];
    }
    public function get_cantSubords_por_ordenTrbj_porDia($idOrdenTrabajo,$fecha) {
        $idSubCntrlRegular = $this->Parametro_model->get_one_detParam_x_codigo('sol_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT(*) AS cantidad FROM "Suborden_distribucion"
        WHERE "SodEli" = FALSE AND "SodTipId" = ? AND "SodOrtId" = ? AND "SodFchEj" = ?', array($idSubCntrlRegular,$idOrdenTrabajo,$fecha));
        return $query->row_array()['cantidad'];
    }

    //OTTomaEstado_cst_ctrllr->orden_ver()
    public function get_cantidad_subordenes_x_orden($idOrdenTrabajo)
    {
        $idSubCntrlRegular = $this->Parametro_model->get_one_detParam_x_codigo('sol_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT(*) AS cantidad FROM "Suborden_distribucion"
        WHERE "SodEli" = FALSE AND "SodTipId" = ? AND "SodOrtId" = ?', array($idSubCntrlRegular,$idOrdenTrabajo));
        return $query->row_array()['cantidad'];
    }
    
}
