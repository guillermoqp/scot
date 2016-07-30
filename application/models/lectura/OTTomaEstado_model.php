<?php

class OTTomaEstado_model extends CI_Model {
   
    function __construct() {
        parent::__construct();
        $this->load->model('lectura/Lectura_model');
        $this->load->model('lectura/Lecturista_model');
        $this->load->model('general/Periodo_model');
        $this->load->model('general/NivelGrupo_model');
        $this->load->model('general/Actividad_model');
        $this->load->model('lectura/Valorizacion_model');
    }
    public function get_one_orden_trabajo($idOrdenTrabajo) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" WHERE "OrtId" = ? AND "OrtEli" = FALSE;', array($idOrdenTrabajo));
        return $query->row_array();
    }
    public function get_ordenes_trabajo_x_contrato_x_anio($idActividad,$idContrato,$anio) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" 
        WHERE "OrtActId" = ? AND "OrtConId" = ? AND date_part(\'year\', "OrtFchEj") = ? ORDER BY "OrtFchEj"', array($idActividad,$idContrato,$anio));
        return $query->result_array();
    }
    //OTTomaEstado_ctrllr -> avance_orden
    public function get_one_orden_trabajo_all_datos($idOrdenTrabajo) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo"
        JOIN "Actividad" ON "OrtActId" = "ActId"
        JOIN "Contrato" ON "OrtConId" = "ConId"
        JOIN "Contratante" ON "ConCntId" = "CntId"
        WHERE "OrtId" = ? AND "OrtEli" = FALSE;', array($idOrdenTrabajo));
        //CANTIDAD LECTURAS
        $idTipoRegular = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $cantidad = $this->Lectura_model->get_cant_lect_x_orden_x_tipo($idOrdenTrabajo, $idTipoRegular);
        //AGREGAR AVANCE
        $validos = $this->Lectura_model->get_cant_lect_ejec_x_orden($idOrdenTrabajo);
        $avance = 0;
        $orden=$query->row_array();
        if ($cantidad != 0) {
            $avance = round($validos * 100 / $cantidad, 2);
        }
        $orden['avance'] = $avance;
        return $orden;
    }
    public function get_periodo_orden_trabajo($idOrdenTrabajo) {
        $query = $this->db->query('SELECT "Periodo".* FROM "Periodo" JOIN "Orden_trabajo" ON "OrtPrdId" = "PrdId" 
        WHERE "PrdEli" = FALSE AND "OrtId" = ? ;', array($idOrdenTrabajo));
        return $query->row_array();
    }
    //OTTomaEstado_ctrllr -> orden_nuevo
    public function get_periodo($idPeriodo)
    {
        $query = $this->db->query('SELECT * FROM "Periodo" WHERE "PrdEli" = FALSE AND "PrdId" = ?', array($idPeriodo));
        return $query->row_array();
    }
    public function get_ciclo_orden_trabajo($idOrdenTrabajo) {
        $query = $this->db->query('SELECT "Grupo_predio"."GprCod","SolGprId" FROM "Suborden_lectura"
        JOIN "Grupo_predio" ON "SolGprId" = "GprId"
        WHERE "SolOrtId" = ? GROUP By "SolGprId","Grupo_predio"."GprCod"', array($idOrdenTrabajo));
        return $query->row_array();
    }
    //OTTomaEstado_ctrllr -> orden_ver()
    public function get_one_orden_trabj_all_datos($idOrdenTrabajo) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" '
                . 'JOIN "Actividad" ON "OrtActId" = "ActId" '
                . 'JOIN "Contrato" ON "OrtConId" = "ConId" '
                . 'JOIN "Contratante" ON "ConCntId" = "CntId" '
                . 'WHERE "OrtId" = ? AND "OrtEli" = FALSE;', array($idOrdenTrabajo));
        $orden = $query->row_array();
        //AGREGAR LOS CICLOS
        $query2 = $this->db->query('SELECT DISTINCT "GprCod" FROM "Suborden_lectura" JOIN "Grupo_predio" ON "SolGprId" = "GprId" WHERE "SolOrtId" = ?', array($orden['OrtId']));
        $ciclos = $query2->result_array();
        $orden['ciclos'] = implode(', ', array_column($ciclos, 'GprCod'));
        //AGREGAR EL PERIODO
        $orden['periodo'] = $this->Periodo_model->get_one_periodo($orden['OrtPrdId']);
        //AGREGAR LA CANTIDAD
        $cantidad = $this->Lectura_model->get_cant_lect_x_orden($orden['OrtId']);
        $ordenes['cantidad'] = $cantidad;
        //AGREGAR AVANCE
        $validos = $this->Lectura_model->get_cant_lect_ejec_x_orden($orden['OrtId']);
        $avance = 0;
        if ($cantidad != 0) 
            {
                $avance = round($validos * 100 / $cantidad, 2);
            }
        $ordenes['avance'] = $avance;
        
        return $orden;
    }
    public function get_one_orden_trabajo_x_num($numOrdenTrabajo) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" WHERE "OrtNum" = ? AND "OrtEli" = FALSE;', array($numOrdenTrabajo));
        return $query->row_array();
    }
    public function get_all_orden_trabajo() {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" '
                . 'JOIN "Detalle_parametro" ON "OrtEstId" = "DprId" '
                . 'WHERE "OrtEli" = FALSE ORDER BY "OrtNum" ASC;');
        return $query->result_array();
    }
    public function get_all_orden_trabajo_archivos() {
        $ordenesTrabajo = $this->get_all_orden_trabajo();
        foreach ($ordenesTrabajo as $key => $ordenTrabajo) {
            $archivos = $this->get_archivos_x_ordenTrabajo($ordenTrabajo['OrtId']);
            $ordenesTrabajo[$key]['archivos'] = $archivos;
        }
        return $ordenesTrabajo;
    }
    /* OBTIENE LAS ORDENES DE TRABAJO POR ID DE USUARIO Y EL ID DEL ESTADO DE LA ORDEN  */
    public function get_all_orden_trabajo_x_usuario($idUsuario, $OrtEstId) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" '
                . 'JOIN "Actividad" ON "OrtActId" = "ActId"'
                . 'JOIN "Contrato" ON "OrtConId" = "ConId" '
                . 'JOIN "Contratante" ON "ConCntId" = "CntId" '
                . 'JOIN "Usuario" ON "UsrCntId" = "CntId" '
                . 'WHERE "UsrId" = ? AND "OrtEstId" = ? AND "OrtEli" = FALSE ORDER BY "OrtNum" ASC;', array($idUsuario, $OrtEstId));
        return $query->result_array();
    }
    //OTTomaEstado_ctrllr -> orden_listar()
    public function get_all_orden_trabajo_x_contratante($idContratante, $idActividad ) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" JOIN "Detalle_parametro" ON "OrtEstId" = "DprId" WHERE "OrtActId" = ? AND "OrtCntId" = ? AND "OrtEli" = FALSE ORDER BY "OrtFchRg" DESC', array($idActividad, $idContratante));
        $ordenes = $query->result_array();
        foreach ($ordenes as $key => $orden) {
            //AGREGAR LOS CICLOS
            
            $ordenes[$key]['nivel_comercial'] = $this->NivelGrupo_model->get_one_nivelGrupo($orden['OrtNgrId']);
            $cantidadSubniveles = $this->NivelGrupo_model->get_cant_subniveles($orden['OrtNgrId']);
            if($cantidadSubniveles == 0)
            {
                $query2 = $this->db->query('SELECT DISTINCT "GprCod" FROM "Suborden_lectura" JOIN "Lectura" ON "SolId" = "LecSolId" JOIN "Grupo_predio" ON "LecGprId" = "GprId" WHERE "SolOrtId" = ?', array($orden['OrtId']));
                $ciclos = $query2->result_array();
            }
            else {
                $query2 = $this->db->query('SELECT DISTINCT "GprCod" FROM "Suborden_lectura" JOIN "Grupo_predio" ON "SolGprId" = "GprId" WHERE "SolOrtId" = ?', array($orden['OrtId']));
                $ciclos = $query2->result_array();
            }
            $ordenes[$key]['ciclos'] = implode(', ', array_column($ciclos, 'GprCod'));
            //agregar el periodo
            $ordenes[$key]['periodo'] = $this->Periodo_model->get_one_periodo($orden['OrtPrdId']);
            
            //AGREGAR METRADO PLANIFICADO
            $idTipoRegular = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
            $ordenes[$key]['cantidad']['lec_reg'] = $this->Lectura_model->get_cant_lect_x_orden_x_tipo($orden['OrtId'] , $idTipoRegular);
            $idTipoSupervision = $this->Parametro_model->get_one_detParam_x_codigo('lec_sup')['DprId'];
            $idTipoAtipico = $this->Parametro_model->get_one_detParam_x_codigo('lec_ati')['DprId'];
            $ordenes[$key]['cantidad']['lec_sup'] = $this->Lectura_model->get_cant_lect_x_orden_x_tipo($orden['OrtId'], $idTipoSupervision) + $this->Lectura_model->get_cant_lect_x_orden_x_tipo($orden['OrtId'], $idTipoAtipico );
            $ordenes[$key]['cantidad']['lec_ati'] = $this->Lectura_model->get_cant_lect_x_orden_x_tipo($orden['OrtId'], $idTipoAtipico );
            
            //AGREGAR METRADO EJECUTADO REGULARES
            $validos = $this->Lectura_model->get_cant_lect_ejec_x_orden($orden['OrtId']);
            $ordenes[$key]['validos'] = $validos;
            $avance = 0;
            if ($ordenes[$key]['cantidad']['lec_reg'] != 0) {
                $avance = round($validos * 100 / $ordenes[$key]['cantidad']['lec_reg'], 2);
            }
            $ordenes[$key]['avance'] = $avance;
            
            //AGREGAR METRADO EJECUTADO SUPERVISION
            $validosSupervision = $this->Lectura_model->get_cant_lect_ejec_x_orden_x_tipo($orden['OrtId'],$idTipoSupervision);
            $ordenes[$key]['validosSupervision'] = $validosSupervision;
            $avanceSupervision = 0;
            if ($ordenes[$key]['cantidad']['lec_sup'] != 0) {
                $avanceSupervision = round($validosSupervision * 100 / $ordenes[$key]['cantidad']['lec_sup'], 2);
            }
            $ordenes[$key]['avanceSupervision'] = $avanceSupervision;
        }
        return $ordenes;
    }
    
    public function get_all_orden_trabajo_x_contratante_cerradas($idContratante, $idActividad ) {
        $idTipoCerrada = $this->Parametro_model->get_one_detParam_x_codigo('cer')['DprId'];
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" JOIN "Detalle_parametro" ON "OrtEstId" = "DprId" WHERE "OrtActId" = ? AND "OrtCntId" = ? AND "OrtEli" = FALSE AND "DprId" = ? ORDER BY "OrtFchRg" DESC', array($idActividad, $idContratante,$idTipoCerrada));
        $ordenes = $query->result_array();
        foreach ($ordenes as $key => $orden) {
            //AGREGAR LOS CICLOS
            $query2 = $this->db->query('SELECT DISTINCT "GprCod" FROM "Suborden_lectura" JOIN "Grupo_predio" ON "SolGprId" = "GprId" WHERE "SolOrtId" = ?', array($orden['OrtId']));
            $ciclos = $query2->result_array();
            $ordenes[$key]['nivel_comercial'] = $this->NivelGrupo_model->get_one_nivelGrupo($orden['OrtNgrId']);
            $ordenes[$key]['ciclos'] = implode(', ', array_column($ciclos, 'GprCod'));
            //agregar el periodo
            $ordenes[$key]['periodo'] = $this->Periodo_model->get_one_periodo($orden['OrtPrdId']);
            
            //AGREGAR LA CANTIDAD
            $idTipoRegular = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
            $ordenes[$key]['cantidad']['lec_reg'] = $this->Lectura_model->get_cant_lect_x_orden_x_tipo($orden['OrtId'] , $idTipoRegular);
            $idTipoSupervision = $this->Parametro_model->get_one_detParam_x_codigo('lec_sup')['DprId'];
            $idTipoAtipico = $this->Parametro_model->get_one_detParam_x_codigo('lec_ati')['DprId'];
            $ordenes[$key]['cantidad']['lec_sup'] = $this->Lectura_model->get_cant_lect_x_orden_x_tipo($orden['OrtId'], $idTipoSupervision) + $this->Lectura_model->get_cant_lect_x_orden_x_tipo($orden['OrtId'], $idTipoAtipico );
            $ordenes[$key]['cantidad']['lec_ati'] = $this->Lectura_model->get_cant_lect_x_orden_x_tipo($orden['OrtId'], $idTipoAtipico );
            
            //AGREGAR AVANCE
            $validos = $this->Lectura_model->get_cant_lect_ejec_x_orden($orden['OrtId']);
            $avance = 0;
            if ($ordenes[$key]['cantidad']['lec_reg'] != 0) {
                $avance = round($validos * 100 / $ordenes[$key]['cantidad']['lec_reg'], 2);
            }
            $ordenes[$key]['avance'] = $avance;
        }
        return $ordenes;
    }
    
    // OTTomaEstado_ctrllr -> orden_listar
    public function get_all_orden_trabajo_temp($idContratante) {
        $idTipoRegular = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('(SELECT * FROM "Detalle_cron_lectura_periodo" 
        JOIN "Cron_lectura_periodo" ON "DlpClpId" = "ClpId"
        JOIN "Grupo_predio" ON "DlpGprId" = "GprId"
        JOIN "Nivel_grupo" ON "GprNgrId" = "NgrId"
        WHERE ("DlpFchEn" = CURRENT_DATE OR "DlpFchEn" = (CURRENT_DATE - 1) OR "DlpFchEn" = (CURRENT_DATE + 1)) AND "ClpCntId" = ? ORDER BY "DlpFchEn" ASC,"DlpId") EXCEPT 
        (SELECT DISTINCT("Detalle_cron_lectura_periodo".*),"Cron_lectura_periodo".*,"Grupo_predio".*,"Nivel_grupo".*
        FROM "Detalle_cron_lectura_periodo" 
        JOIN "Cron_lectura_periodo" ON "DlpClpId" = "ClpId"
        JOIN "Grupo_predio" ON "DlpGprId" = "GprId"
        JOIN "Nivel_grupo" ON "GprNgrId" = "NgrId"
        JOIN "Lectura" ON "GprId" = "LecGprId"
        WHERE ("DlpFchEn" = CURRENT_DATE OR "DlpFchEn" = (CURRENT_DATE - 1) OR "DlpFchEn" = (CURRENT_DATE + 1)) AND "ClpCntId" = ? AND "LecTipId" = ? ORDER BY "DlpFchEn" ASC) ORDER BY "DlpFchEn" ASC,"DlpGprId"', array($idContratante,$idContratante,$idTipoRegular));
        $rows = $query->result_array();
        $ordenes = array();
        foreach ($rows as $key => $row) {
            $fechaEntrega = date('-m-Y', strtotime($row['DlpFchEn']));
            $this->load->library('utilitario_cls');
            $parts = explode('-', $fechaEntrega);
            $nombreMes = $this->utilitario_cls->nombreMes(intval($parts[1]) + 1);
            $fechaToma = date('d-m-Y', strtotime($row['DlpFchTm']));
            $NgrDes = $row['NgrDes'];
            $GprCod = $row['GprCod'];
            //$ordenes[$key] = array('OrtNum'=>$fechaEntrega,'OrtDes'=>$nombreMes.' / '.$parts[2], 'OrtFchEj'=>$fechaToma);
            $ordenes[$row['DlpFchTm']][$row['GprCod']] = array('OrtNum' => $fechaEntrega, 'OrtDes' => $nombreMes . ' / ' . $parts[2], 'OrtFchEj' => $fechaToma, 'NgrDes' => $NgrDes, 'GprCod' => $GprCod, 'DlpId' => $row['DlpId']);
        }
        return $ordenes;
    }
    
    // OTTomaEstado_ctrllr -> orden_listar -- por ciclo --nivel superior
    public function get_all_orden_trabajo_temp_NivSup($idContratante) {
        $query = $this->db->query('(SELECT * FROM "Detalle_cron_lectura_periodo" 
        JOIN "Cron_lectura_periodo" ON "DlpClpId" = "ClpId"
        JOIN "Grupo_predio" ON "DlpGprId" = "GprId"
        JOIN "Nivel_grupo" ON "GprNgrId" = "NgrId"
        WHERE ("DlpFchEn" = CURRENT_DATE OR "DlpFchEn" = (CURRENT_DATE - 1) OR "DlpFchEn" = (CURRENT_DATE + 1)) AND "ClpCntId" = ? ORDER BY "DlpFchEn" ASC,"DlpId") EXCEPT 
        (SELECT DISTINCT("Detalle_cron_lectura_periodo".*),"Cron_lectura_periodo".*,"Grupo_predio".*,"Nivel_grupo".*
        FROM "Detalle_cron_lectura_periodo" 
        JOIN "Cron_lectura_periodo" ON "DlpClpId" = "ClpId"
        JOIN "Grupo_predio" ON "DlpGprId" = "GprId"
        JOIN "Nivel_grupo" ON "GprNgrId" = "NgrId"
        JOIN "Suborden_lectura" ON "SolGprId" = "DlpGprId"
        WHERE ("DlpFchEn" = CURRENT_DATE OR "DlpFchEn" = (CURRENT_DATE - 1) OR "DlpFchEn" = (CURRENT_DATE + 1)) AND "ClpCntId" = ? ORDER BY "DlpFchEn" ASC) ORDER BY "DlpFchEn" ASC,"DlpGprId"', array($idContratante,$idContratante));
        $rows = $query->result_array();
        $ordenes = array();
        foreach ($rows as $key => $row) {
            $fechaEntrega = date('-m-Y', strtotime($row['DlpFchEn']));
            $this->load->library('utilitario_cls');
            $parts = explode('-', $fechaEntrega);
            $nombreMes = $this->utilitario_cls->nombreMes(intval($parts[1]) + 1);
            $fechaToma = date('d-m-Y', strtotime($row['DlpFchTm']));
            $NgrDes = $row['NgrDes'];
            $GprCod = $row['GprCod'];
            //$ordenes[$key] = array('OrtNum'=>$fechaEntrega,'OrtDes'=>$nombreMes.' / '.$parts[2], 'OrtFchEj'=>$fechaToma);
            $ordenes[$row['DlpFchTm']][$row['GprCod']] = array('OrtNum' => $fechaEntrega, 'OrtDes' => $nombreMes . ' / ' . $parts[2], 'OrtFchEj' => $fechaToma, 'NgrDes' => $NgrDes, 'GprCod' => $GprCod, 'DlpId' => $row['DlpId']);
        }
        return $ordenes;
    }
    
    public function get_all_orden_trabajo_x_contratista($idContratista) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" JOIN "Contrato" ON "OrtConId" = "ConId" '
                . 'JOIN "Contratista" ON "ConCstId" = "CstId" '
                . 'WHERE "CstId" = ? AND "OrtEli" = FALSE ORDER BY "OrtNum" ASC;', array($idContratista));
        return $query->result_array();
    }
    //Reporte_model -> get_ciclos_control
    public function get_fechas_ejecucion_x_ciclo_x_periodo($idPeriodo, $idContratante, $idCiclo) {
        $query = $this->db->query('SELECT DISTINCT "SolFchEj" FROM "Suborden_lectura", "Orden_trabajo" , "Periodo" 
        WHERE 
        "SolOrtId" = "OrtId" AND "OrtPrdId" = "PrdId" AND
        "PrdId" = ?  AND "SolGprId" = ? AND "OrtCntId" = ? ORDER BY "SolFchEj" ASC ;', array($idPeriodo, $idCiclo, $idContratante));
        $rows = $query->result_array();
        foreach ($rows as $key => $row) {
            $rows[$key]['SolFchEj'] = date('d/m/Y', strtotime($row['SolFchEj']));
        }
        $fechas = implode(',', array_column($rows, 'SolFchEj'));
        return $fechas;
    }
    //OTTomaEstado_ctrllr -> avance_orden
    public function get_avance_x_orden($idOrden) {
        $ciclos = $this->Lectura_model->get_ciclos_x_orden($idOrden);
        foreach ($ciclos as $key => $ciclo) {
            
            $ciclos[$key]['lecturistas'] = $this->Lecturista_model->get_lecturista_x_ciclo_x_orden($idOrden, $ciclo['GprCod']);
            
            $ciclos[$key]['cantLecturistas'] = count($ciclos[$key]['lecturistas']);
            $ciclos[$key]['cantLecturas'] = $this->Lectura_model->get_cant_lectura_total_x_ciclo_x_orden($idOrden, $ciclo['GprCod']);
            $ciclos[$key]['cantLecturasEj'] = $this->Lectura_model->get_cant_lectura_ejec_x_ciclo_x_orden($idOrden, $ciclo['GprCod']);
            $ciclos[$key]['avance'] = round($ciclos[$key]['cantLecturasEj'] * 100 / $ciclos[$key]['cantLecturas'], 2);
            $ciclos[$key]['cantObs'] = $this->Lectura_model->get_cant_obs_x_ciclo_x_orden($idOrden, $ciclo['GprCod']);
            foreach ($ciclos[$key]['lecturistas'] as $key2 => $lecturista) {
                $ciclos[$key]['lecturistas'][$key2]['cantLecturas'] = $this->Lectura_model->get_cant_lectura_total_x_lecturista_x_ciclo_x_orden($lecturista['LtaId'], $idOrden, $ciclo['GprCod'], $lecturista['SolFchEj']);
                $ciclos[$key]['lecturistas'][$key2]['cantLecturasEj'] = $this->Lectura_model->get_cant_lectura_ejec_x_lecturista_x_ciclo_x_orden($lecturista['LtaId'], $idOrden, $ciclo['GprCod'], $lecturista['SolFchEj']);
                
                $ciclos[$key]['lecturistas'][$key2]['nombre'] = $lecturista['UsrApePt'] . ' ' . $lecturista['UsrApeMt'] . ' ' . $lecturista['UsrNomPr'];
                $ciclos[$key]['lecturistas'][$key2]['supervisor'] =  ( $lecturista['LtaSup'] == 't' ) ? true : false;
                
                if($ciclos[$key]['lecturistas'][$key2]['cantLecturas']==0){
                    $ciclos[$key]['lecturistas'][$key2]['avance'] = 0;
                } else {
                    $ciclos[$key]['lecturistas'][$key2]['avance'] = round($ciclos[$key]['lecturistas'][$key2]['cantLecturasEj'] * 100 / $ciclos[$key]['lecturistas'][$key2]['cantLecturas'], 2);
                }
                $ciclos[$key]['lecturistas'][$key2]['cantObs'] = $this->Lectura_model->get_cant_obs_x_lecturista_x_ciclo_x_orden($lecturista['LtaId'], $idOrden, $ciclo['GprCod']);
            }
        }
        return $ciclos;
    }
    //OTTomaEstado_ctrllr -> avance_suborden
    public function get_avance_x_suborden($idOrden, $codSubOrden) {
        $lecturas = $this->Lectura_model->get_lecturas_x_suborden($idOrden, $codSubOrden);
        foreach ($lecturas as $key => $lectura) {
            $observaciones = $this->Lectura_model->get_obs_x_lectura($lectura['LecId']);
            //$lecturas[$key]['observaciones'] = implode(', ', array_column($observaciones, 'ObsCod'));
            $lecturas[$key]['observaciones'] = $observaciones;
        }
        return $lecturas;
    }
    //OTTomaEstado_ctrllr -> guardar_orden()
    //ORDEN POR SUBCICLOS DE UN MISMO CICLO 
    public function insert_orden_nivel_inferior($OrtNum, $OrtDes, $OrtObs, $OrtPrd, $OrtFchEj, $OrtFchEm, $OrtConId, $OrtEstId, $OrtActId, $OrtUsrEn, $OrtCntId, $detalle, $idBuscarGruposPredio , $idCiclo, $nivel , $supervision , $tamanioMuestra ) {
        
        $this->db->trans_start();
        
        $query = $this->db->query('INSERT INTO "Orden_trabajo"("OrtNum","OrtDes","OrtObs","OrtFchEj","OrtFchEn","OrtFchEm","OrtEli","OrtFchRg","OrtFchAc","OrtEstId","OrtConId","OrtActId","OrtPrdId","OrtNgrId","OrtUsrEn","OrtCntId") '
                . 'VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array($OrtNum,$OrtDes,$OrtObs,$OrtFchEj,date("Y-m-d H:i:s"),$OrtFchEm,false,date("Y-m-d H:i:s"),date("Y-m-d H:i:s"),$OrtEstId,$OrtConId,$OrtActId,$OrtPrd,$nivel,$OrtUsrEn,$OrtCntId));
        $idOrden = $this->db->insert_id();
        $periodo = $this->Periodo_model->get_one_periodo($OrtPrd);
        $lectAcumuladas = 0; // el offset
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $SolTipId = $this->Parametro_model->get_one_detParam_x_codigo('sol_reg')['DprId'];
        foreach ($detalle as $nroDia => $dia) {
                $lecturistas = $dia['lecturistas'];
                foreach ($dia['subordenes'] as $nroSuborden => $suborden) {
                    $fechaEj = $dia['fecha'];
                    if ($dia['aleatorio']) {
                        //$idLecturista = array_rand($lecturistas);
                        //unset($lecturistas[$idLecturista]);
                        $idLecturista = null;
                    }else{
                        //$idLecturista = $suborden['idLecturista'];
                        $idLecturista = null;
                    }
                    $query = $this->insert_suborden($nroSuborden, $fechaEj, $SolTipId , $idOrden, $idLecturista, $idBuscarGruposPredio , $suborden['cantidad'] );
                    $idSuborden = $this->db->insert_id();
                    $mes = $periodo['PrdOrd'] == 1 ? 12 : $periodo['PrdOrd'] - 1; // cambiar el 12 por un metodo que devuelva la ultima orden
                    $anio = $mes == 12 ? $periodo['PrdAni'] - 1 : $periodo['PrdAni'];
                    $conexiones = $this->Catastro_model->get_conexiones_x_subciclos_x_suborden_batch($idSuborden, $idCiclo , $suborden['cantidad'] , $nroSuborden, $anio, $mes, $OrtCntId, $LecTipId , $lectAcumuladas);
                    $lecturas = $this->db->insert_batch('"Lectura"', $conexiones);
                    $lectAcumuladas += $suborden['cantidad'];
            }
        }
        
        //SI SE INSERTO EN LECTURAS
        if ($this->db->affected_rows() > 0) {
            //INSERTAR SUBORDEN DE SUPERVISION
            if (isset($supervision)) {
                $nroSubordenSupervision = $nroSuborden + 1;
                $idSupervisor = $this->Lecturista_model->get_lecturista_supervisor($OrtCntId)['LtaId'];

                $fecha = str_replace("/", "-", $fechaEj);
                $nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
                $fechaEjSubordenSupervision = date ( 'd/m/Y' , $nuevafecha );
                
                $SolTipId = $this->Parametro_model->get_one_detParam_x_codigo('sol_sup')['DprId'];
                $query = $this->insert_suborden($nroSubordenSupervision,$fechaEjSubordenSupervision,$SolTipId,$idOrden,$idSupervisor,$idBuscarGruposPredio , $tamanioMuestra );
                $idSuborden = $this->db->insert_id();
                $mes = $periodo['PrdOrd']==1?12:$periodo['PrdOrd']-1; // cambiar el 12 por un metodo que devuelva la ultima orden
                $anio = $mes==12?$periodo['PrdAni']-1:$periodo['PrdAni'];
                
                $lecturas = array_column($this->Lectura_model->get_lecturas_x_orden($idOrden), 'LecId');
                
                $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_sup')['DprId'];
                for ($i = 0; $i < $tamanioMuestra; $i++) 
                {
                    $rand=array_rand($lecturas);
                    $idLectura = $lecturas[$rand];
                    $resultado = $this->Lectura_model->get_one_lectura($idLectura);
                    $query = $this->db->query('INSERT INTO "Lectura" ("LecCodFc","LecNom","LecUrb","LecCal","LecMun","LecMed",'
                            . '"LecInt","LecEli","LecFchRg","LecFchAc","LecCxnId","LecCntId","LecCsmPr","LecAnt","LecDis","LecTipId","LecSolId","LecGprId") '
                            . 'VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)' , array( $resultado['LecCodFc'], $resultado['LecNom'], $resultado['LecUrb'],  $resultado['LecCal'], $resultado['LecMun'], $resultado['LecMed'], 0, FALSE,date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $resultado['LecCxnId'], $resultado['LecCntId'] , $resultado['LecCsmPr'] , $resultado['LecAnt'] , $resultado['LecDis'] , $LecTipId , $idSuborden , $resultado['LecGprId'] ));
                    unset($lecturas[$rand]);
                }
                
                if ($this->db->affected_rows() > 0 ) {
                    $this->db->trans_complete();
                    return TRUE;
                } else {
                    log_message('error', $this->campaign_id.' :: No se puede generar las Lecturas de Supervision.');
                    log_message('error', $this->ci->db->_error_message());
                    return FALSE;    
                }
            }
        }
        else {
            log_message('error', $this->campaign_id.' :: No se puedo generar la Orden de Trabajo de Toma de Estado, subordenes de Lectura y Lecturas de la Orden.');
            log_message('error', $this->ci->db->_error_message());
            return FALSE; 
        }
    }
    //OTTomaEstado_ctrllr -> guardar_orden()
    //ORDEN POR CICLO 
    public function insert_orden($OrtNum, $OrtDes, $OrtObs, $OrtPrd, $OrtFchEj, $OrtFchEm, $OrtConId, $OrtEstId, $OrtActId, $OrtUsrEn, $OrtCntId, $detalle, $idCiclo, $nivel , $supervision , $tamanioMuestra ) {
        $this->db->trans_start();
        
        $query = $this->db->query('INSERT INTO "Orden_trabajo"("OrtNum","OrtDes","OrtObs","OrtFchEj","OrtFchEn","OrtFchEm","OrtEli","OrtFchRg","OrtFchAc","OrtEstId","OrtConId","OrtActId","OrtPrdId","OrtNgrId","OrtUsrEn","OrtCntId") '
                . 'VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);', array(strval($OrtNum), strval($OrtDes), strval($OrtObs), $OrtFchEj, date("Y-m-d H:i:s"), $OrtFchEm, false, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), intval($OrtEstId), intval($OrtConId), intval($OrtActId), $OrtPrd, $nivel,intval($OrtUsrEn), intval($OrtCntId)));
        $idOrden = $this->db->insert_id();
        $periodo = $this->Periodo_model->get_one_periodo($OrtPrd);
        $lectAcumuladas = 0; // el offset
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $SolTipId = $this->Parametro_model->get_one_detParam_x_codigo('sol_reg')['DprId'];
        foreach ($detalle as $nroDia => $dia) {
                $lecturistas = $dia['lecturistas'];
                foreach ($dia['subordenes'] as $nroSuborden => $suborden) {
                    $fechaEj = $dia['fecha'];
                    if ($dia['aleatorio']) {
                        //$idLecturista = array_rand($lecturistas);
                        //unset($lecturistas[$idLecturista]);
                        $idLecturista = null;
                    }else{
                        //$idLecturista = $suborden['idLecturista'];
                        $idLecturista = null;
                    }
                    $query = $this->insert_suborden($nroSuborden,$fechaEj,$SolTipId,$idOrden,$idLecturista,$idCiclo , $suborden['cantidad'] );
                    $idSuborden = $this->db->insert_id();
                    $mes = $periodo['PrdOrd']==1?12:$periodo['PrdOrd']-1; // cambiar el 12 por un metodo que devuelva la ultima orden
                    $anio = $mes==12?$periodo['PrdAni']-1:$periodo['PrdAni'];
                    $conexiones = $this->Catastro_model->get_conexiones_x_ciclo_x_suborden_batch($idSuborden, $idCiclo, $suborden['cantidad'], $nroSuborden, $anio, $mes, $OrtCntId, $LecTipId , $lectAcumuladas);
                    $lecturas = $this->db->insert_batch('"Lectura"', $conexiones);
                    $lectAcumuladas += $suborden['cantidad'];
                }
        }
        
        //SI SE INSERTO EN LECTURAS
        if ($this->db->affected_rows() > 0) {
            //INSERTAR SUBORDEN DE SUPERVISION
            if (isset($supervision)) {

                $nroSubordenSupervision = $nroSuborden + 1;
                $idSupervisor  = $this->Lecturista_model->get_lecturista_supervisor($OrtCntId)['LtaId'];

                $fecha = str_replace("/", "-", $fechaEj);
                $nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
                $fechaEjSubordenSupervision = date ( 'd/m/Y' , $nuevafecha );

                $SolTipId = $this->Parametro_model->get_one_detParam_x_codigo('sol_sup')['DprId'];
                $query = $this->insert_suborden($nroSubordenSupervision,$fechaEjSubordenSupervision,$SolTipId,$idOrden,$idSupervisor,$idCiclo , $tamanioMuestra );
                $idSuborden = $this->db->insert_id();
                $mes = $periodo['PrdOrd']==1?12:$periodo['PrdOrd']-1; // cambiar el 12 por un metodo que devuelva la ultima orden
                $anio = $mes==12?$periodo['PrdAni']-1:$periodo['PrdAni'];
                
                $lecturas = array_column($this->Lectura_model->get_lecturas_x_orden($idOrden), 'LecId');
                $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_sup')['DprId'];
                for ($i = 0; $i < $tamanioMuestra; $i++) 
                {
                    $rand=array_rand($lecturas);
                    $idLectura = $lecturas[$rand];
                    $resultado = $this->Lectura_model->get_one_lectura($idLectura);
                    $query = $this->db->query('INSERT INTO "Lectura" ("LecCodFc","LecNom","LecUrb","LecCal","LecMun","LecMed",'
                            . '"LecInt","LecEli","LecFchRg","LecFchAc","LecCxnId","LecCntId","LecCsmPr","LecAnt","LecDis","LecTipId","LecSolId","LecGprId") '
                            . 'VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)' , array( $resultado['LecCodFc'], $resultado['LecNom'], $resultado['LecUrb'],  $resultado['LecCal'], $resultado['LecMun'], $resultado['LecMed'], 0, FALSE,date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $resultado['LecCxnId'], $resultado['LecCntId'] , $resultado['LecCsmPr'] , $resultado['LecAnt'] , $resultado['LecDis']  , $LecTipId , $idSuborden , $resultado['LecGprId'] ));
                    unset($lecturas[$rand]);
                }
                
                if ($this->db->affected_rows() > 0 ) {
                    $this->db->trans_complete();
                    return TRUE; 
                } else {
                    log_message('error', $this->campaign_id.' :: No se puede generar las Lecturas de Supervision.');
                    log_message('error', $this->ci->db->_error_message());
                    return FALSE; 
                }
            }  
        }
        else {
            log_message('error', $this->campaign_id.' :: No se puede generar las Lecturas de Supervision.');
            log_message('error', $this->ci->db->_error_message());
            return FALSE; 
        }
    }
    
    // this->insert_orden
    public function insert_suborden($nroSuborden, $fechaEj, $SolTipId , $idOrden, $idLecturista, $idCiclo , $SolMetPl ){
        $query = $this->db->query('INSERT INTO "Suborden_lectura" ("SolCod", "SolFchEj", "SolEli", "SolFchRg" , "SolFchAc", "SolTipId" , "SolOrtId" , "SolLtaId", "SolGprId" , "SolMetPl" , "SolMetEj" )'
                . ' VALUES (?,?,?,?,?,?,?,?,?,?,?)'
                , array($nroSuborden, $fechaEj, false, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $SolTipId , $idOrden, $idLecturista, $idCiclo , $SolMetPl , 0 ));
        return $query;
    }
    
    // OTTomaEstado_ctrllr -> orden_editar_guardar
    public function update_orden_trabajo_ciclos($OrtNum, $OrtDes, $OrtObs, $OrtFchEm, $OrtUsrEn, $idOrdenTrabajo) {
        $query = $this->db->query('UPDATE "Orden_trabajo" SET "OrtNum" = ?, "OrtDes" = ?, "OrtObs" = ?, "OrtFchEm" = ?, "OrtFchAc" = ?, "OrtUsrEn" = ? WHERE "OrtId" = ?;'
                , array(strval($OrtNum), strval($OrtDes), strval($OrtObs), $OrtFchEm, date("Y-m-d H:i:s"), intval($OrtUsrEn), intval($idOrdenTrabajo)));
        return $query;
    }
    // OTTomaEstado_ctrllr -> orden_eliminar
    public function delete_orden($idOrdenTrabajo) {
        $query = $this->db->query('UPDATE "Orden_trabajo" SET "OrtEli" = TRUE, "OrtFchAc" = ? '
                . 'WHERE "OrtId" = ?', array(date("Y-m-d H:i:s"), $idOrdenTrabajo));
        return $query;
    }
    
// <editor-fold defaultstate="collapsed" desc="VALORIZACION">
    //valorizacion_model -> insert_valorizacion
    public function insert_orden_subactividad($cantEj, $precioUni, $montoEj, $idOrden, $idSubActividad, $idValOrdenSub) {
        $query = $this->db->query('INSERT INTO "Orden_trabajo_Subactividad" ("OtsMetEj", "OtsPreUn", "OtsMonEj", "OtsEli", "OtsFchRg" , "OtsFchAc", "OtsOrtId" , "OtsSacId", "OtsVloId" )'
                . ' VALUES (?,?,?,?,?,?,?,?,?);'
                , array($cantEj, $precioUni, $montoEj, false, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idOrden, $idSubActividad, $idValOrdenSub));
        return $this->db->insert_id();
    }

    //valorizacion_model -> delete_valorizacion
    public function delete_orden_subactividad_x_valorizacion($valSub) {
        $query = $this->db->query('UPDATE "Orden_trabajo_Subactividad" SET "OtsEli" = TRUE, "OtsFchAc" = ? '
                . 'WHERE "OtsVloId" = ?', array(date("Y-m-d H:i:s"), $valSub));
        return $query;
    }

    //valorizacion_model -> insert_valorizacion
    //valorizacion_model -> valorizacion_orden
    public function get_descuento_x_idOrden($idOrdenTrabajo) {
        $query = $this->db->query('SELECT SUM("PnzMon") as "Descuento" '
                . 'FROM "Penalizacion" WHERE "PnzEli" = FALSE AND "PnzOrtId" = ?;', array($idOrdenTrabajo));
        return $query->row_array()['Descuento'];
    }

    // valorizacion_model -> insert_valorizacion
    public function get_all_orden_x_periodo_facturacion($idActividad, $fechaIn, $fechaFn, $idContratante) {
        $query = $this->db->query('SELECT DISTINCT or1.* FROM "Orden_trabajo" AS or1 WHERE or1."OrtActId" = ? '
                . 'AND or1."OrtFchEj" BETWEEN ? AND ? AND or1."OrtId" NOT IN (SELECT DISTINCT or2."OrtId" '
                . 'FROM "Orden_trabajo_Subactividad" JOIN "Valorizacion_Orden_Subactividad" ON "OtsVloId" ="VloId" '
                . 'JOIN "Valorizacion" ON "VloVlrId" = "VlrId" JOIN "Orden_trabajo" AS or2 ON or2."OrtId" = "OtsOrtId" '
                . 'WHERE or2."OrtActId" = ? AND or2."OrtFchEj" BETWEEN ? AND ? AND "VlrEli" = FALSE) ', array($idActividad, $fechaIn, $fechaFn,
            $idActividad, $fechaIn, $fechaFn));
        return $query->result_array();
    }

    // Valorizacion_ctrllr -> valorizacion_detalle
    public function get_all_orden_x_valorizacion($idValorizacion) {
        $query = $this->db->query('SELECT DISTINCT "Orden_trabajo".* FROM "Orden_trabajo", "Orden_trabajo_Subactividad",' .
                '"Valorizacion", "Valorizacion_Orden_Subactividad" WHERE "OtsOrtId" = "OrtId" AND "OtsVloId" = "VloId"' .
                ' AND "VloVlrId" = "VlrId" AND "VlrId" = ? AND "VlrEli" = false ORDER BY "OrtId"', array($idValorizacion));
        $ordenes = $query->result_array();
        foreach ($ordenes as $key => $orden) {
            //$cantidad = $this->Valorizacion_model->get_cantidad_orden_valorizacion($orden['OrtId']);
            //AGREGAR LA CANTIDAD
            $idTipoRegular = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
            $ordenes[$key]['cantPl'] = $this->Lectura_model->get_cant_lect_x_orden_x_tipo($orden['OrtId'] , $idTipoRegular);
            $ordenes[$key]['cantEj'] = $this->get_metrado_ejec_x_orden($orden['OrtId']);
            $ordenes[$key]['subTotal'] = $this->get_monto_ejec_x_orden($orden['OrtId']);
            $desc = $this->get_descuento_x_idOrden($orden['OrtId']);
            if(is_null($desc))
            {
                $ordenes[$key]['descuento'] = 0;
            }
            else{
                $ordenes[$key]['descuento'] = $this->get_descuento_x_idOrden($orden['OrtId']);
            }
            $ordenes[$key]['total'] = $ordenes[$key]['subTotal'] - $ordenes[$key]['descuento'];
        }
        return $ordenes;
    }

    // $this -> get_all_orden_x_valorizacion
    public function get_metrado_ejec_x_orden($idOrden) {
        $query = $this->db->query('SELECT SUM("OtsMetEj") as "metrado" '
                . 'FROM "Orden_trabajo_Subactividad" WHERE "OtsOrtId" = ? AND "OtsEli" = FALSE;', array($idOrden));
        return $query->row_array()['metrado'];
    }
    
    // $this -> get_all_orden_x_valorizacion
    public function get_monto_ejec_x_orden($idOrden) {
        $query = $this->db->query('SELECT SUM("OtsMonEj") as "monto" '
                . 'FROM "Orden_trabajo_Subactividad" WHERE "OtsOrtId" = ? AND "OtsEli" = FALSE;', array($idOrden));
        return $query->row_array()['monto'];
    }

    //Valorizacion_ctrllr -> valorizacion_ctrllr 
    public function get_orden_subactividad_x_orden($idOrden) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo_Subactividad" JOIN "Subactividad" ON "OtsSacId" = "SacId"'
                . ' WHERE "OtsOrtId" = ? AND "OtsEli" = FALSE ORDER BY "SacId" ASC;', array($idOrden));
        return $query->result_array();
    }
// </editor-fold>
    
}
