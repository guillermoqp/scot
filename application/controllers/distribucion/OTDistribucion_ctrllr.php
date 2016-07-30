<?php

class OTDistribucion_ctrllr extends CI_Controller {

    var $idContratante;
    var $idActividad;

    public function __construct() {
        parent::__construct();
        $this->load->model('distribucion/OTDistribucion_model');

        $this->load->model('general/Actividad_model');
        $this->load->model('general/Penalizacion_model');
        $this->load->model('general/Penalidad_model');
        $this->load->model('general/Periodo_model');
        $this->load->model('lectura/CronogramaLectura_model');
        $this->load->model('distribucion/OTDistribucion_model');
        $this->load->model('general/Contrato_model');
        $this->load->model('general/Contratista_model');
        $this->load->model('general/NivelGrupo_model');
        $this->load->model('sistema/Parametro_model');
        $this->load->model('acceso/Usuario_model');
        $this->load->model('catastro/Catastro_model');
        $this->load->model('distribucion/Distribuidor_model');
        $this->load->model('contratista/lectura/OTTomaEstado_cst_model');
        $this->load->model('general/SubActividad_model');
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->load->library('utilitario_cls');
        $this->acceso_cls->isLogin();
        
        $this->data['menu']['padre'] = 'distribucion';
        $this->data['menu']['hijo'] = 'ordenes_distribucion';
        
        $this->data['proceso'] = 'Distribucion de Recibos y Comunicaciones';
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->idContratante = $this->data['userdata']['contratante']['CntId'];
        $this->idActividad = $this->Actividad_model->get_id_actividad_x_codigo('distribucion');
    }

// <editor-fold defaultstate="collapsed" desc="ORDENES LISTAR">
    public function orden_listar() {
        $this->acceso_cls->verificarPermiso('ver_orden_distribucion');
        $idContrato = $this->validar_contrato_actividad($this->idActividad, $this->idContratante);
        if (isset($_SESSION['ordenD'])) {
            unset($_SESSION['ordenD']);
        }
        $this->data['ordenesTrabajo'] = $this->OTDistribucion_model->get_all_orden_trabajo_x_contratante($this->idContratante, $this->idActividad);
        $this->data['view'] = 'distribucion/OTDistribucion_lista_view';
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo de Distribucion de Recibos', ''));
        $this->load->view('template/Master', $this->data);
    }
// </editor-fold>

//<editor-fold defaultstate="collapsed" desc="ORDEN NUEVO POR CICLO Y SUB CICLO">
    //this -> orden_nuevo
    //this -> guardar_orden
    public function validar_contrato_actividad($idActividad, $idContratante) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
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
    
    public function get_tamanio_muestra_distribucion_supervision($poblacion) {
        $Z = floatval($this->Variable_model->get_one_variable_cod('z')['VarVal']);
        $muestra = round((pow(floatval($Z), 2) * 0.5 * 0.5 * intval($poblacion)) / (pow(0.05, 2) * (intval($poblacion) - 1) + pow(floatval($Z), 2) * 0.5 * 0.5));
        return $muestra;
    }

    // distribucion/orden/nuevo
    public function orden_nuevo() {
        $this->acceso_cls->verificarPermiso('editar_orden_distribucion');
        if (isset($_SESSION['ordenP'])) {
            unset($_SESSION['ordenP']);
        }
        if (isset($_SESSION['orden'])) {
            unset($_SESSION['orden']);
        }
        /*  VALIDAR QUE PUEDA GENERAR ORDENES DE TRABAJO SEGUN CONTRATO Y ACTIVIDAD CONTRATADA   */
        $idContrato = $this->validar_contrato_actividad($this->idActividad, $this->idContratante);
        if ($idContrato != FALSE) 
        {
            if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['nivel']) && !isset($_POST['btn_guardar'])) {
                $idNivel = $this->input->post('nivel');
                $this->limpiar_ciclo($idContrato);
            }
            
