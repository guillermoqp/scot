<?php

class OTTomaEstado_ctrllr extends CI_Controller {

    var $idContratante;
    var $idActividad;

    public function __construct() {
        parent::__construct();
        $this->load->model('lectura/OTTomaEstado_model');
        $this->load->model('contratista/lectura/OTTomaEstado_cst_model');
        $this->load->model('general/Actividad_model');
        $this->load->model('general/Penalizacion_model');
        $this->load->model('general/Penalidad_model');
        $this->load->model('general/Periodo_model');
        $this->load->model('lectura/CronogramaLectura_model');
        $this->load->model('general/Contrato_model');
        $this->load->model('general/Contratista_model');
        $this->load->model('general/NivelGrupo_model');
        $this->load->model('sistema/Parametro_model');
        $this->load->model('acceso/Usuario_model');
        $this->load->model('catastro/Catastro_model');
        $this->load->model('lectura/Lecturista_model');
        $this->load->model('lectura/Lectura_model');
        $this->load->model('general/SubActividad_model');
        
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->load->library('utilitario_cls');
        $this->acceso_cls->isLogin();
        $this->data['menu']['padre'] = 'toma_estado';
        $this->data['menu']['hijo'] = 'ordenes';
        $this->data['proceso'] = 'Toma de Estado';
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->idContratante = $this->data['userdata']['contratante']['CntId'];
        $this->idActividad = $this->Actividad_model->get_id_actividad_x_codigo('toma_estado');
    }

// <editor-fold defaultstate="collapsed" desc="ORDENES LISTAR">
    public function orden_listar() {
        $this->acceso_cls->verificarPermiso('ver_orden_lectura');
        $idContrato = $this->validar_contrato_actividad($this->idActividad, $this->idContratante);
        if (isset($_SESSION['orden'])) {
            unset($_SESSION['orden']);
        }
        if (isset($_SESSION['ordenP']['detalle'])) {
            $_SESSION['ordenP']['detalle'] = array();
        }
        if (isset($_SESSION['ordenP'])) {
            unset($_SESSION['ordenP']);
        }
        $this->data['ordenesTrabajo'] = $this->OTTomaEstado_model->get_all_orden_trabajo_x_contratante($this->idContratante, $this->idActividad);
        $niveles = $this->NivelGrupo_model->get_niveles_cron($this->idContratante);
        foreach ($niveles as $nivel)
        {
            $cantidadSubniveles = $this->NivelGrupo_model->get_cant_subniveles($nivel['GprNgrId']);
            if($cantidadSubniveles == 0)
            {
                $ordenesNivelInf = $this->OTTomaEstado_model->get_all_orden_trabajo_temp($this->idContratante);
                if(isset($ordenesNivelSuperior))
                {
                    array_push($ordenesNivelSuperior, $ordenesNivelInf);
                }
                else 
                {
                    $ordenesNivelSuperior = $ordenesNivelInf;
                }
            }
            else
            {
                $ordenesNivelSuperior = $this->OTTomaEstado_model->get_all_orden_trabajo_temp_NivSup($this->idContratante);
            }
        }
        //por subciclo
        $this->data['ordenesTrabajoTemp'] = $ordenesNivelSuperior;
        
        //por ciclo
//        (SELECT * FROM "Detalle_cron_lectura_periodo" 
//        JOIN "Cron_lectura_periodo" ON "DlpClpId" = "ClpId"
//        JOIN "Grupo_predio" ON "DlpGprId" = "GprId"
//        JOIN "Nivel_grupo" ON "GprNgrId" = "NgrId"
//        WHERE ("DlpFchEn" = CURRENT_DATE OR "DlpFchEn" = (CURRENT_DATE - 1) OR "DlpFchEn" = (CURRENT_DATE + 1)) AND "ClpCntId" = 4 ORDER BY "DlpFchEn" ASC,"DlpId") EXCEPT 
//        (SELECT DISTINCT("Detalle_cron_lectura_periodo".*),"Cron_lectura_periodo".*,"Grupo_predio".*,"Nivel_grupo".*
//        FROM "Detalle_cron_lectura_periodo" 
//        JOIN "Cron_lectura_periodo" ON "DlpClpId" = "ClpId"
//        JOIN "Grupo_predio" ON "DlpGprId" = "GprId"
//        JOIN "Nivel_grupo" ON "GprNgrId" = "NgrId"
//        JOIN "Suborden_lectura" ON "SolGprId" = "DlpGprId"
//        WHERE ("DlpFchEn" = CURRENT_DATE OR "DlpFchEn" = (CURRENT_DATE - 1) OR "DlpFchEn" = (CURRENT_DATE + 1)) AND "ClpCntId" = 4 ORDER BY "DlpFchEn" ASC) ORDER BY "DlpFchEn" ASC
        
        $this->data['view'] = 'lectura/OTLectura_lista_view';
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', ''));
        $this->load->view('template/Master', $this->data);
    }

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="ORDEN NUEVO POR CICLO Y SUB CICLO">
    // this -> orden_nuevo
    // this -> guardar_orden
    public function validar_contrato_actividad($idActividad, $idContratante) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_orden_lectura');

        $contratosVigentes = $this->Contrato_model->get_contratos_x_contratante($idContratante);

