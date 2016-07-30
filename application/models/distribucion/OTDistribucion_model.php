<?php

class OTDistribucion_model extends CI_Model {
    var $idContratante;
    var $idActividad;
    function __construct() {
        parent::__construct();
        
        $this->load->model('distribucion/Distribucion_model');
        $this->load->model('distribucion/Distribuidor_model');
        
        $this->load->model('general/Periodo_model');
        $this->load->model('general/NivelGrupo_model');
        $this->load->model('general/Actividad_model');
        $this->load->model('general/SubActividad_model');
        
        $this->load->model('distribucion/ValorizacionDistribucion_model');
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
    
    
    //OTDistribucion_ctrllr -> avance_orden
    public function get_one_orden_trabajo_all_datos($idOrdenTrabajo) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo"
        JOIN "Actividad" ON "OrtActId" = "ActId"
        JOIN "Contrato" ON "OrtConId" = "ConId"
        JOIN "Contratante" ON "ConCntId" = "CntId"
        WHERE "OrtId" = ? AND "OrtEli" = FALSE', array($idOrdenTrabajo));
        //CANTIDAD DISTRIBUCIONES
        $idTipoRegular = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $cantidad = $this->Distribucion_model->get_cant_dist_x_orden_x_tipo($idOrdenTrabajo, $idTipoRegular);
        
        //AGREGAR AVANCE
        $validos = $this->Distribucion_model->get_cant_dist_ejec_x_orden($idOrdenTrabajo);
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
        $query = $this->db->query('SELECT "Periodo".* FROM "Periodo"'
                . 'WHERE "PrdEli" = FALSE AND "PrdId" = ? ;', array($idPeriodo));
        return $query->row_array();
    }
    