            if (isset($_SESSION['ordenD'])) {
                //ya hay datos de orden anterior
                if ($this->input->server('REQUEST_METHOD') == 'POST') {
                    $_SESSION['ordenD']['numero'] = $this->input->post('OrtNum');
                    $_SESSION['ordenD']['descripcion'] = $this->input->post('OrtDes');
                    $_SESSION['ordenD']['anio'] = $this->input->post('OrtAnio');
                    $_SESSION['ordenD']['periodo'] = $this->input->post('OrtPrd');
                    $_SESSION['ordenD']['observaciones'] = $this->input->post('OrtObs');
                   
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
                        $_SESSION['ordenD']['gruposPredio'] = $gruposPredio;
                        //$_SESSION['ordenD']['cicloNum'] = $_SESSION['ordenD']['gruposPredio'];
                        //verificar si ya se hicieron lecturas para el ciclo elegido
                    } else {
                        $_SESSION['ordenD']['gruposPredio'] = $gruposPredio;
                        //verificar si ya se hicieron lecturas para el subciclo elegido
                        $_SESSION['ordenD']['buscarGruposPredio'] = $this->input->post('buscarGruposPredio');
                        $this->data['grupoPredioSeleccionado'] = $this->NivelGrupo_model->get_one_grupoPredio($_SESSION['ordenD']['gruposPredio']);
                    }
                    
                    //$_SESSION['ordenD']['bloqueado'] = true;
                    $_SESSION['ordenD']['fchEj'] = $this->input->post('OrtFchEj');
                    $_SESSION['ordenD']['fchEm'] = $this->input->post('OrtFchEm');
                    $_SESSION['ordenD']['observacion'] = $this->input->post('OrtObs');
                    $_SESSION['ordenD']['supervision'] = !is_null($this->input->post('supervision'));
                    
                    if (isset($_POST['btn_guardar'])) {
                        //boton guardar orden
                        $this->guardar_orden();
                    } elseif (isset($_POST['eliminar'])) {
                        //boton eliminar de cada ciclo
                        $idCiclo = $_POST['eliminar'];
                        $this->eliminar_ciclo($idCiclo);
                    } elseif (isset($_POST['opcion'])) {
                        if (isset($_SESSION['ordenD']['gruposPredio'])) {
                            //cambio de algun campo fecha o Grupo Predio
                            $opcion = $this->input->post('opcion');
                            if (!is_array($_SESSION['ordenD']['gruposPredio'])){
                                //SI INGRESO UN CICLO, O GRUPO PREDIO 
                                $SubciclosDeCiclo = count($this->NivelGrupo_model->get_all_grupoPredio_x_idGrupoPredio($_SESSION['ordenD']['gruposPredio']));

                                $NivelSubciclo = $this->NivelGrupo_model->get_nivel_inferior($_SESSION['ordenD']['idNivel']);
                                
                                $idSubciclos = array_column($this->NivelGrupo_model->get_all_grupoPredio_x_periodo_x_nivelGrupo_distribucion($_SESSION['ordenD']['gruposPredio'], $NivelSubciclo['NgrId'], $_SESSION['ordenD']['periodo']), 'GprId');
                                if (isset($idSubciclos) && is_array($idSubciclos)){
                                    if ($SubciclosDeCiclo > count($idSubciclos)) {
                                        $this->session->set_flashdata('mensaje', array('error', 'Se Cargaron solo los Subciclos: ' . implode(",", $idSubciclos)));
                                        $_SESSION['ordenD']['gruposPredio'] = $idSubciclos;
                                    }
                                }
                            }
                            switch ($opcion) {
                                case 'gruposPredio':
                                    $this->limpiar_ciclo($idContrato);
                                    $this->insertar_ciclo($_SESSION['ordenD']['gruposPredio'], $idContrato);
                                    $_SESSION['ordenD']['bloqueado'] = true;
                                    break;
                                case 'fecha':
                                    $this->limpiar_ciclo($idContrato);
                                    $this->insertar_ciclo($_SESSION['ordenD']['gruposPredio'], $idContrato);
                                    $_SESSION['ordenD']['bloqueado'] = true;
                                    break;
                                case 'periodo':
                                    $this->limpiar_ciclo($idContrato);
                                    $this->insertar_ciclo($_SESSION['ordenD']['gruposPredio'], $idContrato);
                                    $_SESSION['ordenD']['bloqueado'] = true;
                                    break;
                                default:
                                    break;
                            }
                        } else {
                            $this->limpiar_ciclo($idContrato);
                        }
                    }
                }
            } else {
                //ES UNA NUEVA ORDEN DE TRABAJO
                $_SESSION['ordenD']['detalle'] = array();
                //numero de lecturas que puede hacer un lecturista segun contrato
                $rendimientosPorActividad = $this->Contrato_model->get_rendimientos_x_contrato_x_actividad($idContrato, $this->idActividad);
                $contrato = $this->Contrato_model->get_one_contrato($idContrato);
                
                $_SESSION['ordenD']['rendimiento'] = implode(",", max($rendimientosPorActividad));
               
                $_SESSION['ordenD']['totalLecturistas'] = $this->Distribuidor_model->get_cant_distribuidores_x_contrato($idContrato);
                $_SESSION['ordenD']['lecturasAcumulado'] = 0;
                $_SESSION['ordenD']['subordenesAcumulado'] = 0;
                $_SESSION['ordenD']['lecturistasDisponibles'] = $_SESSION['ordenD']['totalLecturistas'];
                $_SESSION['ordenD']['numero'] = date('Y');
                $_SESSION['ordenD']['descripcion'] = '';
                $_SESSION['ordenD']['observaciones'] = '';
                $_SESSION['ordenD']['anio'] = date('Y');
                $periods = $this->Periodo_model->get_all_periodo(1, $_SESSION['ordenD']['anio'], $this->idContratante);
                $_SESSION['ordenD']['periodo'] = (int) $periods[0]['PrdId'];
                $nivls = $this->NivelGrupo_model->get_all_nivelGrupo($this->idContratante);
                $idNivl = isset($idNivl) ? $idNivl : $nivls[0]['NgrId'];
                $gruposPre =  $this->NivelGrupo_model->get_all_grupoPredio_x_nivelGrupo($idNivl);
                $idGrupo = isset($idGrupo) ? $idGrupo : $gruposPre[0]['GprId'];
                $_SESSION['ordenD']['gruposPredio'] = (int) $idGrupo;
                $_SESSION['ordenD']['fchEj'] = date('d-m-Y', strtotime('+1 day', time()));
                $entregables = $this->Usuario_model->get_recepcion_orden($contrato['ConCstId'], $this->idContratante);
                $_SESSION['ordenD']['entregado'] = implode(', ', $entregables);
                $_SESSION['ordenD']['supervision'] = TRUE;
            }
            $this->data['anios'] = $this->CronogramaLectura_model->get_anios($this->idContratante);
            if (empty($this->data['anios'])) {
                $this->session->set_flashdata('mensaje', array('error', 'No existe Cronograma Anual'));
                redirect(base_url() . 'distribucion/ordenes');
            }
            $this->data['perio'] = $this->OTDistribucion_model->get_periodo($_SESSION['ordenD']['periodo']);

            //LA ACTIVIDAD DE DISTRIBUCION DE RECIBOS DEPENDE DEL CRONOGRAMA ANUAL DE TOMA DE ESTADO
            $idTomaEstado = $this->Actividad_model->get_id_actividad_x_codigo('toma_estado');
            $this->data['periodos'] = $this->Periodo_model->get_all_periodo( $idTomaEstado , $_SESSION['ordenD']['anio'] , $this->idContratante);

            
            $niveles = $this->NivelGrupo_model->get_all_nivelGrupo($this->idContratante);

            $this->data['niveles'] = $niveles;
            $idNivel = isset($idNivel) ? $idNivel : $niveles[0]['NgrId'];
            $_SESSION['ordenD']['idNivel'] = $idNivel;
            $this->data['idNivel'] = $idNivel;

            $cantidadSubniveles = $this->NivelGrupo_model->get_cant_subniveles($idNivel);
            //ES EL ULTIMO NIVEL Y NO TIENE MAS SUBNIVELES
            if ($cantidadSubniveles == 0 && !is_array($_SESSION['ordenD']['buscarGruposPredio'])){
                $this->data['esUltimoNivel'] = true;

                $this->data['nivelCiclo'] = $this->NivelGrupo_model->get_nivel_superior($idNivel);
                $this->data['gruposPredio'] = $this->NivelGrupo_model->get_all_grupoPredio_x_nivelGrupo($this->data['nivelCiclo']['NgrId']);
                if (is_null($this->input->post('buscarGruposPredio'))) {
                    $nivels = $this->NivelGrupo_model->get_all_nivelGrupo($this->idContratante);
                    $idNvl = isset($idNvl) ? $idNvl : $nivels[0]['NgrId'];
                    $buscarGruposPredio = (int) $idNvl;
                } else {
                    $buscarGruposPredio = $this->input->post('buscarGruposPredio');
                    //$_SESSION['ordenD']['cicloNum'] = $_SESSION['ordenD']['buscarGruposPredio'];
                }
                if (isset($buscarGruposPredio)){

                    $_SESSION['ordenD']['buscarGruposPredio'] = $buscarGruposPredio;
                    //$_SESSION['ordenD']['cicloNum'] = $_SESSION['ordenD']['buscarGruposPredio'];
                    //verificar por ciclo
                    $nivelSuperior = $this->NivelGrupo_model->get_nivel_superior($idNivel)['NgrId'];
                    $Subcls = array_column($this->NivelGrupo_model->get_all_grupoPredio_x_periodo_x_nivelGrupo_distribucion($_SESSION['ordenD']['buscarGruposPredio'], $nivelSuperior, $_SESSION['ordenD']['periodo']), 'GprId');
                    if (empty($Subcls))                     {
                        $idSubciclos = array_column($this->NivelGrupo_model->get_all_grupoPredio_x_periodo_x_nivelGrupo_distribucion($_SESSION['ordenD']['buscarGruposPredio'], $nivelSuperior, $_SESSION['ordenD']['periodo']), 'GprId');
                    }                     else {
                        //verificar por subciclo
                        $idSubciclos = array_column($this->NivelGrupo_model->get_all_grupoPredio_x_periodo_x_nivelGrupo_distribucion($_SESSION['ordenD']['buscarGruposPredio'], $idNivel, $_SESSION['ordenD']['periodo']), 'GprId');
                    }
                    if (isset($idSubciclos) && is_array($idSubciclos)){
                        $subciclos = array();
                        foreach ($idSubciclos as $idSubciclo) {
                            $subciclo = $this->NivelGrupo_model->get_one_gprPredio($idSubciclo);
                            array_push($subciclos, $subciclo);
                        }
                        $this->data['subciclos'] = $subciclos;

                    }                     else                     {
                        $this->data['subciclos'] = $this->NivelGrupo_model->get_all_grupoPredio_x_idGrupoPredio($_SESSION['ordenD']['buscarGruposPredio']);
                    }
                }                 else                 {
                    $this->data['subciclos'] = $this->NivelGrupo_model->get_all_grupoPredio_x_idGrupoPredio($this->data['gruposPredio'][0]['GprId']);
                }
            }             else if ($cantidadSubniveles > 0)             {
                $this->data['gruposPredio'] = $this->NivelGrupo_model->get_all_grupoPredio_x_nivelGrupo($idNivel);
            }
            $_SESSION['ordenD']['tamanioMuestra'] = $this->get_tamanio_muestra_distribucion_supervision($_SESSION['ordenD']['lecturasAcumulado']);

            $Ordenes = $this->OTDistribucion_model->get_ordenes_trabajo_x_contrato_x_anio($this->idActividad, $idContrato, date('Y'));
            if (empty($Ordenes)) {
                $codOrden = str_pad(1, 6, '0', STR_PAD_LEFT);
            }else {
                $codOrden = str_pad(count($Ordenes) + 1, 6, '0', STR_PAD_LEFT);
            }
            $_SESSION['ordenD']['numero'] = date('Y') . $codOrden;

            $this->data['accion'] = 'nuevo';
            $this->data['view'] = 'distribucion/OTDistribucionContinua_uno_view';
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'distribucion/ordenes'), array('Registrar Orden de Trabajo de Distribucion de Recibos', ''));
            $this->load->view('template/Master', $this->data);
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'No tiene esta Actividad Contratada'));
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            redirect(base_url() . 'distribucion/ordenes');
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
                $resultado = $this->OTDistribucion_model->insert_orden_nivel_inferior($OrtNum, $OrtDes, $OrtObs, $OrtPrd, $OrtFchEj, $OrtFchEm, $OrtConId, $OrtEstId, $this->idActividad, $OrtUsrEn, $this->idContratante, $_SESSION['ordenD']['detalle'], $idBuscarGruposPredio, $idCiclo, $nivel, $supervision, $_SESSION['ordenD']['tamanioMuestra'] );
            }else {
                $resultado = $this->OTDistribucion_model->insert_orden($OrtNum, $OrtDes, $OrtObs, $OrtPrd, $OrtFchEj, $OrtFchEm, $OrtConId, $OrtEstId, $this->idActividad, $OrtUsrEn, $this->idContratante, $_SESSION['ordenD']['detalle'], $idCiclo, $nivel, $supervision, $_SESSION['ordenD']['tamanioMuestra']  );
            }

            if ($resultado) {
                if (isset($_SESSION['ordenD'])) {
                    unset($_SESSION['ordenD']);
                }
                if (isset($_SESSION['ordenP'])) {
                    unset($_SESSION['ordenP']);
                }
                if (isset($_SESSION['orden'])) {
                    unset($_SESSION['orden']);
                }
                $this->session->set_flashdata('mensaje', array('success', 'La Orden de trabajo de Distribucion de Recibos y Comunicaciones, se ha registrado satisfactoriamente.'));
                redirect(base_url() . 'distribucion/ordenes');
            }else {
                $this->session->set_flashdata('mensaje', array('error', 'La Orden de trabajo de Distribucion de Recibos y Comunicaciones no se ha podido registrar.'));
                redirect(base_url() . 'distribucion/orden/nuevo');
            }
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'Llene Correctamente los datos solicitados en el Formulario.'));
            redirect(base_url() . 'distribucion/orden/nuevo');
        }
    }
    
    // $this -> orden_nuevo
    public function eliminar_ciclo($cicloCod) {
        if (isset($_SESSION['ordenD']['detalle'][$cicloCod])) {
            $_SESSION['ordenD']['lecturasAcumulado'] -= $_SESSION['ordenD']['detalle'][$cicloCod]['lecturasRealizar'];
            $_SESSION['ordenD']['lecturistasAcumulado'] -= $_SESSION['ordenD']['detalle'][$cicloCod]['lecturistasUtilizados'];
            $_SESSION['ordenD']['lecturistasDisponibles'] = $_SESSION['ordenD']['totalLecturistas'] - $_SESSION['ordenD']['lecturistasAcumulado'];
            unset($_SESSION['ordenD']['detalle'][$cicloCod]);
        }
        redirect(base_url() . 'distribucion/orden/nuevo');
    }
    
    //$this -> orden_nuevo_ciclos
    public function insertar_ciclo($idgrupoPredio, $idContrato) {
        //dentro del ciclo ingresado
        $idPeriodo = $_SESSION['ordenD']['periodo'];
        $periodo = $this->Periodo_model->get_one_periodo($idPeriodo);
        
        $conexionesTotales = 0;
        $distribucionesRestantes = 0;
        
        if (is_array($idgrupoPredio))             {
            
            foreach ($idgrupoPredio as $idgrupoPredio2) {
                $conexionesTotales = $conexionesTotales + intval($this->Catastro_model->get_cant_conexiones_x_grupoPredio_x_mes($idgrupoPredio2, $periodo['PrdOrd'], $periodo['PrdAni']));
                $distribucionesRestantes = intval($this->Distribucion_model->get_cant_distribuciones_restantes_x_grupoPredio($periodo['PrdId'], $idgrupoPredio2, $conexionesTotales, $this->idContratante));
            }
        }
        else {
            $conexionesTotales = $this->Catastro_model->get_cant_conexiones_x_grupoPredio_x_mes($idgrupoPredio, $periodo['PrdOrd'], $periodo['PrdAni']);
            $distribucionesRestantes = $this->Distribucion_model->get_cant_distribuciones_restantes_x_grupoPredio($periodo['PrdId'], $idgrupoPredio, $conexionesTotales, $this->idContratante);
        }
        
        
        $filaInicio = $conexionesTotales - $distribucionesRestantes;
        
        if ($conexionesTotales > 0) {
            if ($distribucionesRestantes > 0) {
                $nroDia = 1;
                $nroSuborden = 1;
                while ($distribucionesRestantes > 0) {
                    $parts = explode('-', $_SESSION['ordenD']['fchEj']);
                    $fecha1 = strtotime($parts[0] . '-' . $parts[1] . '-' . $parts[2]);
                    $fechaEj = date('d/m/Y', strtotime('+' . ($nroDia - 1) . ' day', $fecha1));
                    //array de lecturistas
                    
                    $distribuidoresDisponibles = $this->Distribuidor_model->get_distribuidores_disponibles($this->idContratante, $idContrato, $fechaEj);
                    
                    if (count($distribuidoresDisponibles) > 0) {
                        $distribucionesMaximas = count($distribuidoresDisponibles) * $_SESSION['ordenD']['rendimiento'];
                        $distribucionesDia = 0;
                        if ($distribucionesRestantes <= $distribucionesMaximas) {
                            //se puede hacer completo lo que falta del ciclo
                            $distribucionesDia = $distribucionesRestantes;
                        } else {
                            //solo se puede hacer una parte de lo que falta
                            $distribucionesDia = $distribucionesMaximas;
                        }
                        $_SESSION['ordenD']['detalle'][$nroDia]['lecturistas'] = array_column($distribuidoresDisponibles, null, 'DbrId');
                        $_SESSION['ordenD']['detalle'][$nroDia]['lecturasRealizar'] = $distribucionesDia;
                        $_SESSION['ordenD']['detalle'][$nroDia]['fecha'] = $fechaEj;
                        $cantSubordenes = ceil($distribucionesDia / $_SESSION['ordenD']['rendimiento']);
                        $_SESSION['ordenD']['detalle'][$nroDia]['nroLecturistas'] = $cantSubordenes;
                        
                        $_SESSION['ordenD']['lecturasAcumulado'] += $distribucionesDia;

                        $_SESSION['ordenD']['subordenesAcumulado'] += $cantSubordenes;
                        if (!isset($_SESSION['ordenD']['detalle'][$nroDia]['aleatorio'])){
                            $_SESSION['ordenD']['detalle'][$nroDia]['aleatorio'] = true;
                        }
                        $_SESSION['ordenD']['detalle'][$nroDia]['conexionesTotales'] = $conexionesTotales;
                        $distribucionesRestantes -= $distribucionesDia;
                        //crear subordenes
                        $promedio = ceil($distribucionesDia / $cantSubordenes);
                        for ($i = 0; $i < $cantSubordenes; $i++) {
                            if ($promedio <= $distribucionesDia) {
                                $cantLecturas = $promedio;
                            } else {
                                $cantLecturas = $distribucionesDia;
                            }
                            $_SESSION['ordenD']['detalle'][$nroDia]['subordenes'][$nroSuborden]['cantidad'] = $cantLecturas;
                            $_SESSION['ordenD']['detalle'][$nroDia]['subordenes'][$nroSuborden]['idLecturista'] = 0;
                            $nroSuborden++;
                            $distribucionesDia -= $cantLecturas;
                        }
                        $nroDia++;
                    } else {
                        //no hay lecturistas disponibles
                        $this->session->set_flashdata('mensaje', array('error', 'No hay lecturistas disponibles para la fecha elegida.'));
                        $distribucionesRestantes = 0;
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
        $_SESSION['ordenD']['lecturistas'] = $this->Lecturista_model->get_lecturista_disponibles($idContratante, $idContrato, $fechaEj);
        $_SESSION['ordenD']['totalLecturistas'] = count($_SESSION['ordenD']['lecturistas']);
        $_SESSION['ordenD']['lecturasAcumulado'] = 0;
        $_SESSION['ordenD']['lecturistasAcumulado'] = 0;
        $_SESSION['ordenD']['lecturistasDisponibles'] = $_SESSION['ordenD']['totalLecturistas'];
    }
    
    // this-> orden_nuevo_detalle_ciclo()
    public function actualizar_ciclo($nroDia) {
        $aleatorio = $this->input->post('asignacion') == 0;
        $lecturistas = $_SESSION['ordenD']['detalle'][$nroDia]['lecturistas'];
        $subordenes = $_SESSION['ordenD']['detalle'][$nroDia]['subordenes'];
        $isOk = TRUE;
        if (!$aleatorio) {
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
                            $lecturistas[$idLecturista] = 0;
                            $isOk = FALSE;
                        }
                    } else {
                        $lecturistas[$idLecturista] = 0;
                        $isOk = FALSE;
                    }
                }
            }
            if ($isOk) {
                $_SESSION['ordenD']['detalle'][$nroDia]['subordenes'] = $subordenes;
                $_SESSION['ordenD']['detalle'][$nroDia]['aleatorio'] = FALSE;
            }
            return $isOk;
        }
    }

    public function orden_limpiar() {
        if (isset($_SESSION['ordenD'])) {
            unset($_SESSION['ordenD']);
        }
        redirect(base_url() . 'distribucion/orden/nuevo');
    }

    // this -> orden_nuevo
    public function limpiar_ciclo($idContrato) {
        $_SESSION['ordenD']['detalle'] = array();
        //numero de lecturas que puede hacer un lecturista segun contrato
        $rendimientosPorActividad = $this->Contrato_model->get_rendimientos_x_contrato_x_actividad($idContrato, $this->idActividad);
        $contrato = $this->Contrato_model->get_one_contrato($idContrato);
        $_SESSION['ordenD']['rendimiento'] = implode(",", max($rendimientosPorActividad));
        //nro de lecturistas activos registrado
        $_SESSION['ordenD']['totalLecturistas'] = $this->Distribuidor_model->get_cant_distribuidores_x_contrato($idContrato);
        $_SESSION['ordenD']['lecturasAcumulado'] = 0;
        $_SESSION['ordenD']['subordenesAcumulado'] = 0;
        $_SESSION['ordenD']['lecturistasDisponibles'] = $_SESSION['ordenD']['totalLecturistas'];
    }
    
    //distribucion/orden/nuevo/detalle_ciclo/(:num)
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
        $idPeriodo = $_SESSION['ordenD']['periodo'];
        $periodo = $this->Periodo_model->get_one_periodo($idPeriodo);

        $this->data['ciclo'] = $this->Catastro_model->get_one_grupoPredio_x_mes($nroDia, $periodo['PrdOrd'], $periodo['PrdAni']);
        
        $this->data['nroDia'] = $nroDia;
        $this->data['subordenes'] = $_SESSION['ordenD']['detalle'][$nroDia]['subordenes'];

        $this->data['view'] = 'distribucion/OTDistribucion_ciclos_detalle_view';
        
        $this->load->view('template/Reporte', $this->data);
    }
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="DETALLE ORDEN">
    public function orden_ver($idOrdenTrabajo) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_orden_distribucion');
        $OrdenTrabajo = $this->OTDistribucion_model->get_one_orden_trabajo($idOrdenTrabajo);
        if (!isset($OrdenTrabajo)) {
            show_404();
        }
        else{
        $this->data['OTTomaEstado'] = $this->OTDistribucion_model->get_one_orden_trabj_all_datos($idOrdenTrabajo);
        $idUsrEn = $this->data['OTTomaEstado']['OrtUsrEn'];
        $idUsrRs = $this->data['OTTomaEstado']['OrtUsrRs'];
        
        $this->data['subordenes'] = $this->Distribucion_model->get_detalle_suministros_lecturista($idOrdenTrabajo);
        
        $this->data['distribuidores'] = $this->Usuario_model->get_all_usuario_distribuidor();
        
        $this->data['Subciclos'] = $this->Distribucion_model->get_desc_ciclo_x_orden($idOrdenTrabajo);
        $this->data['LecSubCiclos'] = $this->Distribucion_model->get_lecturistas_x_ciclo_x_orden($idOrdenTrabajo);
        $this->data['UsrEn'] = $this->Usuario_model->get_one_usuario_de_contratante($idUsrEn);
        $this->data['UsrRs'] = $this->Usuario_model->get_one_usuario_de_contratista($idUsrRs);
        $parametro = $this->Parametro_model->get_one_detParam($this->data['OTTomaEstado']['OrtEstId']);
        $this->data['EstadoOrden'] = $parametro;
        $orden = $this->OTDistribucion_model->get_one_orden_trabajo_all_datos($idOrdenTrabajo);
        $cantidadSubniveles = $this->NivelGrupo_model->get_cant_subniveles($orden['OrtNgrId']);
        //ES EL ULTIMO NIVEL Y NO TIENE MAS SUBNIVELES
        if ($cantidadSubniveles == 0)         {
            $this->data['Nivel'] = $this->NivelGrupo_model->get_nivel_superior($orden['OrtNgrId']);
        }         else if ($cantidadSubniveles > 0)         {
            $this->data['Nivel'] = $this->NivelGrupo_model->get_one_nivelGrupo($orden['OrtNgrId']);
        }
        
        $this->data['GprNivel'] = $this->OTDistribucion_model->get_ciclo_orden_trabajo($idOrdenTrabajo);
        $this->data['accion'] = 'ver';
        $this->data['view'] = 'distribucion/OTDistribucion_ver_ciclos_view';
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'distribucion/ordenes'), array('Ver Orden de Trabajo', ''));
        
        $this->data['menu']['hijo'] = 'ordenes_distribucion';
        $this->load->view('template/Master', $this->data);
        }
    }

    public function orden_editar($idOrdenTrabajo) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_orden_distribucion');
        
        $ordenTrabajoEdit = $this->OTDistribucion_model->get_one_orden_trabajo($idOrdenTrabajo);
        if (!isset($ordenTrabajoEdit)) {
            show_404();
        }
        else{
        $periodo = $this->OTDistribucion_model->get_periodo_orden_trabajo($idOrdenTrabajo);
        $cicloOrden = $this->OTDistribucion_model->get_ciclo_orden_trabajo($idOrdenTrabajo);
       
        $parametro = $this->Parametro_model->get_one_detParam_x_codigo('no_rec');
        if ($ordenTrabajoEdit['OrtEstId'] != $parametro['DprId']) {
            $this->session->set_flashdata('mensaje', array('error', 'No se Puede Editar la Orden de Trabajo porque ya ha sido recibida.'));
            redirect(base_url() . 'distribucion/ordenes');
        } else {
            $this->data['ordenTrabajoEdit'] = $ordenTrabajoEdit;

            $SubOrdenes = $this->OTTomaEstado_cst_model->get_cantLec_por_suborden_por_ordenTrabajo_porDia($idOrdenTrabajo);
            foreach ($SubOrdenes as $nroSuborden => $suborden) {
                $SubOrdenes[$nroSuborden]['nroLecturistas'] = $this->OTTomaEstado_cst_model->get_cantSubords_por_ordenTrbj_porDia($idOrdenTrabajo,$SubOrdenes[$nroSuborden]['SolFchEj']);
            }
            $this->data['SubOrdenesDia'] = $SubOrdenes;
            $this->data['lecturistas'] = $this->Usuario_model->get_all_usuario_lecturista();
            $this->data['SubciclosOrden'] = $this->Distribucion_model->get_desc_ciclo_x_orden($idOrdenTrabajo);
            $this->data['LecSubCiclos'] = $this->Distribucion_model->get_lecturistas_x_ciclo_x_orden($idOrdenTrabajo);
            $this->data['periodo'] = $periodo;
            $this->data['cicloOrden'] = $cicloOrden;
            $this->data['cantidad'] = $this->Distribucion_model->get_cant_suborden_lextura($idOrdenTrabajo);
            $this->data['accion'] = 'editar';
            $this->data['view'] = 'distribucion/OTDistribucionContinua_uno_edit_view';
            $this->data['proceso'] = 'Toma de Estado';
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'distribucion/ordenes'), array('Editar Orden de Trabajo', ''));
            $this->load->view('template/Master', $this->data);
            if (isset($_POST['btn_guardar'])) {
                //boton guardar orden
                $this->orden_editar_guardar($idOrdenTrabajo);
            }
        }
        }
    }

    //distribucion/orden/editar/(:num)/guardar
    public function orden_editar_guardar($idOrdenTrabajo) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_orden_distribucion');
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
            $resp = $this->OTDistribucion_model->update_orden_trabajo_ciclos($OrtNum, $OrtDes, $OrtObs, $OrtFchEm, $OrtUsrEn, $idOrdenTrabajo);
            if ($resp) {
                $this->session->set_flashdata('mensaje', array('success', 'La Orden de Trabajo se ha actualizado Correctamente.'));
                $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                $this->data['accion'] = 'editar';
                $this->data['view'] = 'distribucion/OTDistribucionContinua_uno_edit_view';
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'distribucion/ordenes'), array('Editar Orden de Trabajo', ''));
                $this->load->view('template/Master', $this->data);
                redirect(base_url() . 'distribucion/orden/editar/' . $idOrdenTrabajo);
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al momento de Actualizar.'));
                $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                $this->data['accion'] = 'editar';
                $this->data['view'] = 'distribucion/OTDistribucionContinua_uno_edit_view';
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'distribucion/ordenes'), array('Editar Orden de Trabajo', ''));
                $this->load->view('template/Master', $this->data);
                redirect(base_url() . 'distribucion/orden/editar/' . $idOrdenTrabajo);
            }
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'Complete los campos.'));
            $this->data['accion'] = 'editar';
            $this->data['view'] = 'distribucion/OTDistribucionContinua_uno_edit_view';
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'distribucion/ordenes'), array('Editar Orden de Trabajo', ''));
            $this->load->view('template/Master', $this->data);
            redirect(base_url() . 'distribucion/orden/editar/' . $idOrdenTrabajo);
        }
    }


    // distribucion/get_detalle_x_suborden_json
    public function get_detalle_x_suborden_json() {

        $idCiclo = $_SESSION['ordenD']['gruposPredio'];

        $anio = $_SESSION['ordenD']['anio']; //2016
        $idPeriodo = $_SESSION['ordenD']['periodo'];
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
            #$inicio += count($_SESSION['ordenD']['detalle'][$nroDia]['subordenes']);
            $countOrdenes += count($_SESSION['ordenD']['detalle'][$i]['subordenes']);//consultar la cantidad de predios de la ultima orden del dia anterior
            for ($k; $k <= $countOrdenes; $k++) {
                $inicio += $_SESSION['ordenD']['detalle'][$i]['subordenes'][$k]['cantidad'];
                $j++;
            }
        }
        for ($j; $j < $nroSuborden; $j++) {
            $inicio += $_SESSION['ordenD']['detalle'][$nroDia]['subordenes'][$j]['cantidad']; //12250
        }
        $cantidad = $_SESSION['ordenD']['detalle'][$nroDia]['subordenes'][$nroSuborden]['cantidad'];


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
        $this->acceso_cls->verificarPermiso('eliminar_orden_distribucion');
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $this->data['ordenTrabajo'] = $this->OTDistribucion_model->get_one_orden_trabajo($idOrdenTrabajo);
        $this->OTDistribucion_model->delete_orden($idOrdenTrabajo);
        $this->session->set_flashdata('mensaje', array('success', 'La Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'] . ' se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'distribucion/ordenes');
    }