        foreach ($contratosVigentes as $contratoVigente) {
            $actividadesContratadas = $this->Contrato_model->get_actividades_x_contrato_id($contratoVigente['ConId']);
            $idActividadesContratadas = array_column($actividadesContratadas, 'ActId');
            /*      VALIDAR CONTRATO VIGENTE        */
            if (in_array($idActividad, $idActividadesContratadas) && $contratoVigente['ConVig'] == true) {
                $idContrato = $contratoVigente['ConId'];
                return $idContrato;
            }
        }
        return FALSE;
    }
    
    public function get_tamanio_muestra_lecturas_supervision($poblacion) {
        $Z = floatval($this->Variable_model->get_one_variable_cod('z')['VarVal']);
        $muestra = round((pow(floatval($Z), 2) * 0.5 * 0.5 * intval($poblacion)) / (pow(0.05, 2) * (intval($poblacion) - 1) + pow(floatval($Z), 2) * 0.5 * 0.5));
        return $muestra;
    }

    // toma_estado/orden/nuevo
    public function orden_nuevo() {
        $this->acceso_cls->verificarPermiso('editar_orden_lectura');
        if (isset($_SESSION['ordenP'])) {
            unset($_SESSION['ordenP']);
        }
        if (isset($_SESSION['ordenD'])) {
            unset($_SESSION['ordenD']);
        }
        /*  VALIDAR QUE PUEDA GENERAR ORDENES DE TRABAJO SEGUN CONTRATO Y ACTIVIDAD CONTRATADA   */
        $idContrato = $this->validar_contrato_actividad($this->idActividad, $this->idContratante);
        if ($idContrato != FALSE) 
        {
            if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['nivel']) && !isset($_POST['btn_guardar'])) {
                $idNivel = $this->input->post('nivel');
                $this->limpiar_ciclo($idContrato);
            }
            
            if (isset($_SESSION['orden'])) {
                //ya hay datos de orden anterior
                if ($this->input->server('REQUEST_METHOD') == 'POST') {
                    $_SESSION['orden']['numero'] = $this->input->post('OrtNum');
                    $_SESSION['orden']['descripcion'] = $this->input->post('OrtDes');
                    $_SESSION['orden']['anio'] = $this->input->post('OrtAnio');
                    $_SESSION['orden']['periodo'] = $this->input->post('OrtPrd');
                    $_SESSION['orden']['observaciones'] = $this->input->post('OrtObs');
                   
                    if (is_null($this->input->post('gruposPredio'))) {
                        $nvels = $this->NivelGrupo_model->get_all_nivelGrupo($this->idContratante);
                        $idNvel = isset($idNivel) ? $idNivel : $nvels[0]['NgrId'];
                        $gruposPre =  $this->NivelGrupo_model->get_all_grupoPredio_x_nivelGrupo($idNvel);
                        $idGrupo = isset($idGrupo) ? $idGrupo : $gruposPre[0]['GprId'];
                        $gruposPredio = (int) $idGrupo;
                    }else {
                        $gruposPredio = $this->input->post('gruposPredio');
                    }
                    
                    if (is_array($gruposPredio)){
                        $_SESSION['orden']['gruposPredio'] = $gruposPredio;
                        $_SESSION['orden']['buscarGruposPredio'] = $this->input->post('buscarGruposPredio');
                        //$_SESSION['orden']['cicloNum'] = $_SESSION['orden']['gruposPredio'];
                    } else {
                        $_SESSION['orden']['gruposPredio'] = $gruposPredio;
                        //$_SESSION['orden']['cicloNum'] = $_SESSION['orden']['gruposPredio'];
                        $_SESSION['orden']['buscarGruposPredio'] = $this->input->post('buscarGruposPredio');
                        $this->data['grupoPredioSeleccionado'] = $this->NivelGrupo_model->get_one_grupoPredio($_SESSION['orden']['gruposPredio']);
                    }
                    
                    //$_SESSION['orden']['bloqueado'] = true;
                    $_SESSION['orden']['fchEj'] = $this->input->post('OrtFchEj');
                    $_SESSION['orden']['fchEm'] = $this->input->post('OrtFchEm');
                    $_SESSION['orden']['observacion'] = $this->input->post('OrtObs');
                    $_SESSION['orden']['supervision'] = !is_null($this->input->post('supervision'));
                    
                    if (isset($_POST['btn_guardar'])) {
                        //boton guardar orden
                        $this->guardar_orden();
                    } elseif (isset($_POST['eliminar'])) {
                        //boton eliminar de cada ciclo
                        $idCiclo = $_POST['eliminar'];
                        $this->eliminar_ciclo($idCiclo);
                    } elseif (isset($_POST['opcion'])) {
                        if (isset($_SESSION['orden']['gruposPredio'])) {
                            //cambio de algun campo fecha o Grupo Predio
                            $opcion = $this->input->post('opcion');
                            if (!is_array($_SESSION['orden']['gruposPredio'])) {
                                //SI INGRESO UN CICLO, O GRUPO PREDIO 
                                $SubciclosDeCiclo = count($this->NivelGrupo_model->get_all_grupoPredio_x_idGrupoPredio($_SESSION['orden']['gruposPredio']));
                                $NivelSubciclo = $this->NivelGrupo_model->get_nivel_inferior($_SESSION['orden']['idNivel']);
                                $idSubciclos = array_column($this->NivelGrupo_model->get_all_grupoPredio_x_periodo_x_nivelGrupo($_SESSION['orden']['gruposPredio'], $NivelSubciclo['NgrId'], $_SESSION['orden']['periodo']), 'GprId');
                                if (isset($idSubciclos) && is_array($idSubciclos))                                 {
                                    if ($SubciclosDeCiclo > count($idSubciclos))                                     {
                                        $this->session->set_flashdata('mensaje', array('error', 'Se Cargaron solo los Subciclos: ' . implode(",", $idSubciclos)));
                                        $_SESSION['orden']['gruposPredio'] = $idSubciclos;
                                    }
                                }
                            }
                            switch ($opcion) {
                                case 'gruposPredio':
                                    $this->limpiar_ciclo($idContrato);
                                    $this->insertar_ciclo($_SESSION['orden']['gruposPredio'], $idContrato);
                                    $_SESSION['orden']['bloqueado'] = true;
                                    break;
                                case 'fecha':
                                    $this->limpiar_ciclo($idContrato);
                                    $this->insertar_ciclo($_SESSION['orden']['gruposPredio'], $idContrato);
                                    $_SESSION['orden']['bloqueado'] = true;
                                    break;
                                case 'periodo':
                                    $this->limpiar_ciclo($idContrato);
                                    $this->insertar_ciclo($_SESSION['orden']['gruposPredio'], $idContrato);
                                    $_SESSION['orden']['bloqueado'] = true;
                                    break;
                                default:
                                    break;
                            }
                        } else {
                            $this->limpiar_ciclo($idContrato);
                        }
                        //$this->cambioFechaEj($idContratante, $idContrato, $_SESSION['orden']['fchEj']);
                    }
                }
            } else {
                //ES UNA NUEVA ORDEN DE TRABAJO
                $_SESSION['orden']['detalle'] = array();
                //numero de lecturas que puede hacer un lecturista segun contrato
                $rendimientosPorActividad = $this->Contrato_model->get_rendimientos_x_contrato_x_actividad($idContrato, $this->idActividad);
                $contrato = $this->Contrato_model->get_one_contrato($idContrato);
                $_SESSION['orden']['rendimiento'] = implode(",", max($rendimientosPorActividad));
                //$_SESSION['orden']['lecturistas'] = $this->Lecturista_model->get_lecturista_disponibles($this->idContratante, $idContrato, $fechaEj);
                //nro de lecturistas activos registrado
                $_SESSION['orden']['totalLecturistas'] = $this->Lecturista_model->get_cant_lecturista_x_contrato($idContrato);
                $_SESSION['orden']['lecturasAcumulado'] = 0;
                $_SESSION['orden']['subordenesAcumulado'] = 0;
                $_SESSION['orden']['lecturistasDisponibles'] = $_SESSION['orden']['totalLecturistas'];
                $_SESSION['orden']['numero'] = date('Y');
                $_SESSION['orden']['descripcion'] = '';
                $_SESSION['orden']['observaciones'] = '';
                $_SESSION['orden']['anio'] = date('Y');
                $periods = $this->Periodo_model->get_all_periodo($this->idActividad, $_SESSION['orden']['anio'], $this->idContratante);
                $_SESSION['orden']['periodo'] = (int) $periods[0]['PrdId'];
                $nivls = $this->NivelGrupo_model->get_all_nivelGrupo($this->idContratante);
                $idNvel = isset($idNvel) ? $idNvel : $nivls[0]['NgrId'];
                $gruposPre =  $this->NivelGrupo_model->get_all_grupoPredio_x_nivelGrupo($idNvel);
                $idGrupo = isset($idGrupo) ? $idGrupo : $gruposPre[0]['GprId'];
                $_SESSION['orden']['gruposPredio'] = (int) $idGrupo;
                //$_SESSION['orden']['cicloNum'] = $_SESSION['orden']['gruposPredio'];
                $_SESSION['orden']['fchEj'] = date('d-m-Y', strtotime('+1 day', time()));
                $entregables = $this->Usuario_model->get_recepcion_orden($contrato['ConCstId'], $this->idContratante);
                $_SESSION['orden']['entregado'] = implode(', ', $entregables);
                $_SESSION['orden']['supervision'] = TRUE;
            }
            // PROFILER
            //$this->output->enable_profiler(TRUE);
            
            $this->data['anios'] = $this->CronogramaLectura_model->get_anios($this->idContratante);
            if (empty($this->data['anios'])) {
                $this->session->set_flashdata('mensaje', array('error', 'No existe Cronograma Anual'));
                redirect(base_url() . 'toma_estado/ordenes');
            }
            $this->data['perio'] = $this->OTTomaEstado_model->get_periodo($_SESSION['orden']['periodo']);
            $this->data['periodos'] = $this->Periodo_model->get_all_periodo($this->idActividad, $_SESSION['orden']['anio'], $this->idContratante);
            $niveles = $this->NivelGrupo_model->get_all_nivelGrupo($this->idContratante);

            $this->data['niveles'] = $niveles;
            $idNivel = isset($idNivel) ? $idNivel : $niveles[0]['NgrId'];
            $_SESSION['orden']['idNivel'] = (int) $idNivel;
            $this->data['idNivel'] = $idNivel;

            $cantidadSubniveles = $this->NivelGrupo_model->get_cant_subniveles($idNivel);
            //ES EL ULTIMO NIVEL Y NO TIENE MAS SUBNIVELES
            if ($cantidadSubniveles == 0 && !is_array($_SESSION['orden']['buscarGruposPredio'])) {
                $this->data['esUltimoNivel'] = true;

                $this->data['nivelCiclo'] = $this->NivelGrupo_model->get_nivel_superior($idNivel);
                $this->data['gruposPredio'] = $this->NivelGrupo_model->get_all_grupoPredio_x_nivelGrupo($this->data['nivelCiclo']['NgrId']);
                if (is_null($this->input->post('buscarGruposPredio'))) {
                    $nivels = $this->NivelGrupo_model->get_all_nivelGrupo($this->idContratante);
                    $idNvl = isset($idNvl) ? $idNvl : $nivels[0]['NgrId'];
                    $buscarGruposPredio = (int) $idNvl;
                } else {
                    $buscarGruposPredio = $this->input->post('buscarGruposPredio');
                    //$_SESSION['orden']['cicloNum'] = $_SESSION['orden']['buscarGruposPredio'];
                }
                if (isset($buscarGruposPredio)){

                    $_SESSION['orden']['buscarGruposPredio'] = $buscarGruposPredio;
                    //$_SESSION['orden']['cicloNum'] = $_SESSION['orden']['buscarGruposPredio'];
                    //verificar por ciclo
                    $nivelSuperior = $this->NivelGrupo_model->get_nivel_superior($idNivel)['NgrId'];
                    $Subcls = array_column($this->NivelGrupo_model->get_all_grupoPredio_x_periodo_x_nivelGrupo($_SESSION['orden']['buscarGruposPredio'], $nivelSuperior, $_SESSION['orden']['periodo']), 'GprId');
                    if (empty($Subcls)) {
                        $idSubciclos = array_column($this->NivelGrupo_model->get_all_grupoPredio_x_periodo_x_nivelGrupo($_SESSION['orden']['buscarGruposPredio'], $nivelSuperior, $_SESSION['orden']['periodo']), 'GprId');
                    }                     else {
                        //verificar por subciclo
                        $idSubciclos = array_column($this->NivelGrupo_model->get_all_grupoPredio_x_periodo_x_nivelGrupo($_SESSION['orden']['buscarGruposPredio'], $idNivel, $_SESSION['orden']['periodo']), 'GprId');
                    }
                    if (isset($idSubciclos) && is_array($idSubciclos)) {
                        $subciclos = array();
                        foreach ($idSubciclos as $idSubciclo) {
                            $subciclo = $this->NivelGrupo_model->get_one_gprPredio($idSubciclo);
                            array_push($subciclos, $subciclo);
                        }
                        $this->data['subciclos'] = $subciclos;

                    }                     else                     {
                        $this->data['subciclos'] = $this->NivelGrupo_model->get_all_grupoPredio_x_idGrupoPredio($_SESSION['orden']['buscarGruposPredio']);
                    }
                }                 else                 {
                    $this->data['subciclos'] = $this->NivelGrupo_model->get_all_grupoPredio_x_idGrupoPredio($this->data['gruposPredio'][0]['GprId']);
                }
            }             else if ($cantidadSubniveles > 0)             {
                $this->data['gruposPredio'] = $this->NivelGrupo_model->get_all_grupoPredio_x_nivelGrupo($idNivel);
            }
            $_SESSION['orden']['tamanioMuestra'] = $this->get_tamanio_muestra_lecturas_supervision($_SESSION['orden']['lecturasAcumulado']);

            $Ordenes = $this->OTTomaEstado_model->get_ordenes_trabajo_x_contrato_x_anio($this->idActividad, $idContrato, date('Y'));
            if (empty($Ordenes)) {
                $codOrden = str_pad(1, 6, '0', STR_PAD_LEFT);
            }else {
                $codOrden = str_pad(count($Ordenes) + 1, 6, '0', STR_PAD_LEFT);
            }
            $_SESSION['orden']['numero'] = date('Y') . $codOrden;
            
            $this->data['accion'] = 'nuevo';
            $this->data['view'] = 'lectura/OTLectura_uno_view';
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Registrar Orden de Trabajo', ''));
            $this->load->view('template/Master', $this->data);
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'No tiene esta Actividad Contratada'));
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            redirect(base_url() . 'toma_estado/ordenes');
        }
    }

    // $this -> orden_nuevo
    public function guardar_orden() {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('OrtNum', 'Nombre', 'required');
        $this->form_validation->set_rules('OrtAnio', 'Año', 'required');
        $this->form_validation->set_rules('OrtPrd', 'Periodo', 'required');
        $this->form_validation->set_rules('OrtFchEj', 'Fecha Ejecución', 'required');
        $this->form_validation->set_rules('OrtFchEm', 'Fecha Recepción', 'required');
        $this->form_validation->set_rules('nivel', 'Nivel Comercial', 'required');
        $gruposPredio = $this->input->post('gruposPredio');
        
        if (is_array($gruposPredio)){
            $this->form_validation->set_rules('gruposPredio[]', 'Grupos de Predios', 'required');
        }else{
            $this->form_validation->set_rules('gruposPredio', 'Grupos de Predios', 'required');
        }
        if ($this->form_validation->run() == TRUE) {
            $OrtNum = $this->input->post('OrtNum');
            $OrtEnt = $this->input->post('OrtEntr');
            $OrtDes = $this->input->post('OrtDes');
            $anio = $this->input->post('OrtAnio');
            $OrtPrd = $this->input->post('OrtPrd');
            $OrtFchEj = $this->input->post('OrtFchEj');
            $OrtFchEm = $this->input->post('OrtFchEm');
            $OrtObs = $this->input->post('OrtObs');
            $supervision = $this->input->post('supervision');
            $nivel = $this->input->post('nivel');
            $idCiclo = $this->input->post('gruposPredio');
            $OrtDes = $OrtEnt . "<br>" . $OrtDes;
            $OrtConId = $this->validar_contrato_actividad($this->idActividad, $this->idContratante);
            $OrtEstId = $this->Parametro_model->get_one_detParam_x_codigo('no_rec')['DprId']; //No Recibido - tabla : Detalle_parametro
            $OrtUsrEn = $this->data['userdata']['UsrId'];

            if (is_array($idCiclo)){
                $idBuscarGruposPredio = $this->input->post('buscarGruposPredio');
                $resultado = $this->OTTomaEstado_model->insert_orden_nivel_inferior($OrtNum, $OrtDes, $OrtObs, $OrtPrd, $OrtFchEj, $OrtFchEm, $OrtConId, $OrtEstId, $this->idActividad, $OrtUsrEn, $this->idContratante, $_SESSION['orden']['detalle'], $idBuscarGruposPredio, $idCiclo, $nivel, $supervision, $_SESSION['orden']['tamanioMuestra'] );
            }else {
                $resultado = $this->OTTomaEstado_model->insert_orden($OrtNum, $OrtDes, $OrtObs, $OrtPrd, $OrtFchEj, $OrtFchEm, $OrtConId, $OrtEstId, $this->idActividad, $OrtUsrEn, $this->idContratante, $_SESSION['orden']['detalle'], $idCiclo, $nivel, $supervision, $_SESSION['orden']['tamanioMuestra']  );
            }

            if ($resultado) {
                if (isset($_SESSION['ordenP'])) {
                    unset($_SESSION['ordenP']);
                }
                if (isset($_SESSION['orden'])) {
                    unset($_SESSION['orden']);
                }
                if (isset($_SESSION['ordenD'])) {
                    unset($_SESSION['ordenD']);
                }
                $this->session->set_flashdata('mensaje', array('success', 'La Orden de trabajo se ha registrado satisfactoriamente.'));
                redirect(base_url() . 'toma_estado/ordenes');
            }else {
                $this->session->set_flashdata('mensaje', array('error', 'La Orden de trabajo no se ha podido registrar.'));
                redirect(base_url() . 'toma_estado/orden/nuevo');
            }
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'Llene Correctamente los datos solicitados en el Formulario.'));
            redirect(base_url() . 'toma_estado/orden/nuevo');
        }
    }

    // $this -> orden_nuevo
    public function eliminar_ciclo($cicloCod) {
        if (isset($_SESSION['orden']['detalle'][$cicloCod])) {
            $_SESSION['orden']['lecturasAcumulado'] -= $_SESSION['orden']['detalle'][$cicloCod]['lecturasRealizar'];
            $_SESSION['orden']['lecturistasAcumulado'] -= $_SESSION['orden']['detalle'][$cicloCod]['lecturistasUtilizados'];
            $_SESSION['orden']['lecturistasDisponibles'] = $_SESSION['orden']['totalLecturistas'] - $_SESSION['orden']['lecturistasAcumulado'];
            //$_SESSION['orden']['lecturasMaximas'] = $_SESSION['orden']['rendimiento'] * $_SESSION['orden']['lecturistasDisponibles'];
            //unset($_SESSION['orden']['detalle'][$idgrupoPredio]);
            unset($_SESSION['orden']['detalle'][$cicloCod]);
        }
        redirect(base_url() . 'toma_estado/orden/nuevo');
    }
    
    //$this -> orden_nuevo_ciclos
    public function insertar_ciclo($idgrupoPredio, $idContrato) {
        //if (!isset($_SESSION['orden']['detalle'][$idgrupoPredio])||isset($_SESSION['orden'])) {
        //dentro del ciclo ingresado
        $idPeriodo = $_SESSION['orden']['periodo'];
        $periodo = $this->Periodo_model->get_one_periodo($idPeriodo);
        $conexionesTotales = 0;
        $lecturasRestantes = 0;
        if (is_array($idgrupoPredio))             {
            foreach ($idgrupoPredio as $idgrupoPredio2) {
                $conexionesTotales = $conexionesTotales + intval($this->Catastro_model->get_cant_conexiones_x_grupoPredio_x_mes($idgrupoPredio2, $periodo['PrdOrd'], $periodo['PrdAni']));
                $lecturasRestantes = intval($this->Lectura_model->get_cant_lecturas_restantes_x_grupoPredio($periodo['PrdId'], $idgrupoPredio2, $conexionesTotales, $this->idContratante));
            }
        } else {
            $conexionesTotales = $this->Catastro_model->get_cant_conexiones_x_grupoPredio_x_mes($idgrupoPredio, $periodo['PrdOrd'], $periodo['PrdAni']);
            $lecturasRestantes = $this->Lectura_model->get_cant_lecturas_restantes_x_grupoPredio($periodo['PrdId'], $idgrupoPredio, $conexionesTotales, $this->idContratante);
        }
        $filaInicio = $conexionesTotales - $lecturasRestantes;
        if ($conexionesTotales != 0) {
            if ($lecturasRestantes > 0) {
                $nroDia = 1;
                $nroSuborden = 1;
                while ($lecturasRestantes > 0) {
                    $parts = explode('-', $_SESSION['orden']['fchEj']);
                    $fecha1 = strtotime($parts[0] . '-' . $parts[1] . '-' . $parts[2]);
                    $fechaEj = date('d/m/Y', strtotime('+' . ($nroDia - 1) . ' day', $fecha1));
                    //array de lecturistas
                    $lecturistasDisponibles = $this->Lecturista_model->get_lecturista_disponibles($this->idContratante, $idContrato, $fechaEj);
                    if (count($lecturistasDisponibles) > 0) {
                        $lecturasMaximas = count($lecturistasDisponibles) * $_SESSION['orden']['rendimiento'];
                        //$_SESSION['orden']['detalle'][$idgrupoPredio] = $ciclo;
                        $lecturasDia = 0;
                        if ($lecturasRestantes <= $lecturasMaximas) {
                            //se puede hacer completo lo que falta del ciclo
                            $lecturasDia = $lecturasRestantes;
                        } else {
                            //solo se puede hacer una parte de lo que falta
                            $lecturasDia = $lecturasMaximas;
                        }
                        $_SESSION['orden']['detalle'][$nroDia]['lecturistas'] = array_column($lecturistasDisponibles, null, 'LtaId');
                        $_SESSION['orden']['detalle'][$nroDia]['lecturasRealizar'] = $lecturasDia;
                        $_SESSION['orden']['detalle'][$nroDia]['fecha'] = $fechaEj;
                        //$_SESSION['orden']['detalle'][$nroDia]['lecturasMaximas'] = $lecturasRestantes;
                        $cantSubordenes = ceil($lecturasDia / $_SESSION['orden']['rendimiento']);
                        $_SESSION['orden']['detalle'][$nroDia]['nroLecturistas'] = $cantSubordenes;
                        $_SESSION['orden']['lecturasAcumulado'] += $lecturasDia;
                        $_SESSION['orden']['subordenesAcumulado'] += $cantSubordenes;
                        //$_SESSION['orden']['lecturistasDisponibles'] = $_SESSION['orden']['totalLecturistas'] - $_SESSION['orden']['lecturistasAcumulado'];
                        //$_SESSION['orden']['lecturasMaximas'] = $_SESSION['orden']['rendimiento'] * $_SESSION['orden']['lecturistasDisponibles'];
                        if (!isset($_SESSION['orden']['detalle'][$nroDia]['aleatorio'])){
                            $_SESSION['orden']['detalle'][$nroDia]['aleatorio'] = true;
                        }
                        $_SESSION['orden']['detalle'][$nroDia]['conexionesTotales'] = $conexionesTotales;
                        $lecturasRestantes -= $lecturasDia;
                        //crear subordenes
                        $promedio = ceil($lecturasDia / $cantSubordenes);
                        for ($i = 0; $i < $cantSubordenes; $i++) {
                            if ($promedio <= $lecturasDia) {
                                $cantLecturas = $promedio;
                            } else {
                                $cantLecturas = $lecturasDia;
                            }
                            $_SESSION['orden']['detalle'][$nroDia]['subordenes'][$nroSuborden]['cantidad'] = $cantLecturas;
                            $_SESSION['orden']['detalle'][$nroDia]['subordenes'][$nroSuborden]['idLecturista'] = 0;
                            $nroSuborden++;
                            $lecturasDia -= $cantLecturas;
                        }
                        $nroDia++;
                    } else {
                        //no hay lecturistas disponibles
                        $this->session->set_flashdata('mensaje', array('error', 'No hay lecturistas disponibles para la fecha elegida.'));
                        $lecturasRestantes = 0;
                    }
                }
            } else {
                //ya se ha completado las lecturas para este ciclo
                $this->session->set_flashdata('mensaje', array('error', 'Ya se ha realizado una orden de trabajo para el Nivel Comercial seleccionado.'));
            }
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'No hay datos cargados para este periodo.'));
        }
    }

    //$this -> orden_nuevo_ciclos
    public function cambioFechaEj($idContratante, $idContrato, $fechaEj) {
        //elimina las subordenes actuales
        //$_SESSION['orden']['detalle'] = array();
        //actualizar numero de lecturistas
        $_SESSION['orden']['lecturistas'] = $this->Lecturista_model->get_lecturista_disponibles($idContratante, $idContrato, $fechaEj);
        $_SESSION['orden']['totalLecturistas'] = count($_SESSION['orden']['lecturistas']);
        $_SESSION['orden']['lecturasAcumulado'] = 0;
        $_SESSION['orden']['lecturistasAcumulado'] = 0;
        $_SESSION['orden']['lecturistasDisponibles'] = $_SESSION['orden']['totalLecturistas'];
    }

    // this-> orden_nuevo_detalle_ciclo()
    public function actualizar_ciclo($nroDia) {
        $aleatorio = $this->input->post('asignacion') == 0;
        $lecturistas = $_SESSION['orden']['detalle'][$nroDia]['lecturistas'];
        $subordenes = $_SESSION['orden']['detalle'][$nroDia]['subordenes'];
        $isOk = TRUE;
        if (!$aleatorio) {
            //CUANDO ES MANUAL
            foreach ($subordenes as $nroSuborden => $suborden) {
                if (isset($_POST['lecturista_' . $nroSuborden])) {
                    $idLecturista = $this->input->post('lecturista_' . $nroSuborden);
                    if ($idLecturista != 0) {
                        //SELECCIONO UN LECTURISTA DEL COMBO
                        if (isset($lecturistas[$idLecturista])) {
                            //AUN NO ESTA ASIGNADO
                            $subordenes[$nroSuborden]['idLecturista'] = $idLecturista;
                            unset($lecturistas[$idLecturista]);
                        } else {
                            //YA ESTA ASIGNADO Y SE REPITE
                            $lecturistas[$idLecturista] = 0;
                            $isOk = FALSE;
                        }
                    } else {
                        //NO SELECCIONO A UN LECTURISTA
                        $lecturistas[$idLecturista] = 0;
                        $isOk = FALSE;
                    }
                }
            }
            if ($isOk) {
                $_SESSION['orden']['detalle'][$nroDia]['subordenes'] = $subordenes;
                $_SESSION['orden']['detalle'][$nroDia]['aleatorio'] = FALSE;
                //$_SESSION['orden']['detalle'][$nroDia]['aleatorio'] = $aleatorio;
            }
            return $isOk;
        }
    }

    public function orden_limpiar() {
        if (isset($_SESSION['ordenP'])) {
            unset($_SESSION['ordenP']);
        }
        if (isset($_SESSION['orden'])) {
            unset($_SESSION['orden']);
        }
        redirect(base_url() . 'toma_estado/orden/nuevo');
    }
    
    // this -> orden_nuevo
    public function limpiar_ciclo($idContrato) {
        $_SESSION['orden']['detalle'] = array();
        //numero de lecturas que puede hacer un lecturista segun contrato
        $rendimientosPorActividad = $this->Contrato_model->get_rendimientos_x_contrato_x_actividad($idContrato, $this->idActividad);
        $contrato = $this->Contrato_model->get_one_contrato($idContrato);
        $_SESSION['orden']['rendimiento'] = implode(",", max($rendimientosPorActividad));
        //$_SESSION['orden']['lecturistas'] = $this->Lecturista_model->get_lecturista_disponibles($this->idContratante, $idContrato, $_SESSION['orden']['fchEj']);
        //nro de lecturistas activos registrado
        $_SESSION['orden']['totalLecturistas'] = $this->Lecturista_model->get_cant_lecturista_x_contrato($idContrato);
        $_SESSION['orden']['lecturasAcumulado'] = 0;
        $_SESSION['orden']['subordenesAcumulado'] = 0;
        $_SESSION['orden']['lecturistasDisponibles'] = $_SESSION['orden']['totalLecturistas'];
    }
    
    //toma_estado/orden/nuevo/detalle_ciclo/(:num)
    public function orden_nuevo_detalle_ciclo($nroDia) {
        //SI ES POST
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            //ES POST
            //METODO PARA ACTUALIZAR LOS CAMBIOS
            $isOk = $this->actualizar_ciclo($nroDia);
            if ($isOk) {
                $this->data['cerrar'] = TRUE;
            } else {
                $this->session->set_flashdata('mensaje2', array('error', 'Error al asignar lecturistas.'));
            }
        }
        $idPeriodo = $_SESSION['orden']['periodo'];
        $periodo = $this->Periodo_model->get_one_periodo($idPeriodo);
        $this->data['ciclo'] = $this->Catastro_model->get_one_grupoPredio_x_mes($nroDia, $periodo['PrdOrd'], $periodo['PrdAni']);
        $this->data['nroDia'] = $nroDia;
        $this->data['lecturistas'] = $_SESSION['orden']['detalle'][$nroDia]['lecturistas'];
        $this->data['subordenes'] = $_SESSION['orden']['detalle'][$nroDia]['subordenes'];
        $this->data['aleatorio'] = $_SESSION['orden']['detalle'][$nroDia]['aleatorio'];
        $this->data['view'] = 'lectura/OTTomaEstado_ciclos_detalle_view';
        $this->load->view('template/Reporte', $this->data);
    }
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="ORDENES PROGRAMADAS POR CRONOGRAMA PERIODO DE LECTURA">
//ORDENES PROGRAMADAS POR CRONOGRAMA DE LECTURA
   
    // toma_estado/orden/nuevo
    public function orden_nuevoP($idDetalleCronograma) {
        $this->acceso_cls->verificarPermiso('editar_orden_lectura');
//        if (isset($_SESSION['ordenP']['detalle'])) {
//            $_SESSION['ordenP']['detalle'] = array();
//        }
        if (isset($_SESSION['orden'])) {
            unset($_SESSION['orden']);
        }
        /*  VALIDAR QUE PUEDA GENERAR ORDENES DE TRABAJO SEGUN CONTRATO Y ACTIVIDAD CONTRATADA   */
        $idContrato = $this->validar_contrato_actividad($this->idActividad, $this->idContratante);
        $detCronograma = $this->CronogramaLectura_model->get_one_detalle_cronograma_periodo($idDetalleCronograma);
        if ($idContrato != FALSE) 
        {
            if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['nivel']) && !isset($_POST['btn_guardar'])) {
                $idNivel = $this->input->post('nivel');
                $this->limpiar_cicloP($idContrato);
            }
            if (isset($_SESSION['ordenP'])) {
                //ya hay datos de ordenP anterior
                if ($this->input->server('REQUEST_METHOD') == 'POST') {
                    $_SESSION['ordenP']['numero'] = $this->input->post('OrtNum');
                    $_SESSION['ordenP']['descripcion'] = $this->input->post('OrtDes');
                    //$_SESSION['ordenP']['anio'] = $this->input->post('OrtAnio');
                    $_SESSION['ordenP']['anio'] = $detCronograma['ClpAni'];
                    $_SESSION['ordenP']['periodo'] = (int) $detCronograma['ClpPrdId'];
                    $_SESSION['ordenP']['observaciones'] = $this->input->post('OrtObs');
                    if (is_null($this->input->post('gruposPredio'))) {
                        $gruposPredio = (int) $detCronograma['GprId'];
                    }else {
                        $gruposPredio = $this->input->post('gruposPredio');
                    }
                    if (is_array($gruposPredio)){
                        $_SESSION['ordenP']['gruposPredio'] = $gruposPredio;
                    } else {
                        $_SESSION['ordenP']['gruposPredio'] = $gruposPredio;
                        $_SESSION['ordenP']['buscarGruposPredio'] = $this->input->post('buscarGruposPredio');
                        $this->data['grupoPredioSeleccionado'] = $this->NivelGrupo_model->get_one_grupoPredio($_SESSION['ordenP']['gruposPredio']);
                    }
                    $_SESSION['ordenP']['fchEj'] = $this->input->post('OrtFchEj');
                    $_SESSION['ordenP']['fchEm'] = $this->input->post('OrtFchEm');
                    $_SESSION['ordenP']['observacion'] = $this->input->post('OrtObs');
                    $_SESSION['ordenP']['supervision'] = !is_null($this->input->post('supervision'));
                    if (isset($_POST['btn_guardar'])) {
                        //boton guardar orden
                        $this->guardar_ordenP($idDetalleCronograma);
                    } elseif (isset($_POST['eliminar'])) {
                        //boton eliminar de cada ciclo
                        $idCiclo = $_POST['eliminar'];
                        $this->eliminar_cicloP($idCiclo,$idDetalleCronograma);
                    } elseif (isset($_POST['opcion'])) {
                        if (isset($_SESSION['ordenP']['gruposPredio'])) {
                            //cambio de algun campo fecha o Grupo Predio
                            $opcion = $this->input->post('opcion');
                            if (!is_array($_SESSION['ordenP']['gruposPredio'])) {
                                //SI INGRESO UN CICLO, O GRUPO PREDIO 
                                //deberia buscar por fecha
                                $SubciclosDeCiclo = count($this->NivelGrupo_model->get_all_grupoPredio_x_idGrupoPredio($_SESSION['ordenP']['gruposPredio']));
                                $NivelSubciclo = $this->NivelGrupo_model->get_nivel_inferior($_SESSION['ordenP']['idNivel']);
                                $idSubciclos = array_column($this->NivelGrupo_model->get_all_grupoPredio_x_periodo_x_nivelGrupo($_SESSION['ordenP']['gruposPredio'], $NivelSubciclo['NgrId'], $_SESSION['ordenP']['periodo']), 'GprId');
                                if (isset($idSubciclos) && is_array($idSubciclos)) {
                                    if ($SubciclosDeCiclo > count($idSubciclos)) {
                                        $this->session->set_flashdata('mensaje', array('error', 'Se Cargaron solo los Subciclos: ' . implode(",", $idSubciclos)));
                                        $_SESSION['ordenP']['gruposPredio'] = $idSubciclos;
                                    }
                                }
                            }
                            switch ($opcion) {
                                case 'gruposPredio':
                                    $this->limpiar_cicloP($idContrato);
                                    $this->insertar_cicloP($_SESSION['ordenP']['gruposPredio'], $idContrato);
                                    $_SESSION['ordenP']['bloqueado'] = true;
                                    break;
                                case 'fecha':
                                    $this->limpiar_cicloP($idContrato);
                                    $this->insertar_cicloP($_SESSION['ordenP']['gruposPredio'], $idContrato);
                                    $_SESSION['ordenP']['bloqueado'] = true;
                                    break;
                                case 'periodo':
                                    $this->limpiar_cicloP($idContrato);
                                    $this->insertar_cicloP($_SESSION['ordenP']['gruposPredio'], $idContrato);
                                    $_SESSION['ordenP']['bloqueado'] = true;
                                    break;
                                default:
                                    break;
                            }
                        } else {
                            $this->limpiar_cicloP($idContrato);
                        }
                    }
                }
            } else {
                //ES UNA NUEVA ORDENP DE TRABAJO
                $_SESSION['ordenP']['detalle'] = array();
                //numero de lecturas que puede hacer un lecturista segun contrato
                $rendimientosPorActividad = $this->Contrato_model->get_rendimientos_x_contrato_x_actividad($idContrato, $this->idActividad);
                $contrato = $this->Contrato_model->get_one_contrato($idContrato);
                $_SESSION['ordenP']['rendimiento'] = implode(",", max($rendimientosPorActividad));
                //$_SESSION['ordenP']['lecturistas'] = $this->Lecturista_model->get_lecturista_disponibles($this->idContratante, $idContrato, $fechaEj);
                //nro de lecturistas activos registrado
                $_SESSION['ordenP']['totalLecturistas'] = $this->Lecturista_model->get_cant_lecturista_x_contrato($idContrato);
                $_SESSION['ordenP']['lecturasAcumulado'] = 0;
                $_SESSION['ordenP']['subordenesAcumulado'] = 0;
                $_SESSION['ordenP']['lecturistasDisponibles'] = $_SESSION['ordenP']['totalLecturistas'];
                $_SESSION['ordenP']['numero'] = date('Y');
                $_SESSION['ordenP']['descripcion'] = '';
                $_SESSION['ordenP']['observaciones'] = '';
                $_SESSION['ordenP']['anio'] = $detCronograma['ClpAni'];
                $_SESSION['ordenP']['periodo'] = (int) $detCronograma['ClpPrdId'];
                $_SESSION['ordenP']['buscarGruposPredio'] = $detCronograma['GprGprId'];
//                $nivls = $this->NivelGrupo_model->get_all_nivelGrupo($this->idContratante);
//                $idNivl = isset($idNivl) ? $idNivl : $nivls[0]['NgrId'];
                $nivelSuperior = $this->NivelGrupo_model->get_nivel_superior($detCronograma['ClpNgrId'])['NgrId'];
                $Subcls = array_column($this->NivelGrupo_model->get_all_grupoPredio_x_periodo_x_nivelGrupo($_SESSION['ordenP']['buscarGruposPredio'], $nivelSuperior, $_SESSION['ordenP']['periodo']), 'GprId');
                if (empty($Subcls)) {
                    $idSubciclos = array_column($this->NivelGrupo_model->get_all_grupoPredio_x_periodo_x_nivelGrupo_Planificados($_SESSION['ordenP']['buscarGruposPredio'], $nivelSuperior, $_SESSION['ordenP']['periodo'],$detCronograma['DlpFchTm']), 'GprId');
                } else {
                    //verificar por subciclo
                    $idSubciclos = array_column($this->NivelGrupo_model->get_all_grupoPredio_x_periodo_x_nivelGrupo_Planificados($_SESSION['ordenP']['buscarGruposPredio'], $detCronograma['ClpNgrId'], $_SESSION['ordenP']['periodo'],$detCronograma['DlpFchTm']), 'GprId');
                }
                if(is_null($_SESSION['ordenP']['buscarGruposPredio']))
                {
                    $_SESSION['ordenP']['gruposPredio'] = $detCronograma['GprId'];;
                }
                else
                {
                    $_SESSION['ordenP']['gruposPredio'] = $idSubciclos;
                }
                $fecha_ejecucion = strtotime($detCronograma['DlpFchTm']);
                $fecha_ejecucion = date("d-m-Y", $fecha_ejecucion);
                $_SESSION['ordenP']['fchEj'] = $fecha_ejecucion;
                $entregables = $this->Usuario_model->get_recepcion_orden($contrato['ConCstId'], $this->idContratante);
                $_SESSION['ordenP']['entregado'] = implode(', ', $entregables);
                $_SESSION['ordenP']['supervision'] = TRUE;
                if (!is_array($_SESSION['ordenP']['gruposPredio'])) {
                    //SI INGRESO UN CICLO, O GRUPO PREDIO
                    $SubciclosDeCiclo = count($this->NivelGrupo_model->get_all_grupoPredio_x_idGrupoPredio($_SESSION['ordenP']['gruposPredio']));
                    $NivelSubciclo = $this->NivelGrupo_model->get_nivel_inferior($detCronograma['ClpNgrId']);
                    $idSubciclos = array_column($this->NivelGrupo_model->get_all_grupoPredio_x_periodo_x_nivelGrupo($_SESSION['ordenP']['gruposPredio'], $NivelSubciclo['NgrId'], $_SESSION['ordenP']['periodo']), 'GprId');
                    if (isset($idSubciclos) && is_array($idSubciclos)) {
                        if ($SubciclosDeCiclo > count($idSubciclos)) {
                            $this->session->set_flashdata('mensaje', array('error', 'Se Cargaron solo los Subciclos con Id: ' . implode(",", $idSubciclos)));
                        }
                    }
                }
            }
            // PROFILER
            //$this->output->enable_profiler(TRUE);
            $this->data['anios'] = $this->CronogramaLectura_model->get_anios($this->idContratante);
            if (empty($this->data['anios'])) {
                $this->session->set_flashdata('mensaje', array('error', 'No existe Cronograma Anual'));
                redirect(base_url() . 'toma_estado/ordenes');
            }
            if (is_null($this->input->post('gruposPredio'))) {
                $fecha_ejecucion = strtotime($detCronograma['DlpFchTm']);
                $fecha_ejecucion = date("d-m-Y", $fecha_ejecucion);
                $_SESSION['ordenP']['fchEj'] = $fecha_ejecucion;
                $this->insertar_cicloP($_SESSION['ordenP']['gruposPredio'], $idContrato);
            }
            $this->data['perio'] = $this->OTTomaEstado_model->get_periodo($_SESSION['ordenP']['periodo']);
            $this->data['periodos'] = $this->Periodo_model->get_all_periodo($this->idActividad, $_SESSION['ordenP']['anio'], $this->idContratante);
            $niveles = $this->NivelGrupo_model->get_all_nivelGrupo($this->idContratante);
            $this->data['niveles'] = $niveles;
            $idNivel = isset($idNivel) ? $idNivel : $detCronograma['ClpNgrId'];
            $_SESSION['ordenP']['idNivel'] = (int) $detCronograma['ClpNgrId'];
            $this->data['idNivel'] = (int) $detCronograma['ClpNgrId'];
            $cantidadSubniveles = $this->NivelGrupo_model->get_cant_subniveles($detCronograma['ClpNgrId']);
            //ES EL ULTIMO NIVEL Y NO TIENE MAS SUBNIVELES
            if ($cantidadSubniveles == 0 && !is_array($_SESSION['ordenP']['buscarGruposPredio'])) {
                $this->data['esUltimoNivel'] = true;
                $this->data['nivelCiclo'] = $this->NivelGrupo_model->get_nivel_superior($detCronograma['ClpNgrId']);
                $this->data['gruposPredio'] = $this->NivelGrupo_model->get_all_grupoPredio_x_nivelGrupo($this->data['nivelCiclo']['NgrId']);
                if (is_null($this->input->post('buscarGruposPredio'))) {
                    $buscarGruposPredio = (int) $detCronograma['GprGprId'];
                } else {
                    $buscarGruposPredio = $this->input->post('buscarGruposPredio');
                }
                if (isset($buscarGruposPredio)){
                    $_SESSION['ordenP']['buscarGruposPredio'] = $buscarGruposPredio;
                    //verificar por ciclo
                    $nivelSuperior = $this->NivelGrupo_model->get_nivel_superior($detCronograma['ClpNgrId'])['NgrId'];
                    $Subcls = array_column($this->NivelGrupo_model->get_all_grupoPredio_x_periodo_x_nivelGrupo($_SESSION['ordenP']['buscarGruposPredio'], $nivelSuperior, $_SESSION['ordenP']['periodo']), 'GprId');
                    if (empty($Subcls)) {
                        $idSubciclos = array_column($this->NivelGrupo_model->get_all_grupoPredio_x_periodo_x_nivelGrupo_Planificados($_SESSION['ordenP']['buscarGruposPredio'], $nivelSuperior, $_SESSION['ordenP']['periodo'],$detCronograma['DlpFchTm']), 'GprId');
//                        $idSubciclos = array_column($this->NivelGrupo_model->get_all_grupoPredio_x_periodo_x_nivelGrupo($_SESSION['ordenP']['buscarGruposPredio'], $nivelSuperior, $_SESSION['ordenP']['periodo']), 'GprId');
                    } else {
                        //verificar por subciclo
//                        $idSubciclos = array_column($this->NivelGrupo_model->get_all_grupoPredio_x_periodo_x_nivelGrupo($_SESSION['ordenP']['buscarGruposPredio'], $idNivel, $_SESSION['ordenP']['periodo']), 'GprId');
                        $idSubciclos = array_column($this->NivelGrupo_model->get_all_grupoPredio_x_periodo_x_nivelGrupo_Planificados($_SESSION['ordenP']['buscarGruposPredio'], $idNivel, $_SESSION['ordenP']['periodo'],$detCronograma['DlpFchTm']), 'GprId');
                    }
                    $_SESSION['ordenP']['gruposPredio'] = $idSubciclos;
                    if (isset($idSubciclos) && is_array($idSubciclos)) {
                        $subciclos = array();
                        foreach ($idSubciclos as $idSubciclo) {
                            $subciclo = $this->NivelGrupo_model->get_one_gprPredio($idSubciclo);
                            array_push($subciclos, $subciclo);
                        }
                        $this->data['subciclos'] = $subciclos;

                    } else  {
                        $this->data['subciclos'] = $this->NivelGrupo_model->get_all_grupoPredio_x_idGrupoPredio($_SESSION['ordenP']['buscarGruposPredio']);
                    }
                }   else  {
                    $this->data['subciclos'] = $this->NivelGrupo_model->get_all_grupoPredio_x_idGrupoPredio($this->data['gruposPredio'][0]['GprId']);
                }
            } else if ($cantidadSubniveles > 0)             {
                $this->data['gruposPredio'] = $this->NivelGrupo_model->get_all_grupoPredio_x_nivelGrupo($detCronograma['ClpNgrId']);
            }
            $_SESSION['ordenP']['tamanioMuestra'] = $this->get_tamanio_muestra_lecturas_supervision($_SESSION['ordenP']['lecturasAcumulado']);

            $Ordenes = $this->OTTomaEstado_model->get_ordenes_trabajo_x_contrato_x_anio($this->idActividad, $idContrato, date('Y'));
            if (empty($Ordenes)) {
                $codOrden = str_pad(1, 6, '0', STR_PAD_LEFT);
            }else {
                $codOrden = str_pad(count($Ordenes) + 1, 6, '0', STR_PAD_LEFT);
            }
            $_SESSION['ordenP']['numero'] = date('Y') . $codOrden;
            $this->data['detCron'] = $detCronograma;
            $OrtNum = $this->input->post('OrtNum');
            $OrtDes = $this->input->post('OrtDes');
            $anio = $this->input->post('OrtAnio');
            $OrtPrd = $this->input->post('OrtPrd');
            $OrtFchEj = $this->input->post('OrtFchEj');
            $OrtFchEm = $this->input->post('OrtFchEm');
            $OrtObs = $this->input->post('OrtObs');
            $supervision = $this->input->post('supervision');
            $nivel = $this->input->post('nivel');
            $idCiclo = $this->input->post('gruposPredio');
            $this->data['supervision'] = $supervision;
            $this->data['ciclo'] = $idCiclo;
            $this->data['todo'] = "Num: ".$OrtNum."Des: ".$OrtDes."Anio: ".$anio."Prd: ".$OrtPrd."Ej: ".$OrtFchEj."Em: ".$OrtFchEm."Obs: ".$OrtObs."Nivel: ".$nivel;
            $this->data['accion'] = 'nuevo';
            $this->data['view'] = 'lectura/OTLectura_uno_Planificadas_view';
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Registrar Orden de Trabajo', ''));
            $this->load->view('template/Master', $this->data);
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'No tiene esta Actividad Contratada'));
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            redirect(base_url() . 'toma_estado/ordenes');
        }
    }
    
    public function guardar_ordenP($idDetalleCronograma) {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('OrtNum', 'Nombre', 'required');
        $this->form_validation->set_rules('OrtAnio', 'Año', 'required');
        $this->form_validation->set_rules('OrtPrd', 'Periodo', 'required');
        $this->form_validation->set_rules('OrtFchEj', 'Fecha Ejecución', 'required');
        $this->form_validation->set_rules('OrtFchEm', 'Fecha Recepción', 'required');
        $this->form_validation->set_rules('nivel', 'Nivel Comercial', 'required');
        $gruposPredio = $this->input->post('gruposPredio');
        
        if (is_array($gruposPredio)){
            $this->form_validation->set_rules('gruposPredio[]', 'Grupos de Predios', 'required');
        }else{
            $this->form_validation->set_rules('gruposPredio', 'Grupos de Predios', 'required');
        }
        if ($this->form_validation->run() == TRUE) {
            $OrtNum = $this->input->post('OrtNum');
            $OrtEnt = $this->input->post('OrtEntr');
            $OrtDes = $this->input->post('OrtDes');
            $anio = $this->input->post('OrtAnio');
            $OrtPrd = $this->input->post('OrtPrd');
            $OrtFchEj = $this->input->post('OrtFchEj');
            $OrtFchEm = $this->input->post('OrtFchEm');
            $OrtObs = $this->input->post('OrtObs');
            $supervision = $this->input->post('supervision');

            $nivel = $this->input->post('nivel');
            $idCiclo = $this->input->post('gruposPredio');

            $OrtDes = $OrtEnt . "<br>" . $OrtDes;
            $OrtConId = $this->validar_contrato_actividad($this->idActividad, $this->idContratante);
            $OrtEstId = $this->Parametro_model->get_one_detParam_x_codigo('no_rec')['DprId']; //No Recibido - tabla : Detalle_parametro
            $OrtUsrEn = $this->data['userdata']['UsrId'];

            if (is_array($idCiclo)){
                $idBuscarGruposPredio = $this->input->post('buscarGruposPredio');
                $resultado = $this->OTTomaEstado_model->insert_orden_nivel_inferior($OrtNum, $OrtDes, $OrtObs, $OrtPrd, $OrtFchEj, $OrtFchEm, $OrtConId, $OrtEstId, $this->idActividad, $OrtUsrEn, $this->idContratante, $_SESSION['ordenP']['detalle'], $idBuscarGruposPredio, $idCiclo, $nivel, $supervision, $_SESSION['ordenP']['tamanioMuestra'] );
            }else {
                $resultado = $this->OTTomaEstado_model->insert_orden($OrtNum, $OrtDes, $OrtObs, $OrtPrd, $OrtFchEj, $OrtFchEm, $OrtConId, $OrtEstId, $this->idActividad, $OrtUsrEn, $this->idContratante, $_SESSION['ordenP']['detalle'], $idCiclo, $nivel, $supervision, $_SESSION['ordenP']['tamanioMuestra']  );
            }

            if ($resultado) {
                if (isset($_SESSION['ordenP']['detalle'])) {
                    $_SESSION['ordenP']['detalle'] = array();
                }

                if (isset($_SESSION['ordenP'])) {
                    unset($_SESSION['ordenP']);
                }
                if (isset($_SESSION['orden'])) {
                    unset($_SESSION['orden']);
                }
                $this->session->set_flashdata('mensaje', array('success', 'La Orden de trabajo se ha registrado satisfactoriamente.'));
                redirect(base_url() . 'toma_estado/ordenes');
            }else {
                $this->session->set_flashdata('mensaje', array('error', 'La Orden de trabajo no se ha podido registrar.'));
                redirect(base_url() . 'toma_estado/orden/nuevo/' . $idDetalleCronograma);
            }
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'Llene Correctamente los datos solicitados en el Formulario.'));
            redirect(base_url() . 'toma_estado/orden/nuevo/' . $idDetalleCronograma);
        }
    }
    
    // $this -> orden_nuevoP
    public function eliminar_cicloP($cicloCod,$idDetalleCronograma) {
        if (isset($_SESSION['ordenP']['detalle'][$cicloCod])) {
            $_SESSION['ordenP']['lecturasAcumulado'] -= $_SESSION['ordenP']['detalle'][$cicloCod]['lecturasRealizar'];
            $_SESSION['ordenP']['lecturistasAcumulado'] -= $_SESSION['ordenP']['detalle'][$cicloCod]['lecturistasUtilizados'];
            $_SESSION['ordenP']['lecturistasDisponibles'] = $_SESSION['ordenP']['totalLecturistas'] - $_SESSION['ordenP']['lecturistasAcumulado'];
            //$_SESSION['orden']['lecturasMaximas'] = $_SESSION['orden']['rendimiento'] * $_SESSION['orden']['lecturistasDisponibles'];
            //unset($_SESSION['orden']['detalle'][$idgrupoPredio]);
            unset($_SESSION['ordenP']['detalle'][$cicloCod]);
        }
        redirect(base_url() . 'toma_estado/orden/nuevo/' . $idDetalleCronograma);
    }
    
    //$this -> orden_nuevoP
    public function insertar_cicloP($idgrupoPredio, $idContrato) {
        //if (!isset($_SESSION['orden']['detalle'][$idgrupoPredio])||isset($_SESSION['orden'])) {
        //dentro del ciclo ingresado
        $idPeriodo = $_SESSION['ordenP']['periodo'];
        $periodo = $this->Periodo_model->get_one_periodo($idPeriodo);
        $conexionesTotales = 0;
        $lecturasRestantes = 0;
        if (is_array($idgrupoPredio)) {
            foreach ($idgrupoPredio as $idgrupoPredio2) {
                $conexionesTotales = $conexionesTotales + intval($this->Catastro_model->get_cant_conexiones_x_grupoPredio_x_mes($idgrupoPredio2, $periodo['PrdOrd'], $periodo['PrdAni']));
                $lecturasRestantes = intval($this->Lectura_model->get_cant_lecturas_restantes_x_grupoPredio($periodo['PrdId'], $idgrupoPredio2, $conexionesTotales, $this->idContratante));
            }
        } else {
            $conexionesTotales = $this->Catastro_model->get_cant_conexiones_x_grupoPredio_x_mes($idgrupoPredio, $periodo['PrdOrd'], $periodo['PrdAni']);
            $lecturasRestantes = $this->Lectura_model->get_cant_lecturas_restantes_x_grupoPredio($periodo['PrdId'], $idgrupoPredio, $conexionesTotales, $this->idContratante);
        }
        $filaInicio = $conexionesTotales - $lecturasRestantes;
        if ($conexionesTotales != 0) {
            if ($lecturasRestantes > 0) {
                $nroDia = 1;
                $nroSuborden = 1;
                while ($lecturasRestantes > 0) {
                    $parts = explode('-', $_SESSION['ordenP']['fchEj']);
                    $fecha1 = strtotime($parts[0] . '-' . $parts[1] . '-' . $parts[2]);
                    $fechaEj = date('d/m/Y', strtotime('+' . ($nroDia - 1) . ' day', $fecha1));
                    //array de lecturistas
                    $lecturistasDisponibles = $this->Lecturista_model->get_lecturista_disponibles($this->idContratante, $idContrato, $fechaEj);
                    if (count($lecturistasDisponibles) > 0) {
                        $lecturasMaximas = count($lecturistasDisponibles) * $_SESSION['ordenP']['rendimiento'];
                        //$_SESSION['orden']['detalle'][$idgrupoPredio] = $ciclo;
                        $lecturasDia = 0;
                        if ($lecturasRestantes <= $lecturasMaximas) {
                            //se puede hacer completo lo que falta del ciclo
                            $lecturasDia = $lecturasRestantes;
                        } else {
                            //solo se puede hacer una parte de lo que falta
                            $lecturasDia = $lecturasMaximas;
                        }
                        $_SESSION['ordenP']['detalle'][$nroDia]['lecturistas'] = array_column($lecturistasDisponibles, null, 'LtaId');
                        $_SESSION['ordenP']['detalle'][$nroDia]['lecturasRealizar'] = $lecturasDia;
                        $_SESSION['ordenP']['detalle'][$nroDia]['fecha'] = $fechaEj;
                        //$_SESSION['orden']['detalle'][$nroDia]['lecturasMaximas'] = $lecturasRestantes;
                        $cantSubordenes = ceil($lecturasDia / $_SESSION['ordenP']['rendimiento']);
                        $_SESSION['ordenP']['detalle'][$nroDia]['nroLecturistas'] = $cantSubordenes;
                        $_SESSION['ordenP']['lecturasAcumulado'] += $lecturasDia;
                        $_SESSION['ordenP']['subordenesAcumulado'] += $cantSubordenes;
                        //$_SESSION['orden']['lecturistasDisponibles'] = $_SESSION['orden']['totalLecturistas'] - $_SESSION['orden']['lecturistasAcumulado'];
                        //$_SESSION['orden']['lecturasMaximas'] = $_SESSION['orden']['rendimiento'] * $_SESSION['orden']['lecturistasDisponibles'];
                        if (!isset($_SESSION['ordenP']['detalle'][$nroDia]['aleatorio'])){
                            $_SESSION['ordenP']['detalle'][$nroDia]['aleatorio'] = true;
                        }
                        $_SESSION['ordenP']['detalle'][$nroDia]['conexionesTotales'] = $conexionesTotales;
                        $lecturasRestantes -= $lecturasDia;
                        //crear subordenes
                        $promedio = ceil($lecturasDia / $cantSubordenes);
                        for ($i = 0; $i < $cantSubordenes; $i++) {
                            if ($promedio <= $lecturasDia) {
                                $cantLecturas = $promedio;
                            } else {
                                $cantLecturas = $lecturasDia;
                            }
                            $_SESSION['ordenP']['detalle'][$nroDia]['subordenes'][$nroSuborden]['cantidad'] = $cantLecturas;
                            $_SESSION['ordenP']['detalle'][$nroDia]['subordenes'][$nroSuborden]['idLecturista'] = 0;
                            $nroSuborden++;
                            $lecturasDia -= $cantLecturas;
                        }
                        $nroDia++;
                    } else {
                        //no hay lecturistas disponibles
                        $this->session->set_flashdata('mensaje', array('error', 'No hay lecturistas disponibles para la fecha elegida.'));
                        $lecturasRestantes = 0;
                    }
                }
            } else {
                //ya se ha completado las lecturas para este ciclo
                $this->session->set_flashdata('mensaje', array('error', 'Ya se ha realizado una orden de trabajo para el Nivel Comercial seleccionado.'));
            }
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'No hay datos cargados para este periodo.'));
        }
    }
    
    // this-> orden_nuevo_detalle_ciclo()
    public function actualizar_cicloP($nroDia) {
        $aleatorio = $this->input->post('asignacion') == 0;
        $lecturistas = $_SESSION['ordenP']['detalle'][$nroDia]['lecturistas'];
        $subordenes = $_SESSION['ordenP']['detalle'][$nroDia]['subordenes'];
        $isOk = TRUE;
        if (!$aleatorio) {
            //CUANDO ES MANUAL
            foreach ($subordenes as $nroSuborden => $suborden) {
                if (isset($_POST['lecturista_' . $nroSuborden])) {
                    $idLecturista = $this->input->post('lecturista_' . $nroSuborden);
                    if ($idLecturista != 0) {
                        //SELECCIONO UN LECTURISTA DEL COMBO
                        if (isset($lecturistas[$idLecturista])) {
                            //AUN NO ESTA ASIGNADO
                            $subordenes[$nroSuborden]['idLecturista'] = $idLecturista;
                            unset($lecturistas[$idLecturista]);
                        } else {
                            //YA ESTA ASIGNADO Y SE REPITE
                            $lecturistas[$idLecturista] = 0;
                            $isOk = FALSE;
                        }
                    } else {
                        //NO SELECCIONO A UN LECTURISTA
                        $lecturistas[$idLecturista] = 0;
                        $isOk = FALSE;
                    }
                }
            }
            if ($isOk) {
                $_SESSION['ordenP']['detalle'][$nroDia]['subordenes'] = $subordenes;
                $_SESSION['ordenP']['detalle'][$nroDia]['aleatorio'] = FALSE;
                //$_SESSION['orden']['detalle'][$nroDia]['aleatorio'] = $aleatorio;
            }
            return $isOk;
        }
    }
    
    public function orden_limpiarP($idDetalleCronograma) {
        if (isset($_SESSION['ordenP'])) {
            unset($_SESSION['ordenP']);
        }
        if (isset($_SESSION['orden'])) {
            unset($_SESSION['orden']);
        }
        redirect(base_url() . 'toma_estado/orden/nuevo/' . $idDetalleCronograma);
    }
    
    // this -> orden_nuevoP
    public function limpiar_cicloP($idContrato) {
        $_SESSION['ordenP']['detalle'] = array();
        //numero de lecturas que puede hacer un lecturista segun contrato
        $rendimientosPorActividad = $this->Contrato_model->get_rendimientos_x_contrato_x_actividad($idContrato, $this->idActividad);
        $contrato = $this->Contrato_model->get_one_contrato($idContrato);
        $_SESSION['ordenP']['rendimiento'] = implode(",", max($rendimientosPorActividad));
        //$_SESSION['orden']['lecturistas'] = $this->Lecturista_model->get_lecturista_disponibles($this->idContratante, $idContrato, $_SESSION['orden']['fchEj']);
        //nro de lecturistas activos registrado
        $_SESSION['ordenP']['totalLecturistas'] = $this->Lecturista_model->get_cant_lecturista_x_contrato($idContrato);
        $_SESSION['ordenP']['lecturasAcumulado'] = 0;
        $_SESSION['ordenP']['subordenesAcumulado'] = 0;
        $_SESSION['ordenP']['lecturistasDisponibles'] = $_SESSION['ordenP']['totalLecturistas'];
    }
    
    //toma_estado/orden/nuevo/detalle_ciclo/(:num)
    public function orden_nuevo_detalle_cicloP($nroDia) {
        //SI ES POST
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            //ES POST
            //METODO PARA ACTUALIZAR LOS CAMBIOS
            $isOk = $this->actualizar_cicloP($nroDia);
            if ($isOk) {
                $this->data['cerrar'] = TRUE;
            } else {
                $this->session->set_flashdata('mensaje2', array('error', 'Error al asignar lecturistas.'));
            }
        }
        $idPeriodo = $_SESSION['ordenP']['periodo'];
        $periodo = $this->Periodo_model->get_one_periodo($idPeriodo);
        $this->data['ciclo'] = $this->Catastro_model->get_one_grupoPredio_x_mes($nroDia, $periodo['PrdOrd'], $periodo['PrdAni']);
        $this->data['nroDia'] = $nroDia;
        $this->data['lecturistas'] = $_SESSION['ordenP']['detalle'][$nroDia]['lecturistas'];
        $this->data['subordenes'] = $_SESSION['ordenP']['detalle'][$nroDia]['subordenes'];
        $this->data['aleatorio'] = $_SESSION['ordenP']['detalle'][$nroDia]['aleatorio'];
        $this->data['view'] = 'lectura/OTTomaEstado_ciclos_detalle_plan_view';
        $this->load->view('template/Reporte', $this->data);
    }
    
    public function orden_nuevo_ciclos2($idDetalleCronograma) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_orden_lectura');
        $idContrato = $this->validar_contrato_actividad($this->idActividad, $this->idContratante);

        if ($idContrato != FALSE) {
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                
            }
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            $ciclos = $this->Catastro_model->get_all_ciclo_localidad();
            if (isset($_SESSION['orden'])) {
                //ya hay datos de orden anterior
                if ($this->input->server('REQUEST_METHOD') == 'POST') {
                    $_SESSION['orden']['numero'] = $this->input->post('OrtNum');
                    $_SESSION['orden']['descripcion'] = $this->input->post('OrtDes');
                    $_SESSION['orden']['anio'] = $this->input->post('OrtAnio');
                    $_SESSION['orden']['mes'] = $this->input->post('OrtMes');
                    $_SESSION['orden']['bloqueado'] = true;
                    $_SESSION['orden']['fchEj'] = $this->input->post('OrtFchEj');
                    $_SESSION['orden']['fchEm'] = $this->input->post('OrtFchEm');
                    $_SESSION['orden']['observacion'] = $this->input->post('OrtObs');
                    if (isset($_POST['btn_guardar'])) {
                        //boton guardar orden
                        $this->guardar_orden();
                    } elseif (isset($_POST['eliminar'])) {
                        //boton eliminar de cada ciclo
                        $idCiclo = $_POST['eliminar'];
                        $this->eliminar_ciclo($idCiclo);
                    } elseif (isset($_POST['multi'])) {
                        //boton agregar ciclos
                        $this->insertar_ciclo($ciclos);
                    } elseif (isset($_POST['btn_limpiar'])) {
                        //boton agregar ciclos
                        $this->orden_limpiar2($idDetalleCronograma);
                    }
                }
            } else {
                //ES UNA NUEVA ORDEN DE TRABAJO
                $_SESSION['orden']['detalle'] = array();
                //numero de lecturas que puede hacer un lecturista segun contrato
                $rendimientosPorActividad = $this->Contrato_model->get_rendimientos_x_contrato_x_actividad($idContrato, $this->idActividad);
                $_SESSION['orden']['rendimiento'] = implode(",", max($rendimientosPorActividad));
                
                $_SESSION['orden']['lecturistas'] = array_column($this->Lecturista_model->get_all_lecturista($idContratante), null, 'LtaId');
                foreach ($_SESSION['orden']['lecturistas'] as $key => $lecturista) {
                    $_SESSION['orden']['lecturistas'][$key]['asignado'] = FALSE;
                }
                //nro de lecturistas activos registrado
                $_SESSION['orden']['totalLecturistas'] = count($_SESSION['orden']['lecturistas']);
                $_SESSION['orden']['lecturasAcumulado'] = 0;
                $_SESSION['orden']['lecturistasAcumulado'] = 0;
                $_SESSION['orden']['lecturistasDisponibles'] = $_SESSION['orden']['totalLecturistas'];
                $_SESSION['orden']['numero'] = '';
                $_SESSION['orden']['descripcion'] = '';
                $_SESSION['orden']['anio'] = date('Y');
                $_SESSION['orden']['mes'] = $this->utilitario_cls->nombreMes(date('m') + 1);
                $contrato = $this->Contrato_model->get_one_contrato($idContrato);
                $entregables = $this->Usuario_model->get_recepcion_orden($contrato['ConCstId'], $this->data['userdata']['contratante']['CntId']);
                $_SESSION['orden']['entregado'] = implode(', ', $entregables);
            }
            // PROFILER
            // $this->output->enable_profiler(TRUE);
            //
            $_SESSION['orden']['lecturasMaximas'] = $_SESSION['orden']['rendimiento'] * $_SESSION['orden']['lecturistasDisponibles'];
            $this->data['ciclos'] = $ciclos;
            $detCronograma = $this->CronogramaLectura_model->get_one_detalle_cronograma_periodo($idDetalleCronograma);
            $this->data['detCron'] = $detCronograma;
            $this->data['accion'] = 'nuevo';
            $this->data['view'] = 'lectura/OTTomaEstado_ciclos2_view';
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Registrar Orden de Trabajo', ''));
            $this->load->view('template/Master', $this->data);
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'No tiene esta Actividad Contratada'));
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            redirect(base_url() . 'toma_estado/ordenes');
        }
    }

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="DETALLE ORDEN">
    public function orden_ver($idOrdenTrabajo) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_orden_lectura');
        $OrdenTrabajo = $this->OTTomaEstado_model->get_one_orden_trabajo($idOrdenTrabajo);
        if (!isset($OrdenTrabajo)) {
            show_404();
        }
        else{
        $this->data['OTTomaEstado'] = $this->OTTomaEstado_model->get_one_orden_trabj_all_datos($idOrdenTrabajo);
        $idUsrEn = $this->data['OTTomaEstado']['OrtUsrEn'];
        $idUsrRs = $this->data['OTTomaEstado']['OrtUsrRs'];
        $this->data['subordenes'] = $this->Lectura_model->get_detalle_suministros_lecturista($idOrdenTrabajo);
        $this->data['lecturistas'] = $this->Usuario_model->get_all_usuario_lecturista();
        $this->data['Subciclos'] = $this->Lectura_model->get_desc_ciclo_x_orden($idOrdenTrabajo);
        $this->data['LecSubCiclos'] = $this->Lectura_model->get_lecturistas_x_ciclo_x_orden($idOrdenTrabajo);
        $this->data['UsrEn'] = $this->Usuario_model->get_one_usuario_de_contratante($idUsrEn);
        $this->data['UsrRs'] = $this->Usuario_model->get_one_usuario_de_contratista($idUsrRs);
        $parametro = $this->Parametro_model->get_one_detParam($this->data['OTTomaEstado']['OrtEstId']);
        $this->data['EstadoOrden'] = $parametro;
        $orden = $this->OTTomaEstado_model->get_one_orden_trabajo_all_datos($idOrdenTrabajo);
        $cantidadSubniveles = $this->NivelGrupo_model->get_cant_subniveles($orden['OrtNgrId']);
        //ES EL ULTIMO NIVEL Y NO TIENE MAS SUBNIVELES
        if ($cantidadSubniveles == 0)         {
            $this->data['Nivel'] = $this->NivelGrupo_model->get_nivel_superior($orden['OrtNgrId']);
        }         else if ($cantidadSubniveles > 0)         {
            $this->data['Nivel'] = $this->NivelGrupo_model->get_one_nivelGrupo($orden['OrtNgrId']);
        }
        $this->data['GprNivel'] = $this->OTTomaEstado_model->get_ciclo_orden_trabajo($idOrdenTrabajo);
        $this->data['accion'] = 'ver';
        $this->data['view'] = 'lectura/OTTomaEstado_ver_ciclos_view';
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Ver Orden de Trabajo', ''));
        $this->data['menu']['hijo'] = 'ordenes';
        $this->load->view('template/Master', $this->data);
        }
    }

    public function orden_editar($idOrdenTrabajo) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_orden_lectura');
        
        $ordenTrabajoEdit = $this->OTTomaEstado_model->get_one_orden_trabajo($idOrdenTrabajo);
        if (!isset($ordenTrabajoEdit)) {
            show_404();
        }
        else{
        $periodo = $this->OTTomaEstado_model->get_periodo_orden_trabajo($idOrdenTrabajo);
        $cicloOrden = $this->OTTomaEstado_model->get_ciclo_orden_trabajo($idOrdenTrabajo);
       
        $parametro = $this->Parametro_model->get_one_detParam_x_codigo('no_rec');
        if ($ordenTrabajoEdit['OrtEstId'] != $parametro['DprId']) {
            $this->session->set_flashdata('mensaje', array('error', 'No se Puede Editar la Orden de Trabajo porque ya ha sido recibida.'));
            redirect(base_url() . 'toma_estado/ordenes');
        } else {
            $this->data['ordenTrabajoEdit'] = $ordenTrabajoEdit;
//            $this->data['subordenes'] = $this->Lectura_model->get_detalle_suministros_lecturista($idOrdenTrabajo);
            
            $SubOrdenes = $this->OTTomaEstado_cst_model->get_cantLec_por_suborden_por_ordenTrabajo_porDia($idOrdenTrabajo);
            foreach ($SubOrdenes as $nroSuborden => $suborden) {
                $SubOrdenes[$nroSuborden]['nroLecturistas'] = $this->OTTomaEstado_cst_model->get_cantSubords_por_ordenTrbj_porDia($idOrdenTrabajo,$SubOrdenes[$nroSuborden]['SolFchEj']);
            }
            $this->data['SubOrdenesDia'] = $SubOrdenes;
            $this->data['lecturistas'] = $this->Usuario_model->get_all_usuario_lecturista();
            $this->data['SubciclosOrden'] = $this->Lectura_model->get_desc_ciclo_x_orden($idOrdenTrabajo);
            $this->data['LecSubCiclos'] = $this->Lectura_model->get_lecturistas_x_ciclo_x_orden($idOrdenTrabajo);
            $this->data['periodo'] = $periodo;
            $this->data['cicloOrden'] = $cicloOrden;
            $this->data['cantidad'] = $this->Lectura_model->get_cant_suborden_lextura($idOrdenTrabajo);
            $this->data['accion'] = 'editar';
            //$this->data['view'] = 'lectura/OTTomaEstado_ciclos2_view';
            $this->data['view'] = 'lectura/OTLectura_uno_edit_view';
            $this->data['proceso'] = 'Toma de Estado';
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Editar Orden de Trabajo', ''));
            $this->load->view('template/Master', $this->data);
            if (isset($_POST['btn_guardar'])) {
                //boton guardar orden
                $this->orden_editar_guardar($idOrdenTrabajo);
            }
        }
        }
    }

    //toma_estado/orden/editar/(:num)/guardar
    public function orden_editar_guardar($idOrdenTrabajo) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_orden_lectura');
        $this->load->library('form_validation');
        //$this->form_validation->set_rules('OrtNum', 'Num. Orden de Trabajo', 'required');
        $this->form_validation->set_rules('OrtFchEm2', 'Fecha Envio Max.', 'required');
        if ($this->form_validation->run() == TRUE) {
            $OrtNum = $this->input->post('OrtNum');
            $OrtEntr = $this->input->post('OrtEntr');
            $OrtDes = $this->input->post('OrtDes');
            $OrtObs = $this->input->post('OrtObs');
            //$OrtFchEj = $this->input->post('OrtFchEj2');
            $OrtFchEm = $this->input->post('OrtFchEm2');
            $OrtDes = $OrtEntr . "<br>" . $OrtDes;
            $OrtUsrEn = $this->data['userdata']['UsrId'];
            $resp = $this->OTTomaEstado_model->update_orden_trabajo_ciclos($OrtNum, $OrtDes, $OrtObs, $OrtFchEm, $OrtUsrEn, $idOrdenTrabajo);
            if ($resp) {
                $this->session->set_flashdata('mensaje', array('success', 'La Orden de Trabajo se ha actualizado Correctamente.'));
                $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                $this->data['accion'] = 'editar';
                $this->data['view'] = 'lectura/OTLectura_uno_edit_view';
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Editar Orden de Trabajo', ''));
                $this->load->view('template/Master', $this->data);
                redirect(base_url() . 'toma_estado/orden/editar/' . $idOrdenTrabajo);
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al momento de Actualizar.'));
                $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                $this->data['accion'] = 'editar';
                $this->data['view'] = 'lectura/OTLectura_uno_edit_view';
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Editar Orden de Trabajo', ''));
                $this->load->view('template/Master', $this->data);
                redirect(base_url() . 'toma_estado/orden/editar/' . $idOrdenTrabajo);
            }
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'Complete los campos.'));
            $this->data['accion'] = 'editar';
            $this->data['view'] = 'lectura/OTLectura_uno_edit_view';
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Editar Orden de Trabajo', ''));
            $this->load->view('template/Master', $this->data);
            redirect(base_url() . 'toma_estado/orden/editar/' . $idOrdenTrabajo);
        }
    }

    // toma_estado/get_detalle_x_suborden_json
    public function get_detalle_x_suborden_json() {

        $idCiclo = $_SESSION['orden']['gruposPredio'];

        $anio = $_SESSION['orden']['anio']; //2016
        $idPeriodo = $_SESSION['orden']['periodo'];
        $periodo = $this->Periodo_model->get_one_periodo($idPeriodo);
        $mes = $periodo['PrdOrd'] == 1 ? 12 : $periodo['PrdOrd'] - 1; // cambiar el 12 por un metodo que devuelva la ultima orden
        $nroDia = $this->input->post('nroDia'); //2
        $inicio = 0;
        $countOrdenes = 0;
        $nroSuborden = $this->input->post('nroSuborden'); //37
        $j = 1;
        $k = 1;
        for ($i = 1; $i <= ($nroDia - 1); $i++) {
        #for ($i = ($nroDia - 1); $i > 0; $i--) {
            #$inicio += count($_SESSION['orden']['detalle'][$nroDia]['subordenes']);
            $countOrdenes += count($_SESSION['orden']['detalle'][$i]['subordenes']);//consultar la cantidad de predios de la ultima orden del dia anterior
            for ($k; $k <= $countOrdenes; $k++) {
                $inicio += $_SESSION['orden']['detalle'][$i]['subordenes'][$k]['cantidad'];
                $j++;
            }
        }
        for ($j; $j < $nroSuborden; $j++) {
            $inicio += $_SESSION['orden']['detalle'][$nroDia]['subordenes'][$j]['cantidad']; //12250
        }
        $cantidad = $_SESSION['orden']['detalle'][$nroDia]['subordenes'][$nroSuborden]['cantidad'];


        if (is_array($idCiclo))         {
            $detalleSuborden = $this->Catastro_model->get_conexiones_x_subciclo_x_suborden($idCiclo, $cantidad, $anio, $mes, $inicio);
        }         else         {
            $detalleSuborden = $this->Catastro_model->get_conexiones_x_suborden($idCiclo, $cantidad, $anio, $mes, $inicio);
        }
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($detalleSuborden));
    }
    
    // toma_estado/get_detalle_x_suborden_jsonP
    public function get_detalle_x_suborden_jsonP() {

        $idCiclo = $_SESSION['ordenP']['gruposPredio'];

        $anio = $_SESSION['ordenP']['anio']; //2016
        $idPeriodo = $_SESSION['ordenP']['periodo'];
        $periodo = $this->Periodo_model->get_one_periodo($idPeriodo);
        $mes = $periodo['PrdOrd'] == 1 ? 12 : $periodo['PrdOrd'] - 1; // cambiar el 12 por un metodo que devuelva la ultima orden
        $nroDia = $this->input->post('nroDia'); //2
        $inicio = 0;
        $countOrdenes = 0;
        $nroSuborden = $this->input->post('nroSuborden'); //37
        $j = 1;
        $k = 1;
        for ($i = 1; $i <= ($nroDia - 1); $i++) {
        #for ($i = ($nroDia - 1); $i > 0; $i--) {
            #$inicio += count($_SESSION['orden']['detalle'][$nroDia]['subordenes']);
            $countOrdenes += count($_SESSION['ordenP']['detalle'][$i]['subordenes']);//consultar la cantidad de predios de la ultima orden del dia anterior
            for ($k; $k <= $countOrdenes; $k++) {
                $inicio += $_SESSION['ordenP']['detalle'][$i]['subordenes'][$k]['cantidad'];
                $j++;
            }
        }
        for ($j; $j < $nroSuborden; $j++) {
            $inicio += $_SESSION['ordenP']['detalle'][$nroDia]['subordenes'][$j]['cantidad']; //12250
        }
        $cantidad = $_SESSION['ordenP']['detalle'][$nroDia]['subordenes'][$nroSuborden]['cantidad'];


        if (is_array($idCiclo))         {
            $detalleSuborden = $this->Catastro_model->get_conexiones_x_subciclo_x_suborden($idCiclo, $cantidad, $anio, $mes, $inicio);
        }         else         {
            $detalleSuborden = $this->Catastro_model->get_conexiones_x_suborden($idCiclo, $cantidad, $anio, $mes, $inicio);
        }
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($detalleSuborden));
    }
    
    public function orden_eliminar($idOrdenTrabajo) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('eliminar_orden_lectura');
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $this->data['ordenTrabajo'] = $this->OTTomaEstado_model->get_one_orden_trabajo($idOrdenTrabajo);
        $this->OTTomaEstado_model->delete_orden($idOrdenTrabajo);
        $this->session->set_flashdata('mensaje', array('success', 'La Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'] . ' se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'toma_estado/ordenes');
    }