    public function get_ciclo_orden_trabajo($idOrdenTrabajo) {
        $query = $this->db->query('SELECT "Grupo_predio"."GprCod","SodGprId" FROM "Suborden_distribucion"
        JOIN "Grupo_predio" ON "SodGprId" = "GprId"
        WHERE "SodOrtId" = ? GROUP By "SodGprId","Grupo_predio"."GprCod"', array($idOrdenTrabajo));
        return $query->row_array();
    }
    
    
    //OTDistribucion -> orden_ver()
    public function get_one_orden_trabj_all_datos($idOrdenTrabajo) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" '
                . 'JOIN "Actividad" ON "OrtActId" = "ActId" '
                . 'JOIN "Contrato" ON "OrtConId" = "ConId" '
                . 'JOIN "Contratante" ON "ConCntId" = "CntId" '
                . 'WHERE "OrtId" = ? AND "OrtEli" = FALSE;', array($idOrdenTrabajo));
        $orden = $query->row_array();
        //AGREGAR LOS CICLOS
        $query2 = $this->db->query('SELECT DISTINCT "GprCod" FROM "Suborden_distribucion" JOIN "Grupo_predio" ON "SodGprId" = "GprId" WHERE "SodOrtId" = ?', array($orden['OrtId']));
        $ciclos = $query2->result_array();
        $orden['ciclos'] = implode(',', array_column($ciclos, 'GprCod'));
        //AGREGAR EL PERIODO
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
                . 'WHERE "UsrId" = ? AND "OrtEstId" = ? AND "OrtEli" = FALSE ORDER BY "OrtNum" ASC', array($idUsuario, $OrtEstId));
        return $query->result_array();
    }

    //OTDistribucion_ctrllr -> orden_listar()
    public function get_all_orden_trabajo_x_contratante($idContratante, $idActividad ) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" JOIN "Detalle_parametro" ON "OrtEstId" = "DprId" WHERE "OrtActId" = ? AND "OrtCntId" = ? AND "OrtEli" = FALSE ORDER BY "OrtFchRg" DESC', array($idActividad, $idContratante));
        $ordenes = $query->result_array();
        foreach ($ordenes as $key => $orden) {
            //AGREGAR LOS CICLOS
            $query2 = $this->db->query('SELECT DISTINCT "GprCod" FROM "Suborden_distribucion" JOIN "Grupo_predio" ON "SodGprId" = "GprId" WHERE "SodOrtId" = ?', array($orden['OrtId']));
            $ciclos = $query2->result_array();
            $ordenes[$key]['nivel_comercial'] = $this->NivelGrupo_model->get_one_nivelGrupo($orden['OrtNgrId']);
            $ordenes[$key]['ciclos'] = implode(', ', array_column($ciclos, 'GprCod'));
            //agregar el periodo
            $ordenes[$key]['periodo'] = $this->Periodo_model->get_one_periodo($orden['OrtPrdId']);
            
            //AGREGAR METRADO PLANIFICADO
            $idTipoRegular = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
            $ordenes[$key]['cantidad']['dbn_reg'] = $this->Distribucion_model->get_cant_distrib_x_orden_x_tipo($orden['OrtId'] , $idTipoRegular);
            $idTipoSupervision = $this->Parametro_model->get_one_detParam_x_codigo('dbn_sup')['DprId'];
            $ordenes[$key]['cantidad']['dbn_sup'] = $this->Distribucion_model->get_cant_distrib_x_orden_x_tipo($orden['OrtId'], $idTipoSupervision);

            //AGREGAR METRADO EJECUTADO REGULARES
            $validos = $this->Distribucion_model->get_cant_distrib_ejec_x_orden($orden['OrtId']);
            $ordenes[$key]['validos'] = $validos;
            $avance = 0;
            if ($ordenes[$key]['cantidad']['dbn_reg'] != 0) {
                $avance = round($validos * 100 / $ordenes[$key]['cantidad']['dbn_reg'], 2);
            }
            $ordenes[$key]['avance'] = $avance;
            
            //AGREGAR METRADO EJECUTADO SUPERVISION
            $validosSupervision = $this->Distribucion_model->get_cant_distrib_ejec_x_orden_x_tipo($orden['OrtId'],$idTipoSupervision);
            $ordenes[$key]['validosSupervision'] = $validosSupervision;
            $avanceSupervision = 0;
            if ($ordenes[$key]['cantidad']['dbn_sup'] != 0) {
                $avanceSupervision = round($validosSupervision * 100 / $ordenes[$key]['cantidad']['dbn_sup'], 2);
            }
            $ordenes[$key]['avanceSupervision'] = $avanceSupervision;
        }
        return $ordenes;
    }
    

    public function get_all_orden_trabajo_x_contratante_cerradas($idContratante, $idActividad ) {
        $idTipoCerrada = $this->Parametro_model->get_one_detParam_x_codigo('cer')['DprId'];
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" JOIN "Detalle_parametro" ON "OrtEstId" = "DprId" WHERE "OrtActId" = ? AND "OrtCntId" = ? AND "OrtEli" = FALSE AND "DprId" = ? ORDER BY "OrtFchRg" DESC', array($idActividad, $idContratante, $idTipoCerrada));
        $ordenes = $query->result_array();
        foreach ($ordenes as $key => $orden) {
            //AGREGAR LOS CICLOS
            $query2 = $this->db->query('SELECT DISTINCT "GprCod" FROM "Suborden_distribucion" JOIN "Grupo_predio" ON "SodGprId" = "GprId" WHERE "SodOrtId" = ?', array($orden['OrtId']));
            $ciclos = $query2->result_array();
            $ordenes[$key]['nivel_comercial'] = $this->NivelGrupo_model->get_one_nivelGrupo($orden['OrtNgrId']);
            $ordenes[$key]['ciclos'] = implode(', ', array_column($ciclos, 'GprCod'));
            //agregar el periodo
            $ordenes[$key]['periodo'] = $this->Periodo_model->get_one_periodo($orden['OrtPrdId']);

            //AGREGAR METRADO PLANIFICADO
            $idTipoRegular = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
            $ordenes[$key]['cantidad']['dbn_reg'] = $this->Distribucion_model->get_cant_distrib_x_orden_x_tipo($orden['OrtId'] , $idTipoRegular);

            $idTipoSupervision = $this->Parametro_model->get_one_detParam_x_codigo('dbn_sup')['DprId'];
            $ordenes[$key]['cantidad']['dbn_sup'] = $this->Distribucion_model->get_cant_distrib_x_orden_x_tipo($orden['OrtId'], $idTipoSupervision);

            //AGREGAR METRADO EJECUTADO
            $validos = $this->Distribucion_model->get_cant_distrib_ejec_x_orden($orden['OrtId']);
            $avance = 0;
            if ($ordenes[$key]['cantidad']['dbn_reg'] != 0) {
                $avance = round($validos * 100 / $ordenes[$key]['cantidad']['dbn_reg'], 2);
            }
            $ordenes[$key]['avance'] = $avance;
        }
        return $ordenes;
    }

    // OTDistribucion_ctrllr -> orden_listar
    public function get_all_orden_trabajo_temp($idContratante) {
        $query = $this->db->query('SELECT * FROM "Detalle_cron_lectura_periodo" '
                . 'JOIN "Cron_lectura_periodo" ON "DlpClpId" = "ClpId" '
                . 'JOIN "Grupo_predio" ON "DlpGprId" = "GprId" '
                . 'WHERE ("DlpFchEn" = CURRENT_DATE OR "DlpFchEn" = (CURRENT_DATE - 1) OR "DlpFchEn" = (CURRENT_DATE + 1)) AND "ClpCntId" = ? ORDER BY "DlpFchEn" ASC;', array($idContratante));
        $rows = $query->result_array();
        $ordenes = array();
        foreach ($rows as $key => $row) {
            $fechaEntrega = date('-m-Y', strtotime($row['DlpFchEn']));
            $this->load->library('utilitario_cls');
            $parts = explode('-', $fechaEntrega);
            $nombreMes = $this->utilitario_cls->nombreMes(intval($parts[1]) + 1);
            $fechaToma = date('d-m-Y', strtotime($row['DlpFchTm']));
            //$ordenes[$key] = array('OrtNum'=>$fechaEntrega,'OrtDes'=>$nombreMes.' / '.$parts[2], 'OrtFchEj'=>$fechaToma);
            $ordenes[$row['DlpFchTm']][$row['GprId']] = array('OrtNum' => $fechaEntrega, 'OrtDes' => $nombreMes . ' / ' . $parts[2], 'OrtFchEj' => $fechaToma, 'DlpId' => $row['DlpId']);
        }
        return $ordenes;
    }
    
    
    public function get_all_orden_trabajo_x_contratista($idContratista) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" JOIN "Contrato" ON "OrtConId" = "ConId" '
                . 'JOIN "Contratista" ON "ConCstId" = "CstId" '
                . 'WHERE "CstId" = ? AND "OrtEli" = FALSE ORDER BY "OrtNum" ASC', array($idContratista));
        return $query->result_array();
    }
    
    
    
    //Reporte_model -> get_ciclos_control
    public function get_fechas_ejecucion_x_ciclo_x_periodo($idPeriodo, $idContratante, $idCiclo) {
        $query = $this->db->query('SELECT DISTINCT "SodFchEj" FROM "Suborden_distribucion", "Orden_trabajo" , "Periodo" '
                . 'WHERE "SodOrtId" = "OrtId" AND "OrtPrdId" = "PrdId" AND "PrdId" = ? AND "SodGprId" = ? AND "OrtCntId" = ? ORDER BY "SodFchEj" ASC', array($idPeriodo, $idCiclo, $idContratante));
        $rows = $query->result_array();
        foreach ($rows as $key => $row) {
            $rows[$key]['SodFchEj'] = date('d/m/Y', strtotime($row['SodFchEj']));
        }
        $fechas = implode(',', array_column($rows, 'SodFchEj'));
        return $fechas;
    }
    
    
    // distribucion/orden/avance/(:num)
    // distribucion/Orden_avance_ctrllr -> avance_orden
    
    public function get_avance_x_orden($idOrden) {
        $ciclos = $this->Distribucion_model->get_ciclos_x_orden($idOrden);
        foreach ($ciclos as $key => $ciclo) {
            
            $ciclos[$key]['distribuidores'] = $this->Distribuidor_model->get_distribuidor_x_ciclo_x_orden($idOrden, $ciclo['GprCod']);
            
            $ciclos[$key]['cantDistribuidores'] = count($ciclos[$key]['distribuidores']);
            
            $ciclos[$key]['cantDistribuciones'] = $this->Distribucion_model->get_cant_distribucion_total_x_ciclo_x_orden($idOrden, $ciclo['GprCod']);
            
            $ciclos[$key]['cantDistribucionesEj'] = $this->Distribucion_model->get_cant_distribucion_ejec_x_ciclo_x_orden($idOrden, $ciclo['GprCod']);
            
            
            $ciclos[$key]['avance'] = round($ciclos[$key]['cantDistribucionesEj'] * 100 / $ciclos[$key]['cantDistribuciones'], 2);
            
            $ciclos[$key]['cantObs'] = $this->Distribucion_model->get_cant_obs_x_ciclo_x_orden($idOrden, $ciclo['GprCod']);
            
            foreach ($ciclos[$key]['distribuidores'] as $key2 => $distribuidor) {
                
                $ciclos[$key]['distribuidores'][$key2]['cantDistribuciones'] = $this->Distribucion_model->get_cant_distribucion_total_x_distribuidor_x_ciclo_x_orden($distribuidor['DbrId'], $idOrden, $ciclo['GprCod'], $distribuidor['SodFchEj']);
                
                $ciclos[$key]['distribuidores'][$key2]['cantDistribucionesEj'] = $this->Distribucion_model->get_cant_distribucion_ejec_x_distribuidor_x_ciclo_x_orden($distribuidor['DbrId'], $idOrden, $ciclo['GprCod'], $distribuidor['SodFchEj']);
                
                $ciclos[$key]['distribuidores'][$key2]['nombre'] = $distribuidor['UsrApePt'] . ' ' . $distribuidor['UsrApeMt'] . ' ' . $distribuidor['UsrNomPr'];
                
                if($ciclos[$key]['distribuidores'][$key2]['cantDistribuciones']==0){
                    $ciclos[$key]['distribuidores'][$key2]['avance'] = 0;
                } else {
                    $ciclos[$key]['distribuidores'][$key2]['avance'] = round($ciclos[$key]['distribuidores'][$key2]['cantDistribucionesEj'] * 100 / $ciclos[$key]['distribuidores'][$key2]['cantDistribuciones'], 2);
                }
                $ciclos[$key]['distribuidores'][$key2]['cantObs'] = $this->Distribucion_model->get_cant_obs_x_distribuidor_x_ciclo_x_orden($distribuidor['DbrId'], $idOrden, $ciclo['GprCod']);
            }
        }
        return $ciclos;
    }
    
    
    
    //OTDistribucion_ctrllr -> avance_suborden
    public function get_avance_x_suborden($idOrden, $idSubOrden) {
        
        $dirtribuciones = $this->Distribucion_model->get_distribuciones_x_suborden($idOrden, $idSubOrden);
        
        foreach ($dirtribuciones as $key => $dirtribucion) {
            $observaciones = $this->Distribucion_model->get_obs_x_distribucion($dirtribucion['DbnId']);
            $dirtribuciones[$key]['observaciones'] = $observaciones;
        }
        return $dirtribuciones;
    }
    
    
    
    //OTDistribucion_ctrllr -> guardar_orden()
    //ORDEN POR SUBCICLOS DE UN MISMO CICLO 
    public function insert_orden_nivel_inferior($OrtNum, $OrtDes, $OrtObs, $OrtPrd, $OrtFchEj, $OrtFchEm, $OrtConId, $OrtEstId, $OrtActId, $OrtUsrEn, $OrtCntId, $detalle, $idBuscarGruposPredio , $idCiclo, $nivel , $supervision , $tamanioMuestra ) {
        
        $this->db->trans_start();
        
        $query = $this->db->query('INSERT INTO "Orden_trabajo"("OrtNum","OrtDes","OrtObs","OrtFchEj","OrtFchEn","OrtFchEm","OrtEli","OrtFchRg","OrtFchAc","OrtEstId","OrtConId","OrtActId","OrtPrdId","OrtNgrId","OrtUsrEn","OrtCntId") '
                . 'VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array($OrtNum,$OrtDes,$OrtObs,$OrtFchEj,date("Y-m-d H:i:s"),$OrtFchEm,false,date("Y-m-d H:i:s"),date("Y-m-d H:i:s"),$OrtEstId,$OrtConId,$OrtActId,$OrtPrd,$nivel,$OrtUsrEn,$OrtCntId));
        $idOrden = $this->db->insert_id();
        $periodo = $this->Periodo_model->get_one_periodo($OrtPrd);
        
        $distribucionesAcumuladas = 0; // el offset
        
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $SolTipId = $this->Parametro_model->get_one_detParam_x_codigo('sol_reg')['DprId'];
        $DbnSacId = $this->SubActividad_model->get_one_subactividad_x_codigo('distribucion_continua')['SacId'];
        
        foreach ($detalle as $nroDia => $dia) {
                $lecturistas = $dia['lecturistas'];
                foreach ($dia['subordenes'] as $nroSuborden => $suborden) {
                    $fechaEj = $dia['fecha'];
                    if ($dia['aleatorio']) {
                        $idLecturista = null;
                    }else{
                        $idLecturista = null;
                    }
                    $query = $this->insert_suborden($nroSuborden, $fechaEj, $SolTipId , $idOrden, $idLecturista, $idBuscarGruposPredio , $suborden['cantidad'] );
                    $idSuborden = $this->db->insert_id();
                    $mes = $periodo['PrdOrd'] == 1 ? 12 : $periodo['PrdOrd'] - 1; // cambiar el 12 por un metodo que devuelva la ultima orden
                    $anio = $mes == 12 ? $periodo['PrdAni'] - 1 : $periodo['PrdAni'];
                    
                    $conexiones = $this->Catastro_model->get_conexiones_x_subciclos_x_suborden_batch_distribucion($idSuborden, $idCiclo , $suborden['cantidad'] , $nroSuborden, $anio, $mes, $OrtCntId, $DbnTipId , $distribucionesAcumuladas , $DbnSacId );
                    
                    $distribuciones = $this->db->insert_batch('"Distribucion"', $conexiones);
                    
                    $distribucionesAcumuladas += $suborden['cantidad'];
            }
        }
        
        //SI SE INSERTO EN DISTRIBUCION
        if ($this->db->affected_rows() > 0) {
            //INSERTAR SUBORDEN DE SUPERVISION
            if (isset($supervision)) {
                
                $nroSubordenSupervision = $nroSuborden + 1;
                $idSupervisor  = $this->Distribuidor_model->get_distribuidor_supervisor($OrtCntId)['DbrId'];

                $fecha = str_replace("/", "-", $fechaEj);
                $nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
                $fechaEjSubordenSupervision = date ( 'd/m/Y' , $nuevafecha );
                
                $SolTipId = $this->Parametro_model->get_one_detParam_x_codigo('sol_sup')['DprId'];
                $query = $this->insert_suborden($nroSubordenSupervision,$fechaEjSubordenSupervision,$SolTipId,$idOrden,$idSupervisor,$idBuscarGruposPredio , $tamanioMuestra );
                $idSuborden = $this->db->insert_id();
                $mes = $periodo['PrdOrd']==1?12:$periodo['PrdOrd']-1; // cambiar el 12 por un metodo que devuelva la ultima orden
                $anio = $mes==12?$periodo['PrdAni']-1:$periodo['PrdAni'];

                $distribuciones = array_column($this->Distribucion_model->get_distribuciones_x_orden($idOrden), 'DbnId');
                $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_sup')['DprId'];

                for ($i = 0; $i < $tamanioMuestra; $i++) 
                {
                    $rand=array_rand($distribuciones);
                    
                    $idDistribucion = $distribuciones[$rand];
                    
                    $resultado = $this->Distribucion_model->get_one_distribucion($idDistribucion);
                    
                    $query = $this->db->query('INSERT INTO "Distribucion" ("DbnCodFc","DbnNom","DbnUrb","DbnCal","DbnMun","DbnMed","DbnEli","DbnFchRg","DbnFchAc","DbnCxnId","DbnCntId","DbnTipId","DbnSodId","DbnGprId","DbnSacId") '
                            . 'VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)' , array( $resultado['DbnCodFc'], $resultado['DbnNom'], $resultado['DbnUrb'],  $resultado['DbnCal'], $resultado['DbnMun'], $resultado['DbnMed'],FALSE, date("Y-m-d H:i:s"),date("Y-m-d H:i:s"), $resultado['DbnCxnId'], $resultado['DbnCntId'] , $DbnTipId , $idSuborden , $resultado['DbnGprId'] , $resultado['DbnSacId'] ));
                    unset($distribuciones[$rand]);
                }
                
                if ($this->db->affected_rows() > 0 ) {
                    $this->db->trans_complete();
                    return TRUE;
                } else {
                    log_message('error', $this->campaign_id.' :: No se puede generar las Lecturas de Supervision.');
                    log_message('error', $this->ci->db->_error_message);
                    return FALSE;
                }
            }
        }
        
        else {
            log_message('error', $this->campaign_id.' :: No se puede generar las Lecturas de la Orden.');
            log_message('error', $this->ci->db->_error_message());
            return FALSE; 
        }
    }
    
    //OTDistribucion_ctrllr -> guardar_orden()
    //ORDEN POR CICLO 
    public function insert_orden($OrtNum, $OrtDes, $OrtObs, $OrtPrd, $OrtFchEj, $OrtFchEm, $OrtConId, $OrtEstId, $OrtActId, $OrtUsrEn, $OrtCntId, $detalle, $idCiclo, $nivel , $supervision , $tamanioMuestra ) {
        
        $this->db->trans_start();
        
        $query = $this->db->query('INSERT INTO "Orden_trabajo"("OrtNum","OrtDes","OrtObs","OrtFchEj","OrtFchEn","OrtFchEm","OrtEli","OrtFchRg","OrtFchAc","OrtEstId","OrtConId","OrtActId","OrtPrdId","OrtNgrId","OrtUsrEn","OrtCntId") '
                . 'VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);', array(strval($OrtNum), strval($OrtDes), strval($OrtObs), $OrtFchEj, date("Y-m-d H:i:s"), $OrtFchEm, false, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), intval($OrtEstId), intval($OrtConId), intval($OrtActId), $OrtPrd, $nivel,intval($OrtUsrEn), intval($OrtCntId)));
        $idOrden = $this->db->insert_id();
        $periodo = $this->Periodo_model->get_one_periodo($OrtPrd);
        $distribucionesAcumuladas = 0; // el offset
        
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_reg')['DprId'];
        $SolTipId = $this->Parametro_model->get_one_detParam_x_codigo('sol_reg')['DprId'];
        $DbnSacId = $this->SubActividad_model->get_one_subactividad_x_codigo('distribucion_continua')['SacId'];
        
        
        foreach ($detalle as $nroDia => $dia) {
                $lecturistas = $dia['lecturistas'];
                foreach ($dia['subordenes'] as $nroSuborden => $suborden) {
                    $fechaEj = $dia['fecha'];
                    if ($dia['aleatorio']) {
                        $idLecturista = null;
                    }else{
                        $idLecturista = null;
                    }
                    $query = $this->insert_suborden($nroSuborden,$fechaEj,$SolTipId,$idOrden,$idLecturista,$idCiclo , $suborden['cantidad'] );
                    $idSuborden = $this->db->insert_id();
                    $mes = $periodo['PrdOrd']==1?12:$periodo['PrdOrd']-1; // cambiar el 12 por un metodo que devuelva la ultima orden
                    $anio = $mes==12?$periodo['PrdAni']-1:$periodo['PrdAni'];
                    
                    $conexiones = $this->Catastro_model->get_conexiones_x_ciclo_x_suborden_batch_distribucion($idSuborden, $idCiclo, $suborden['cantidad'], $nroSuborden, $anio, $mes, $OrtCntId, $DbnTipId , $distribucionesAcumuladas , $DbnSacId );
                    
                    $distribuciones = $this->db->insert_batch('"Distribucion"', $conexiones);
                    $distribucionesAcumuladas += $suborden['cantidad'];
                }
        }
        
        //SI SE INSERTO EN DISTRIBUCION
        if ($this->db->affected_rows() > 0) {

            //INSERTAR SUBORDEN DE SUPERVISION
            if (isset($supervision)) {

                $nroSubordenSupervision = $nroSuborden + 1;
                $idSupervisor  = $this->Distribuidor_model->get_distribuidor_supervisor($OrtCntId)['DbrId'];

                $fecha = str_replace("/", "-", $fechaEj);
                $nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
                $fechaEjSubordenSupervision = date ( 'd/m/Y' , $nuevafecha );

                $SolTipId = $this->Parametro_model->get_one_detParam_x_codigo('sol_sup')['DprId'];
                
                $query = $this->insert_suborden($nroSubordenSupervision,$fechaEjSubordenSupervision,$SolTipId,$idOrden,$idSupervisor,$idCiclo , $tamanioMuestra );
                
                $idSuborden = $this->db->insert_id();
                $mes = $periodo['PrdOrd']==1?12:$periodo['PrdOrd']-1; // cambiar el 12 por un metodo que devuelva la ultima orden
                $anio = $mes==12?$periodo['PrdAni']-1:$periodo['PrdAni'];
                
                $distribuciones = array_column( $this->Distribucion_model->get_distribuciones_x_orden($idOrden) , 'DbnId');
                
                $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dbn_sup')['DprId'];
                
                for ($i = 0; $i < $tamanioMuestra; $i++) 
                {
                    $rand = array_rand($distribuciones);
                    $idDistribucion = $distribuciones[$rand];
                    
                    $resultado = $this->Distribucion_model->get_one_distribucion($idDistribucion);
                    
                    $query = $this->db->query('INSERT INTO "Distribucion" ("DbnCodFc","DbnNom","DbnUrb","DbnCal","DbnMun","DbnMed","DbnEli","DbnFchRg","DbnFchAc","DbnCxnId","DbnCntId","DbnTipId","DbnSodId","DbnGprId","DbnSacId") '
                            . 'VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)' , array( $resultado['DbnCodFc'], $resultado['DbnNom'], $resultado['DbnUrb'],  $resultado['DbnCal'], $resultado['DbnMun'], $resultado['DbnMed'], FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $resultado['DbnCxnId'], $resultado['DbnCntId'] , $DbnTipId , $idSuborden , $resultado['DbnGprId'] , $resultado['DbnSacId'] ));
                    unset($distribuciones[$rand]);
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
        $query = $this->db->query('INSERT INTO "Suborden_distribucion" ("SodCod", "SodFchEj", "SodEli", "SodFchRg" , "SodFchAc", "SodTipId" , "SodOrtId" , "SodDbrId", "SodGprId" , "SodMetPl" , "SodMetEj" )'
                . ' VALUES (?,?,?,?,?,?,?,?,?,?,?)'
                , array($nroSuborden, $fechaEj, false, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $SolTipId , $idOrden, $idLecturista, $idCiclo , $SolMetPl , 0 ));
        return $query;
    }
    
    
    // OTDistribucion_ctrllr -> orden_editar_guardar
    public function update_orden_trabajo_ciclos($OrtNum, $OrtDes, $OrtObs, $OrtFchEm, $OrtUsrEn, $idOrdenTrabajo) {
        $query = $this->db->query('UPDATE "Orden_trabajo" SET "OrtNum" = ?, "OrtDes" = ?, "OrtObs" = ?, "OrtFchEm" = ?, "OrtFchAc" = ?, "OrtUsrEn" = ? WHERE "OrtId" = ?;'
                , array(strval($OrtNum), strval($OrtDes), strval($OrtObs), $OrtFchEm, date("Y-m-d H:i:s"), intval($OrtUsrEn), intval($idOrdenTrabajo)));
        return $query;
    }
    
    // OTDistribucion_ctrllr -> orden_eliminar
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