// </editor-fold>(VER / EDITAR / ELIMINAR )
    
// <editor-fold defaultstate="collapsed" desc="PENALIZACIONES DE LA ORDEN">

    /*  PENALIZACIONES DE LA ORDEN DE TRABAJO    */
    public function orden_penalizaciones($idOrdenTrabajo) {
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('penalizar_orden_distribucion');

        $this->data['ordenTrabajoEdit'] = $this->OTDistribucion_model->get_one_orden_trabajo($idOrdenTrabajo);
        $this->data['penalizaciones'] = $this->Penalizacion_model->get_all_penalizaciones_x_orden($idOrdenTrabajo);

        $this->data['accion'] = 'editar';
        $this->data['view'] = 'distribucion/OTsPenalizaciones_view';
        
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'distribucion/ordenes'), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajoEdit']['OrtNum'], ''));
        $this->load->view('template/Master', $this->data);
    }

    public function orden_penalizaciones_nuevo($idOrdenTrabajo) {
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('penalizar_orden_distribucion');

        $this->data['detallesContratoPenalidad'] = $this->Contrato_model->get_all_detalle_contrato_penalidad();

        $ordenTrabajo = $this->OTDistribucion_model->get_one_orden_trabajo($idOrdenTrabajo);
        $idContrato = $ordenTrabajo['OrtConId'];

        $this->data['gruposPenalidad'] = $this->Contrato_model->get_all_grupo_penalidad_x_contrato($idContrato);
        $this->data['penalidades'] = $this->Penalidad_model->get_penalidad_x_grupo($this->data['gruposPenalidad'][0]['GpeId']);


        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'distribucion/OTPenalizacion_view';
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'distribucion/ordenes'), array('Penalizaciones de la Orden de Trabajo N° ' . $ordenTrabajo['OrtNum'], 'distribucion/orden/' . $idOrdenTrabajo . '/penalizaciones'), array('Registrar Penalización', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function orden_penalizaciones_nuevo_guardar($idOrdenTrabajo) {
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['ordenTrabajo'] = $this->OTDistribucion_model->get_one_orden_trabajo($idOrdenTrabajo);
        $this->acceso_cls->verificarPermiso('penalizar_orden_distribucion');

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
                $this->data['view'] = 'distribucion/OTPenalizacion_view';
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'distribucion/ordenes'), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], 'distribucion/orden/' . $idOrdenTrabajo . '/penalizaciones'), array('Registrar Penalización', ''));
                redirect(base_url() . 'distribucion/orden/' . $idOrdenTrabajo . '/penalizaciones');
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algún error al momento de guardar.'));
                $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);

                $this->data['accion'] = 'nuevo';
                $this->data['view'] = 'distribucion/OTPenalizacion_view';
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'distribucion/ordenes'), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], 'distribucion/orden/' . $idOrdenTrabajo . '/penalizaciones'), array('Registrar Penalización', ''));
                redirect(base_url() . 'distribucion/orden/' . $idOrdenTrabajo . '/penalizacion/nuevo');
            }
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'Complete los campos'));

            $this->data['accion'] = 'nuevo';
            $this->data['view'] = 'distribucion/OTPenalizacion_view';
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'distribucion/ordenes'), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], 'distribucion/orden/' . $idOrdenTrabajo . '/penalizaciones'), array('Registrar Penalización', ''));
            $this->load->view('template/Master', $this->data);
            redirect(base_url() . 'distribucion/orden/' . $idOrdenTrabajo . '/penalizacion/nuevo');
        }
    }

    public function orden_penalizaciones_editar($idOrdenTrabajo, $idPenalizacion) {
        if (!isset($idOrdenTrabajo) && !isset($idPenalizacion)) {
            show_404();
        }
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('penalizar_orden_distribucion');

        $this->data['ordenTrabajo'] = $this->OTDistribucion_model->get_one_orden_trabajo($idOrdenTrabajo);
        $this->data['penalizacionEdit'] = $this->Penalizacion_model->get_one_penalizacion($idPenalizacion);

        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'distribucion/OTPenalizacion_view';
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'distribucion/ordenes'), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], 'distribucion/orden/' . $idOrdenTrabajo . '/penalizaciones'), array('Editar Penalización', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function orden_penalizaciones_editar_guardar($idOrdenTrabajo, $idPenalizacion) {
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('penalizar_orden_distribucion');
        $this->data['ordenTrabajo'] = $this->OTDistribucion_model->get_one_orden_trabajo($idOrdenTrabajo);

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
                $this->data['view'] = 'distribucion/OTPenalizacion_view';
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'distribucion/ordenes'), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], 'distribucion/orden/' . $idOrdenTrabajo . '/penalizaciones'), array('Editar Penalización', ''));
                redirect(base_url() . 'distribucion/orden/' . $idOrdenTrabajo . '/penalizacion/editar/' . $idPenalizacion);
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al momento de Actualizar el registro.'));
                $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);

                $this->data['accion'] = 'nuevo';
                $this->data['view'] = 'distribucion/OTPenalizacion_view';
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'distribucion/ordenes'), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], 'distribucion/orden/' . $idOrdenTrabajo . '/penalizaciones'), array('Editar Penalización', ''));
                redirect(base_url() . 'distribucion/orden/' . $idOrdenTrabajo . '/penalizacion/editar/' . $idPenalizacion);
            }
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'Complete los campos'));

            $this->data['accion'] = 'nuevo';
            $this->data['view'] = 'distribucion/OTPenalizacion_view';
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'distribucion/ordenes'), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], 'distribucion/orden/' . $idOrdenTrabajo . '/penalizaciones'), array('Editar Penalización', ''));
            $this->load->view('template/Master', $this->data);
            redirect(base_url() . 'distribucion/orden/' . $idOrdenTrabajo . '/penalizacion/editar/' . $idPenalizacion);
        }
    }

    public function orden_penalizaciones_eliminar($idOrdenTrabajo, $idPenalizacion) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('penalizar_orden_distribucion');
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $this->data['ordenTrabajo'] = $this->OTDistribucion_model->get_one_orden_trabajo($idOrdenTrabajo);
        $this->Penalizacion_model->delete_penalizacion($idPenalizacion);
        $this->session->set_flashdata('mensaje', array('success', 'La Penalización de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'] . ' se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'distribucion/orden/' . $idOrdenTrabajo . '/penalizaciones');
    }

// </editor-fold>
    

}