// </editor-fold>(VER / EDITAR / ELIMINAR )

// <editor-fold defaultstate="collapsed" desc="LISTA DE ATIPICOS DE LA ORDEN">
    /* ELIMINAR ATIPICOS    */
    public function orden_atipicos($idOrdenTrabajo) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_orden_lectura');
        $OTTomaEstado = $this->OTTomaEstado_model->get_one_orden_trabj_all_datos($idOrdenTrabajo);
        if (!isset($OTTomaEstado)) {
            show_404();
        }


        $idTipoAtipico = $this->Parametro_model->get_one_detParam_x_codigo('lec_ati')['DprId'];
        $lecturasAtipicas = $this->Lectura_model->get_cant_lect_x_orden_x_tipo($idOrdenTrabajo, $idTipoAtipico);
        if ($lecturasAtipicas <= 0)         {
            /* SI NO ES UN POST, CON ALGUN VALOR, ES DECIR ES UNA LLAMADA A LA RUTA DE ATIPICOS */
            if (!($this->input->server('REQUEST_METHOD') == 'POST')) {
                $_SESSION['listaAtipicos'] = array();
            }

            $this->data['NivelGrupo'] = $this->NivelGrupo_model->get_one_nivelGrupo($OTTomaEstado['OrtNgrId']);
            $this->data['SubNivel'] = $this->NivelGrupo_model->get_subnivel_inferior($OTTomaEstado['OrtNgrId']);


            $this->data['OTTomaEstado'] = $this->OTTomaEstado_model->get_one_orden_trabj_all_datos($idOrdenTrabajo);
            $idUsrEn = $this->data['OTTomaEstado']['OrtUsrEn'];
            $idUsrRs = $this->data['OTTomaEstado']['OrtUsrRs'];

            $this->data['Subciclos'] = $this->Lectura_model->get_desc_ciclo_x_orden($idOrdenTrabajo);

            $this->data['UsrEn'] = $this->Usuario_model->get_one_usuario_de_contratante($idUsrEn);
            $this->data['UsrRs'] = $this->Usuario_model->get_one_usuario_de_contratista($idUsrRs);
            $parametro = $this->Parametro_model->get_one_detParam($this->data['OTTomaEstado']['OrtEstId']);
            $this->data['EstadoOrden'] = $parametro;

            $this->data['cicloOrden'] = $this->OTTomaEstado_model->get_ciclo_orden_trabajo($idOrdenTrabajo);

            if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['enviar'])) {
                //ES POST que procede de la Consulta por Codigo Facturacion o Nombres
                $tipoBusqueda = $this->input->post('tipoBusqueda'); // radio butonn
                $campoBusqueda = $this->input->post('campoBusqueda');
                if (isset($tipoBusqueda)) {
                    if ($tipoBusqueda == 'CodFc') {
                        $resultados = $this->Lectura_model->get_lectura_x_codFac($idOrdenTrabajo, $campoBusqueda);
                    }
                    if ($tipoBusqueda == 'nombres') {
                        $resultados = $this->Lectura_model->get_lectura_x_nombres($idOrdenTrabajo, $campoBusqueda);
                    }
                }
                $this->data['resultados'] = $resultados;
            }
            if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['agregar_lista'])) {
                $idLecturas = $this->input->post('idLecturas');
                foreach ($idLecturas as $idLectura) {
                    $resultado = $this->Lectura_model->get_one_lectura($idLectura);
                    array_push($_SESSION['listaAtipicos'], $resultado);
                }
            }

            if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['guardar_lista_atipicos'])) {
                if (isset($_SESSION['listaAtipicos'])) {
                    $numeroUltimasFilas = count($_SESSION['listaAtipicos']);
                    $idTipo = $this->Parametro_model->get_one_detParam_x_codigo('lec_sup')['DprId'];
                    $idLecturasAReemplazar = array_column($this->Lectura_model->get_last_n_lecturas_x_orden_x_tipo($idOrdenTrabajo, $idTipo, $numeroUltimasFilas), 'LecId');
                    $contador = 0;
                    $idTipoAtipico = $this->Parametro_model->get_one_detParam_x_codigo('lec_ati')['DprId'];
                    for ($i = 0; $i < $numeroUltimasFilas; $i++) {
                        $data = array(
                            'LecCodFc' => $_SESSION['listaAtipicos'][$i]['LecCodFc'],
                            'LecNom' => $_SESSION['listaAtipicos'][$i]['LecNom'],
                            'LecCal' => $_SESSION['listaAtipicos'][$i]['LecCal'],
                            'LecMun' => $_SESSION['listaAtipicos'][$i]['LecMun'],
                            'LecMed' => $_SESSION['listaAtipicos'][$i]['LecMed'],
                            'LecInt' => 0,
                            'LecEli' => FALSE,
                            'LecFchRg' => date("Y-m-d H:i:s"),
                            'LecFchAc' => date("Y-m-d H:i:s"),
                            'LecCxnId' => $_SESSION['listaAtipicos'][$i]['LecCxnId'],
                            'LecCsmPr' => $_SESSION['listaAtipicos'][$i]['LecCsmPr'],
                            'LecAnt' => $_SESSION['listaAtipicos'][$i]['LecAnt'],
                            'LecDis' => $_SESSION['listaAtipicos'][$i]['LecDis'],
                            'LecTipId' => $idTipoAtipico,
                            'LecGprId' => $_SESSION['listaAtipicos'][$i]['LecGprId']
                        );
                        $this->db->where('"LecId"', $idLecturasAReemplazar[$i]);
                        $this->db->update('"Lectura"', $data);
                        $contador += $this->db->affected_rows();
                    }
                    if ($contador == $numeroUltimasFilas)                    {
                        unset($_SESSION['listaAtipicos']);
                        $this->session->set_flashdata('mensaje', array('success', 'Se Guardo la Lista de Lecturas atipicas en las Lecturas de Control/Supervision de calidad de Lecturas.'));
                        redirect(base_url() . 'toma_estado/ordenes');
                    }                    else                    {
                        $this->session->set_flashdata('mensaje', array('error', 'No se pudo guardar la Lista de Lecturas atipicas en las Lecturas de Control/Supervision de calidad de Lecturas.'));
                    }
                }
            }
            $this->data['accion'] = 'atipicos';
            $this->data['view'] = 'lectura/OTLectura_atipicos_view';
            $this->data['breadcrumbs'] = array(array('Orden de Trabajo N° ' . $OTTomaEstado["OrtNum"], 'toma_estado/orden/ver/' . $OTTomaEstado["OrtId"]), array('Lista de Atipicos de la Orden de Trabajo', ''));
            $this->data['menu']['hijo'] = 'ordenes';
            $this->load->view('template/Master', $this->data);
        }         else         {
            $this->session->set_flashdata('mensaje', array('error', 'Ya existe una Lista de Lecturas atipicas en las Lecturas de Control/Supervision de calidad de Lecturas.'));
            redirect(base_url() . 'toma_estado/ordenes');
        }
    }


    public function orden_atipicos_limpiar($idOrdenTrabajo) {
        if (isset($_SESSION['listaAtipicos'])) {
            unset($_SESSION['listaAtipicos']);
        }
        redirect(base_url() . 'toma_estado/orden/' . $idOrdenTrabajo . '/atipicos');
    }

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="PENALIZACIONES DE LA ORDEN">

    /*  PENALIZACIONES DE LA ORDEN DE TRABAJO    */
    public function orden_penalizaciones($idOrdenTrabajo) {
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('penalizar_orden_lectura');

        $this->data['ordenTrabajoEdit'] = $this->OTTomaEstado_model->get_one_orden_trabajo($idOrdenTrabajo);
        $this->data['penalizaciones'] = $this->Penalizacion_model->get_all_penalizaciones_x_orden($idOrdenTrabajo);

        $this->data['accion'] = 'editar';
        $this->data['view'] = 'lectura/OTsPenalizaciones_view';
        $this->data['proceso'] = 'Toma de Estado';
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajoEdit']['OrtNum'], ''));
        $this->load->view('template/Master', $this->data);
    }

    public function orden_penalizaciones_nuevo($idOrdenTrabajo) {
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('penalizar_orden_lectura');

        $this->data['detallesContratoPenalidad'] = $this->Contrato_model->get_all_detalle_contrato_penalidad();

        $ordenTrabajo = $this->OTTomaEstado_model->get_one_orden_trabajo($idOrdenTrabajo);
        $idContrato = $ordenTrabajo['OrtConId'];

        $this->data['gruposPenalidad'] = $this->Contrato_model->get_all_grupo_penalidad_x_contrato($idContrato);
        $this->data['penalidades'] = $this->Penalidad_model->get_penalidad_x_grupo($this->data['gruposPenalidad'][0]['GpeId']);


        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'lectura/OTPenalizacion_view';
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Penalizaciones de la Orden de Trabajo N° ' . $ordenTrabajo['OrtNum'], 'toma_estado/orden/' . $idOrdenTrabajo . '/penalizaciones'), array('Registrar Penalización', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function orden_penalizaciones_nuevo_guardar($idOrdenTrabajo) {
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['ordenTrabajo'] = $this->OTTomaEstado_model->get_one_orden_trabajo($idOrdenTrabajo);
        $this->acceso_cls->verificarPermiso('penalizar_orden_lectura');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('PnzCod', 'Codigo de Penalizacion', 'required');
        $this->form_validation->set_rules('PnzDes', 'Descripcion de Penalizacion', 'required');
        $this->form_validation->set_rules('PnzCas', 'Numero de Casos Suscitados', 'required');
        $this->form_validation->set_rules('PnzDia', 'Numero de Dias de retraso hasta la subsanación pertinente', 'required');
        $this->form_validation->set_rules('DcpId', 'Detalle Contrato Penalidad', 'required');

        if ($this->form_validation->run() == TRUE) {
            $PnzCod = $this->input->post('PnzCod');
            $PnzDes = $this->input->post('PnzDes');
            $PnzCas = $this->input->post('PnzCas');
            $PnzDia = $this->input->post('PnzDia');
            $DcpId = $this->input->post('DcpId');

            $Dcp = $this->Contrato_model->get_one_detalle_contrato_penalidad($DcpId);
            $resp = $this->Penalizacion_model->insert_penalizacion($PnzCod, $PnzDes, $PnzCas, $PnzDia, $Dcp, $idOrdenTrabajo);

            if ($resp) {
                $this->session->set_flashdata('mensaje', array('success', 'Se ha Registrado la Penalización a la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum']));
                $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);

                $this->data['accion'] = 'nuevo';
                $this->data['view'] = 'lectura/OTPenalizacion_view';
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], 'toma_estado/orden/' . $idOrdenTrabajo . '/penalizaciones'), array('Registrar Penalización', ''));
                redirect(base_url() . 'toma_estado/orden/' . $idOrdenTrabajo . '/penalizaciones');
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algún error al momento de guardar.'));
                $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);

                $this->data['accion'] = 'nuevo';
                $this->data['view'] = 'lectura/OTPenalizacion_view';
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], 'toma_estado/orden/' . $idOrdenTrabajo . '/penalizaciones'), array('Registrar Penalización', ''));
                redirect(base_url() . 'toma_estado/orden/' . $idOrdenTrabajo . '/penalizacion/nuevo');
            }
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'Complete los campos'));

            $this->data['accion'] = 'nuevo';
            $this->data['view'] = 'lectura/OTPenalizacion_view';
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], 'toma_estado/orden/' . $idOrdenTrabajo . '/penalizaciones'), array('Registrar Penalización', ''));
            $this->load->view('template/Master', $this->data);
            redirect(base_url() . 'toma_estado/orden/' . $idOrdenTrabajo . '/penalizacion/nuevo');
        }
    }

    public function orden_penalizaciones_editar($idOrdenTrabajo, $idPenalizacion) {
        if (!isset($idOrdenTrabajo) && !isset($idPenalizacion)) {
            show_404();
        }
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('penalizar_orden_lectura');

        $this->data['ordenTrabajo'] = $this->OTTomaEstado_model->get_one_orden_trabajo($idOrdenTrabajo);
        $this->data['penalizacionEdit'] = $this->Penalizacion_model->get_one_penalziacion($idPenalizacion);

        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'lectura/OTPenalizacion_view';
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], 'toma_estado/orden/' . $idOrdenTrabajo . '/penalizaciones'), array('Editar Penalización', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function orden_penalizaciones_editar_guardar($idOrdenTrabajo, $idPenalizacion) {
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('penalizar_orden_lectura');
        $this->data['ordenTrabajo'] = $this->OTTomaEstado_model->get_one_orden_trabajo($idOrdenTrabajo);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('PnzCod', 'Codigo de Penalizacion', 'required');
        $this->form_validation->set_rules('PnzDes', 'Descripcion de Penalizacion', 'required');
        $this->form_validation->set_rules('PnzCas', 'Numero de Casos Suscitados', 'required');
        $this->form_validation->set_rules('PnzDia', 'Numero de Dias de retraso hasta la subsanación pertinente', 'required');

        if ($this->form_validation->run() == TRUE) {
            $PnzCod = $this->input->post('PnzCod');
            $PnzDes = $this->input->post('PnzDes');
            $PnzCas = $this->input->post('PnzCas');
            $PnzDia = $this->input->post('PnzDia');
            $DcpId = $this->input->post('DcpId');

            $Dcp = $this->Contrato_model->get_one_detalle_contrato_penalidad($DcpId);

            $resp = $this->Penalizacion_model->update_penalizacion($PnzCod, $PnzDes, $PnzCas, $PnzDia, $Dcp, $idPenalizacion);

            if ($resp) {
                $this->session->set_flashdata('mensaje', array('success', 'La Penalización de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'] . ' se ha actualizado correctamenrte.'));
                $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);

                $this->data['accion'] = 'nuevo';
                $this->data['view'] = 'lectura/OTPenalizacion_view';
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], 'toma_estado/orden/' . $idOrdenTrabajo . '/penalizaciones'), array('Editar Penalización', ''));
                redirect(base_url() . 'toma_estado/orden/' . $idOrdenTrabajo . '/penalizacion/editar/' . $idPenalizacion);
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al momento de Actualizar el registro.'));
                $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);

                $this->data['accion'] = 'nuevo';
                $this->data['view'] = 'lectura/OTPenalizacion_view';
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], 'toma_estado/orden/' . $idOrdenTrabajo . '/penalizaciones'), array('Editar Penalización', ''));
                redirect(base_url() . 'toma_estado/orden/' . $idOrdenTrabajo . '/penalizacion/editar/' . $idPenalizacion);
            }
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'Complete los campos'));

            $this->data['accion'] = 'nuevo';
            $this->data['view'] = 'lectura/OTPenalizacion_view';
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], 'toma_estado/orden/' . $idOrdenTrabajo . '/penalizaciones'), array('Editar Penalización', ''));
            $this->load->view('template/Master', $this->data);
            redirect(base_url() . 'toma_estado/orden/' . $idOrdenTrabajo . '/penalizacion/editar/' . $idPenalizacion);
        }
    }

    public function orden_penalizaciones_eliminar($idOrdenTrabajo, $idPenalizacion) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('penalizar_orden_lectura');
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $this->data['ordenTrabajo'] = $this->OTTomaEstado_model->get_one_orden_trabajo($idOrdenTrabajo);
        $this->Penalizacion_model->delete_penalizacion($idPenalizacion);
        $this->session->set_flashdata('mensaje', array('success', 'La Penalización de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'] . ' se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'toma_estado/orden/' . $idOrdenTrabajo . '/penalizaciones');
    }

// </editor-fold>
 
}
