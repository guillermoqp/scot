<?php

class Distribucion_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('catastro/Catastro_model');
        $this->load->model('distribucion/Distribuidor_model');
        $this->load->model('sistema/Parametro_model');
        $this->load->library('utilitario_cls');
    }
    
    
    //OTDistribucion_ctrllr -> orden_penalizar
    public function get_one_distribucion($DbnId) {
        $query = $this->db->query('SELECT "Distribucion".*,"SodFchEj" FROM "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId"
        WHERE "DbnEli" = FALSE AND "DbnId" = ?', array($DbnId));
        return $query->row_array();
    }
    
    //distribucion/Orden_avance_ctrllr -> avance_suborden($idOrden, $codSubOrden)
    public function get_one_suborden_x_orden($idOrden, $codSubOrden) {
        $query = $this->db->query('SELECT * FROM "Suborden_distribucion" WHERE "SodEli" = FALSE AND "SodCod" = ? AND "SodOrtId" = ?', array($codSubOrden , $idOrden ));
        return $query->row_array();
    }
    
    //distribucion/Orden_avance_ctrllr -> avance_suborden($idOrden, $codSubOrden)
    public function get_one_suborden($SodId) {
        $query = $this->db->query('SELECT * FROM "Suborden_distribucion" WHERE "SodEli" = FALSE AND "SodId" = ?', array($SodId));
        return $query->row_array();
    }
    
    
    
    
    
    //OTDistribucion_model -> get_all_orden_trabajo_x_contratante($idContratante, $idActividad)
    public function get_cant_dist_x_orden_x_tipo($idOrden,$idTipo) {
        $query = $this->db->query('SELECT COUNT("DbnId") as "cantidad" FROM "Distribucion" JOIN "Suborden_distribucion" '
                . 'ON "DbnSodId" = "SodId" WHERE "SodOrtId" = ? AND "DbnTipId" = ?', array($idOrden,$idTipo )  );
        return $query->row_array()['cantidad'];
    }    
    
    //OTDistribucion_model -> get_all_orden_trabajo_x_contratante
    //OTDistribucion_mode -> get_all_orden_x_valorizacion
    public function get_cant_dist_ejec_x_orden($idOrden) {
        $query = $this->db->query('SELECT COUNT("DbnId") as "cantidad" FROM "Distribucion" JOIN "Suborden_distribucion" '
                . 'ON "DbnSodId" = "SodId" WHERE "SodOrtId" = ? AND '
                . '"DbnVld" = TRUE', array($idOrden));
        return $query->row_array()['cantidad'];
    }

    //OTDistribucion_model -> get_all_orden_trabajo_x_contratante
    //OTDistribucion_model -> get_all_orden_x_valorizacion
    public function get_cant_dist_x_orden($idOrden) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT("DbnId") as "cantidad" FROM "Distribucion" JOIN "Suborden_distribucion" '
                . 'ON "DbnSodId" = "SodId"  WHERE "SodOrtId" = ? AND "DbnTipId" = ?', array($idOrden, $DbnTipId));
        return $query->row_array()['cantidad'];
    }
    
    //OTDistribucion_cst_ctrllr -> orden_ver()
    public function get_detalle_distribuciones_sin_asignar_distribuidores($idOrden,$SolFchEj) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT "Suborden_distribucion".*,COUNT(*) AS "suministros"
        FROM "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId"
        JOIN "Grupo_predio" ON "DbnGprId" = "GprId"
        WHERE "SodOrtId" = ? AND "DbnEli" = FALSE AND "DbnTipId" = ? AND "SodFchEj" = ?
        GROUP BY "SodId","SodDbrId","SodCod" ORDER BY "SodCod"::integer ASC', array($idOrden, $DbnTipId , $SolFchEj));
        return $query->result_array();
    }
    
    //OTDistribucion_cst_ctrllr ->get_detalle_x_suborden_json()
    public function get_distribuciones_x_idsuborden($idSubOrden) {
        $query = $this->db->query('SELECT "DbnCodFc","DbnNom","DbnUrb","DbnCal","DbnMun","DbnMed" FROM "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId" 
        WHERE "SodId" = ? AND "DbnEli" = FALSE ORDER BY "DbnId"', array($idSubOrden));
        return $query->result_array();
    }
    
    
    public function get_desc_ciclo_x_orden($idOrden) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT DISTINCT "GprCod","GprDes"
        FROM "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId"
        JOIN "Grupo_predio" ON "DbnGprId" = "GprId" 
        WHERE "SodOrtId" = ? AND "SodEli" = FALSE AND "DbnTipId" = ? GROUP BY "GprCod","GprDes" ORDER BY "GprDes" ASC', array($idOrden , $DbnTipId ));
        return $query->result_array();
    }

    public function get_cant_suborden_distribucion($idOrden) {
        $query = $this->db->query('SELECT COUNT(DISTINCT "Suborden_distribucion"."SodFchEj") AS cantidad FROM "Suborden_distribucion" WHERE "SodOrtId" = ?', array($idOrden));
        return $query->row_array()['cantidad'];
    }
    
    
    // BEGIN valorizacionDistribucion_model -> insertar_valorizacion
    public function get_distribucion_regular_x_orden_x_tipo($idOrden, $tipo) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT * FROM "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId"
        WHERE "SodOrtId" = ? AND "DbnSacId" = ? AND "DbnEli" = FALSE AND "DbnVld" = TRUE AND "DbnTipId" = ?', array($idOrden, $tipo, $DbnTipId));
        return $query->result_array();
    }
    public function get_distribucion_control_x_orden_x_tipo($idOrden, $tipo) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_sup')['DprId'];
        $query = $this->db->query('SELECT * FROM "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId"
        WHERE "SodOrtId" = ? AND "DbnSacId" = ? AND "DbnEli" = FALSE AND "DbnVld" = TRUE AND "DbnTipId" = ?', array($idOrden, $tipo, $DbnTipId));
        return $query->result_array();
    }
    public function get_distribucion_dispersa_x_orden_x_tipo($idOrden, $tipo) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_dis')['DprId'];
        $query = $this->db->query('SELECT * FROM "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId"
        WHERE "SodOrtId" = ? AND "DbnSacId" = ? AND "DbnEli" = FALSE AND "DbnVld" = TRUE AND "DbnTipId" = ?', array($idOrden, $tipo, $DbnTipId));
        return $query->result_array();
    }
    //END valorizacionDistribucion_model -> insertar_valorizacion 
    
    
    //OTDistribucion_model -> get_avance_x_suborden($idOrden, $idSubOrden) 
    public function get_distribuciones_x_suborden($idOrden, $subOrden) {
        $query = $this->db->query('SELECT * FROM "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId" 
        WHERE "SodOrtId" = ? AND "SodCod" = ? AND "DbnEli" = FALSE ORDER BY "DbnId"', array($idOrden, $subOrden));
        return $query->result_array();
    }
    
    //OTDistribucion_model -> get_avance_x_suborden
    public function get_obs_x_distribucion($idDistribucion) {
        $query = $this->db->query('SELECT * FROM "Observaciones_Distribucion" JOIN "Observacion" ON "OdiObsId" = "ObsId" '
                . 'AND "OdiDbnId" = ?', array($idDistribucion));
        return $query->result_array();
    }
    
    //OTDistribucion_ctrllr -> insertar_ciclo()
    public function get_cant_distribuciones_restantes_x_grupoPredio($idPeriodo, $idgrupoPredio, $conexionesTotales, $idContratante) {
        
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        
        $query = $this->db->query('SELECT COUNT("DbnId") AS cantidad FROM "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId" 
        JOIN "Orden_trabajo" ON "SodOrtId" = "OrtId" 
        WHERE "OrtPrdId" = ? AND "SodGprId" = ? AND "DbnTipId" = ?', array($idPeriodo, $idgrupoPredio, $DbnTipId));
        $lecturasHechas = $query->row_array()['cantidad'];
        return $conexionesTotales - $lecturasHechas;
    }
    
    
    //OTDistribucion_model -> get_avance_x_orden
    public function get_cant_distribucion_total_x_ciclo_x_orden($idOrden, $cicloCod) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT ("Distribucion".*) as "cantidad" FROM "Orden_trabajo", "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId" 
        JOIN "Grupo_predio" ON "DbnGprId" = "GprId"
        WHERE "SodOrtId" = "OrtId" AND "OrtId" = ? AND "GprCod" = ? AND "DbnTipId" = ?', array($idOrden, $cicloCod, $DbnTipId));
        $row = $query->row_array();
        return $row['cantidad'];
    }
    
    //OTDistribucion_model -> get_avance_x_orden
    public function get_cant_distribucion_ejec_x_ciclo_x_orden($idOrden, $cicloCod) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT ("Distribucion".*) as "cantidad" 
        FROM "Orden_trabajo", "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId" 
        JOIN "Grupo_predio" ON "DbnGprId" = "GprId"
        WHERE "SodOrtId" = "OrtId" AND "OrtId" = ? AND "GprCod" = ? AND "DbnVld" = TRUE AND "DbnTipId" = ?', array($idOrden, $cicloCod , $DbnTipId));
        $row = $query->row_array();
        return $row['cantidad'];
    }
    
    
    //OTTomaEstado_model -> orden_ver()
    public function get_lecturistas_x_ciclo_x_orden($idOrden) {
        $query = $this->db->query('SELECT "GprCod","GprDes",COUNT(DISTINCT "SodDbrId") AS "Lecturistas" 
        FROM "Suborden_distribucion"
        JOIN "Distribucion" ON "DbnSodId" = "SodId"
        JOIN "Grupo_predio" ON "DbnGprId" = "GprId" 
        WHERE "SodOrtId" = ? AND "SodEli" = FALSE 
        GROUP BY "GprCod","GprDes" ORDER BY "GprDes" ASC', array($idOrden));
        return $query->result_array();
    }
    //OTTomaEstado_model -> orden_ver()
    public function get_lecturas_x_suborden($idOrden, $subOrden) {
        $query = $this->db->query('SELECT * FROM "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId" 
        WHERE "SodOrtId" = ? AND "SodCod" = ? AND "DbnEli" = FALSE ORDER BY "LecId"', array($idOrden, $subOrden));
        return $query->result_array();
    }
    //OTTomaEstado_cst_ctrlle -> get_detalle_x_suborden_json()
    public function get_lecturas_x_idsuborden($idSubOrden) {
        $query = $this->db->query('SELECT "LecCodFc","LecNom","LecUrb","LecCal","LecMun","LecMed" FROM "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId" 
        WHERE "SodId" = ? AND "DbnEli" = FALSE ORDER BY "LecId"', array($idSubOrden));
        return $query->result_array();
    }
    //Orden_avance_cst_ctrllr -> orden_avance_suborden_json() 
    public function get_ruta_x_suborden($idOrden, $subOrden) {
        $query = $this->db->query('SELECT * FROM "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId" 
        WHERE "SodOrtId" = ? AND "SodCod" = ? AND "DbnEli" = FALSE AND "LecPosLt" IS NOT NULL AND "LecPosLt" IS NOT NULL ORDER BY "LecId"', array($idOrden, $subOrden ));
        return $query->result_array();
    }
    //OTTomaEstado_model -> get_avance_x_orden
    public function get_ciclos_x_orden($idOrden) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT DISTINCT "GprCod","GprDes"
        FROM "Distribucion" 
        JOIN "Grupo_predio" ON "DbnGprId" = "GprId"
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId"
        WHERE "SodOrtId" = ? AND "DbnTipId" = ? ORDER BY "GprCod" ASC', array(intval($idOrden), $DbnTipId));
        return $query->result_array();
    }
    
    
    //  distribucion/Orden_avance_ctrllr -> avance_orden
    public function get_periodo_fact_x_orden($idOrden) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT "PrdAni", "PrdOrd" FROM "Periodo" 
        JOIN "Orden_trabajo" ON "OrtPrdId" = "PrdId"
        JOIN "Suborden_distribucion" ON "SodOrtId" = "OrtId"
        JOIN "Distribucion" ON "DbnSodId" = "SodId"
        WHERE "SodOrtId" = ? AND "DbnTipId" = ? LIMIT 1', array( $idOrden, $DbnTipId ));
        return $query->row_array();
    }
    
    
    //ConsultasMovil_cst_ctrllr -> obtener_lecturas_en_ejec_x_lecturista_json() 
    public function get_desc_ciclos_x_orden_lecturista($idOrden, $idLecturista) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT DISTINCT "Grupo_predio"."GprCod","Grupo_predio"."GprDes" 
        FROM "Distribucion" 
        JOIN "Grupo_predio" ON "DbnGprId" = "GprId" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId"
        JOIN "Distribuidor" ON "SodDbrId" = "DbrId"
        WHERE "SodOrtId" = ? AND "SodDbrId" = ? AND "DbnTipId" = ?', array($idOrden, $idLecturista, $DbnTipId));
        return $query->result_array();
    }
    
    
    //Reporte_model -> get_ciclos_control
    public function get_cant_distribucion_prog_x_ciclo_x_periodo($idPeriodo, $idContratante, $idgrupoPredio) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT("Distribucion".*) as "Cantidad" FROM "Distribucion" 
        JOIN "Suborden_distribucion" ON "SodId" = "DbnSodId" 
        JOIN "Orden_trabajo" ON "SodOrtId" = "OrtId" 
        WHERE "OrtPrdId" = ? AND "SodGprId" = ? AND "OrtCntId" = ? AND "DbnTipId" = ?', array($idPeriodo, $idgrupoPredio, $idContratante, $DbnTipId ));
        $row = $query->row_array();
        return $row['Cantidad'];
    }
    
    
    //OTDistribucion_model -> insert_orden()
    public function get_distribuciones_x_orden($idOrden) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT * FROM "Distribucion" JOIN "Suborden_distribucion" 
        ON "DbnSodId" = "SodId" WHERE "SodOrtId" = ? AND "DbnTipId" = ?', array($idOrden, $DbnTipId ));
        return $query->result_array();
    }

    //OTTomaEstado_model -> get_all_orden_trabajo_x_contratante
    //OTTomaEstado_model -> get_all_orden_x_valorizacion
    public function get_cant_distrib_x_orden($idOrden) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT("DbnId") as "cantidad" FROM "Distribucion" JOIN "Suborden_distribucion" '
                . 'ON "DbnSodId" = "SodId"  WHERE "SodOrtId" = ? AND "DbnTipId" = ?', array($idOrden, $DbnTipId));
        return $query->row_array()['cantidad'];
    }
    
    
    //OTTomaEstado_model -> get_all_orden_trabajo_x_contratante($idContratante, $idActividad)
    public function get_cant_distrib_x_orden_x_tipo($idOrden,$idTipo) {
        $query = $this->db->query('SELECT COUNT("DbnId") as "cantidad" FROM "Distribucion" JOIN "Suborden_distribucion" '
                . 'ON "DbnSodId" = "SodId" WHERE "SodOrtId" = ? AND "DbnTipId" = ?', array($idOrden,$idTipo )  );
        return $query->row_array()['cantidad'];
    }
    
    
    //OTTomaEstado_model -> get_all_orden_trabajo_x_contratante
    //OTTomaEstado_model -> get_all_orden_x_valorizacion
    public function get_cant_distrib_ejec_x_orden($idOrden) {
        $query = $this->db->query('SELECT COUNT("DbnId") as "cantidad" FROM "Distribucion" JOIN "Suborden_distribucion" '
                . 'ON "DbnSodId" = "SodId" WHERE "SodOrtId" = ? AND '
                . '"DbnVld" = TRUE', array($idOrden));
        return $query->row_array()['cantidad'];
    }
    
    //OTTomaEstado_model -> get_all_orden_trabajo_x_contratante
    public function get_cant_distrib_ejec_x_orden_x_tipo($idOrden,$idTipo) {
        $query = $this->db->query('SELECT COUNT("DbnId") as "cantidad" FROM "Distribucion" JOIN "Suborden_distribucion" '
                . 'ON "DbnSodId" = "SodId" WHERE "SodOrtId" = ? AND '
                . '"DbnVld" = TRUE AND "DbnTipId" = ?', array($idOrden , $idTipo));
        return $query->row_array()['cantidad'];
    }
    
    
    //Reporte_model -> get_ciclos_control
    public function get_cant_distribucion_ejec_x_ciclo_x_periodo($idPeriodo, $idContratante, $idgrupoPredio) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT("Distribucion".*) as "Cantidad" FROM "Distribucion" '
                .'JOIN "Suborden_distribucion" ON "SodId" = "DbnSodId" JOIN "Orden_trabajo" ON "SodOrtId" = "OrtId" '
                .'WHERE "OrtPrdId" = ? AND "SodGprId" = ? AND "OrtCntId" = ? AND "DbnVld" = TRUE AND "DbnTipId" = ?'
                , array($idPeriodo, $idgrupoPredio, $idContratante, $DbnTipId ));
        $row = $query->row_array();
        return $row['Cantidad'];
    }
    
    //OTDistribucion_model -> get_avance_x_orden
    public function get_cant_obs_x_ciclo_x_orden($idOrden, $cicloCod) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT ("Observaciones_Distribucion".*) as "cantidad" FROM "Orden_trabajo" 
        JOIN "Suborden_distribucion" ON "SodOrtId" = "OrtId" 
        JOIN "Distribucion" ON "DbnSodId" = "SodId" 
        JOIN "Grupo_predio" ON "DbnGprId" = "GprId"
        JOIN "Observaciones_Distribucion" ON "OdiDbnId" = "DbnId" AND "OrtId" = ? AND "GprCod" = ? AND "DbnTipId" = ?', array($idOrden, $cicloCod, $DbnTipId));
        $row = $query->row_array();
        return $row['cantidad'];
    }
    
    //OTTomaEstado_model -> get_avance_x_orden
    public function get_cant_lectura_total_x_ciclo_x_orden($idOrden, $cicloCod) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT ("Distribucion".*) as "cantidad" FROM "Orden_trabajo", "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId" 
        JOIN "Grupo_predio" ON "DbnGprId" = "GprId"
        WHERE "SodOrtId" = "OrtId" AND "OrtId" = ? AND "GprCod" = ? AND "DbnTipId" = ?', array($idOrden, $cicloCod, $DbnTipId));
        $row = $query->row_array();
        return $row['cantidad'];
    }
    
    //OTTomaEstado_model -> get_avance_x_orden
    public function get_cant_lectura_ejec_x_ciclo_x_orden($idOrden, $cicloCod) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT ("Distribucion".*) as "cantidad" 
        FROM "Orden_trabajo", "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId" 
        JOIN "Grupo_predio" ON "DbnGprId" = "GprId"
        WHERE "SodOrtId" = "OrtId" AND "OrtId" = ? AND "GprCod" = ? AND "LecVld" = TRUE AND "DbnTipId" = ?', array($idOrden, $cicloCod , $DbnTipId));
        $row = $query->row_array();
        return $row['cantidad'];
    }
    
    
    //OTDistribucion_model -> get_avance_x_orden
    public function get_cant_distribucion_total_x_distribuidor_x_ciclo_x_orden($idDistribuidor , $idOrden, $cicloCod, $fchEj) {
        $query = $this->db->query('SELECT COUNT ("Distribucion".*) as "cantidad" FROM "Orden_trabajo", "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId"
        JOIN "Grupo_predio" ON "DbnGprId" = "GprId" 
        JOIN "Distribuidor" ON "SodDbrId" = "DbrId"
        WHERE "SodOrtId" = "OrtId" AND "OrtId" = ? AND "GprCod" = ? AND "SodDbrId" = ? AND "SodFchEj" = ?', array($idOrden, $cicloCod, $idDistribuidor , $fchEj));
        $row = $query->row_array();
        return $row['cantidad'];
    }
    
    //OTDistribucion_model -> get_avance_x_orden
    public function get_cant_distribucion_ejec_x_distribuidor_x_ciclo_x_orden($idDistribuidor , $idOrden, $cicloCod, $fchEj) {
        $query = $this->db->query('SELECT COUNT ("Distribucion".*) as "cantidad" FROM "Orden_trabajo"
        JOIN "Suborden_distribucion" ON "SodOrtId" = "OrtId" 
        JOIN "Distribucion" ON "DbnSodId" = "SodId" 
        JOIN "Grupo_predio" ON "DbnGprId" = "GprId"
        WHERE "SodOrtId" = ? AND "GprCod" = ? AND "SodDbrId" = ? AND "DbnVld" = TRUE AND "SodFchEj" = ?', array($idOrden, $cicloCod, $idDistribuidor , $fchEj));
        $row = $query->row_array();
        return $row['cantidad'];
    }
    
    
    
    
    
    //OTTomaEstado_cst_model -> orden_imprimir_suborden
    public function get_cant_lectura_total_x_suborden_x_ciclo_x_orden($idOrden , $idSubOrden) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT ("Distribucion".*) as "cantidad" FROM "Orden_trabajo", "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId"
        WHERE "SodOrtId" = "OrtId" AND "OrtId" = ? AND "SodId" = ? AND "DbnTipId" = ?', array($idOrden , $idSubOrden, $DbnTipId));
        $row = $query->row_array();
        return $row['cantidad'];
    }
    
    
    //OTDistribucion_model -> get_avance_x_ciclo
    public function get_cant_obs_x_distribuidor_x_ciclo_x_orden($idDistribuidor , $idOrden, $cicloCod) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT("Observaciones_Distribucion".*) as "cantidad" FROM "Orden_trabajo" 
        JOIN "Suborden_distribucion" ON "SodOrtId" = "OrtId" 
        JOIN "Distribucion" ON "DbnSodId" = "SodId" 
        JOIN "Grupo_predio" ON "DbnGprId" = "GprId"
        JOIN "Observaciones_Distribucion" ON "OdiDbnId" = "DbnId"
        WHERE "SodOrtId" = ? AND "GprCod" = ? AND "SodDbrId" = ? AND "DbnTipId" = ?', array($idOrden, $cicloCod, $idDistribuidor , $DbnTipId));
        $row = $query->row_array();
        return $row['cantidad'];
    }
    
    
    
    //OTTomaEstado_ctrllr -> avance_suborden
    public function get_obs_x_suborden($idSuborden) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT "Observacion".*, COUNT("ObsId") as "cantidad" FROM "Observaciones_Distribucion" 
        JOIN "Observacion" ON "OdiObsId" = "ObsId"
        JOIN "Distribucion" ON "OdiDbnId" = "DbnId" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId"
        AND "SodCod" = ? AND "DbnTipId" = ? GROUP BY "ObsId";', array($idSuborden, $DbnTipId));
        return $query->result_array();
    }
    //OTTomaEstado_model -> get_avance_x_suborden
    public function get_obs_x_lectura($idLectura) {
        $query = $this->db->query('SELECT * FROM "Observaciones_Distribucion" JOIN "Observacion" ON "OleObsId" = "ObsId" '
                . 'AND "OleLecId" = ?', array($idLectura));
        return $query->result_array();
    }
    //OTTomaEstado_cst_ctrllr -> orden_ver()
    public function get_detalle_suministros_lecturista($idOrden) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT "Suborden_distribucion".*,COUNT(*) AS "suministros","GprCod" 
        FROM "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId"
        JOIN "Distribuidor" ON "SodDbrId" = "DbrId"
        JOIN "Grupo_predio" ON "DbnGprId" = "GprId"
        WHERE "SodOrtId" = ? AND "DbnEli" = FALSE AND "DbnTipId" = ?
        GROUP BY "SodId","SodDbrId","GprCod","SodCod" ORDER BY "GprCod"::integer,"SodCod"::integer ASC', array($idOrden, $DbnTipId));
        return $query->result_array();
    }
    
    
    //OTDistribucion_cst_ctrllr -> orden_ver()
    public function get_detalle_suministros_sin_asignar_distribuidor($idOrden,$SodFchEj) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT "Suborden_distribucion".*,COUNT(*) AS "suministros"
        FROM "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId"
        JOIN "Grupo_predio" ON "DbnGprId" = "GprId"
        WHERE "SodOrtId" = ? AND "DbnEli" = FALSE AND "DbnTipId" = ? AND "SodFchEj" = ?
        GROUP BY "SodId","SodDbrId","SodCod" ORDER BY "SodCod"::integer ASC', array($idOrden, $DbnTipId  , $SodFchEj));
        return $query->result_array();
    }
    
    
    //Inicio_cst_ctrllr -> inicio() 
    public function get_subordenes_x_fechaEj_x_lecturista($SolFchEj, $SolLtaId) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $query = $this->db->query('SELECT "Suborden_distribucion".*,COUNT(*) AS "suministros","GprCod","OrtId" 
        FROM "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId"
        JOIN "Orden_trabajo" ON "SodOrtId" = "OrtId"
        JOIN "Distribuidor" ON "SodDbrId" = "DbrId"
        JOIN "Grupo_predio" ON "DbnGprId" = "GprId"
        WHERE "DbnEli" = FALSE AND "DbnTipId" = ? AND "SodFchEj"= ? AND "SodDbrId" = ?
	GROUP BY "SodId","SodDbrId","GprCod","SodCod","OrtId"  ORDER BY "GprCod"::integer,"SodCod"::integer ASC,"OrtId"', array($DbnTipId, $SolFchEj, $SolLtaId));
        return $query->result_array();
    }
    
    
    
    /*   DATOS PARA LA APLICACION MOVIL : TOMA DE ESTADO    */
    //ConsultasMovil_cst_ctrllr -> obtener_lecturas_en_ejec_x_lecturista_json
    public function get_lecturas_x_lecturista($OrtFchEj, $LecLtaId) {
        $query = $this->db->query('SELECT "SodOrtId","Distribucion".* FROM "Distribucion" 
        JOIN "Suborden_distribucion" ON "DbnSodId" = "SodId" 
        WHERE "SodFchEj" = ? AND "SodDbrId" = ? AND "DbnEli" = FALSE AND "LecVld" IS NOT TRUE ORDER BY "LecId"', array($OrtFchEj, $LecLtaId ));
        return $query->result_array();
    }
    //ConsultasMovil_cst_ctrllr -> sincronizar_lecturas_json
    public function insert_lectura_sincronizacion_movil($LecVal, $LecValInt1, $LecValInt2, $LecValInt3, $LecInt, $LecLat, $LecLong, $LecImg, $LecCom, $LecFchRg, $LecId) {
        $query = $this->db->query('UPDATE "Distribucion" SET "LecVal" = ?, "LecVal1" = ?, "LecVal2" = ?, "LecVal3" = ?, "LecInt" = ?, "LecPosLt" = ?, "LecPosLg" = ?, "LecImg" = ?, "LecCom" = ?, "LecFchRgMov" = ?, "LecDis" = ?, "LecVld" = ?, "LecFchAc" = ? WHERE "LecId" = ?'
                , array($LecVal, $LecValInt1, $LecValInt2, $LecValInt3, $LecInt, $LecLat, $LecLong, $LecImg, $LecCom, $LecFchRg , TRUE, TRUE, date("Y-m-d H:i:s"), $LecId));
        return $query;
    }
    //ConsultasMovil_cst_ctrllr -> sincronizar_observaciones_lecturas_json
    public function insert_observaciones_lectura_sincronizacion_movil($OleLecId, $OleObsId, $OleImg) {
        $query = $this->db->query('INSERT into "Observaciones_Distribucion" ("OleImg", "OleEli" , "OleFchRg", "OleFchAc" , "OleLecId" , "OleObsId" ) '
                . ' VALUES (?,?,?,?,?,?);', array($OleImg, false, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $OleLecId, $OleObsId));
        return $query;
    }
    public function insert_imagen_lectura_sincronizacion_movil($LecImg, $LecId) {
        $query = $this->db->query('UPDATE "Distribucion" SET "LecImg" = ? WHERE "LecId" = ?'
                , array( $LecImg , $LecId));
        return $query;
    }
    
    /* ------------------------------------------------------ POR CAMBIAR ------------------------*/
    
}