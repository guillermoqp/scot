<?php

class CronogramaLectura_ctrllr extends CI_Controller {

    var $idActividad;
    var $idContratante;

    public function __construct() {
        parent::__construct();
        $this->load->model('lectura/CronogramaLectura_model');
        $this->load->model('catastro/Catastro_model');
        $this->load->model('planificacion/Actividad_model');
        
        $this->load->model('general/Periodo_model');
        
        $this->load->model('general/NivelGrupo_model');
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->load->library('utilitario_cls');
        
        $this->acceso_cls->isLogin();
        $this->data['userdata'] = $this->acceso_cls->get_userdata();
        $this->data['proceso'] = 'Toma de Estado';
        $this->data['menu']['padre'] = 'toma_estado';
        
        $this->idActividad = $this->Actividad_model->get_id_actividad_x_codigo('toma_estado');
        $this->idContratante = $this->data['userdata']['contratante']['CntId'];
        
    }

    //CRONOGRAMA ANUAL
    // toma_estado/cronograma_anual
    public function cronograma_anual_listar() {
        $this->acceso_cls->verificarPermiso('ver_cron_lectura_anual');
        $this->data['cronogramas'] = $this->CronogramaLectura_model->get_all_cronograma_anual($this->idContratante);
        $this->data['view'] = 'lectura/CronAnualLectura_lista_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas Anuales de Lectura', ''));
        $this->data['menu']['hijo'] = 'cronograma_anual';
        $this->load->view('template/Master', $this->data);
    }

    // toma_estado/cronograma_anual/nuevo/(:num)
    public function cronograma_anual_nuevo($anio) {
        $this->acceso_cls->verificarPermiso('editar_cron_lectura_anual');
        $ultimoPeriodo = $this->Periodo_model->get_ultimo_periodo($this->idActividad, $anio, $this->idContratante);
        
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            
            $nroPeriodos = $this->input->post('periodos');
            $idNivel = $this->input->post('nivel');
            
            if (isset($_POST['btn_guardar'])) { //guardar
                $grupos = $this->NivelGrupo_model->get_all_grupo_x_nivel_cantidad($idNivel, $ultimoPeriodo);
                // empieza validacion
                $this->load->library('form_validation');
                $this->form_validation->set_rules('anio', 'required');
                //$this->form_validation->set_rules('aprobado', 'required');
                // validar codigo de los periodos
                for ($i = 1; $i <= $nroPeriodos; $i++) {
                    $this->form_validation->set_rules('periodo_' . $i, 'Codigo', 'required');
                }
                // validar fechas y dias por ciclo
                foreach ($grupos as $grupo) {
                    for ($i = 1; $i <= $nroPeriodos; $i++) {
                        $this->form_validation->set_rules('dias_' . $grupo['GprId'] . '_' . $i, 'Dias', 'required');
                        $this->form_validation->set_rules('fch_' . $grupo['GprId'] . '_' . $i, 'Fecha', 'required');
                    }
                    //total de dias en el año por ciclo
                    $this->form_validation->set_rules('anio_' . $grupo['GprId'], 'Dias/Año', 'required');
                }

                if ($this->form_validation->run() == TRUE) {
                    $anio = $this->input->post('anio');
                    //$aprobado = $this->input->post('aprobado');
                    $aprobado = 1; // siempre aprobado
                    $cronograma = array();
                    $aprobado = ($aprobado == 1);
                    $diasAnio = ((($anio % 4) == 0) && ((($anio % 100) != 0) || (($anio % 400) == 0))) ? 366 : 365;
                    $completo = TRUE;
                    $periodos = array();
                    
                    for ($i = 1; $i <= $nroPeriodos; $i++) {
                        $periodos[$i] = $this->input->post('periodo_' . $i);
                    }
                    
                    foreach ($grupos as $grupo) {
                        $idGrupo =$grupo['GprId'];
                        $cronograma[$idGrupo] = array();
                        $total = 0;
                        for ($i = 1; $i <= $nroPeriodos; $i++) {
                            $dias = $this->input->post('dias_' . $idGrupo . '_' . $i);
                            $fecha = $this->input->post('fch_' . $idGrupo . '_' . $i);
                            $cronograma[$idGrupo][$i]['fecha'] = $fecha;
                            $cronograma[$idGrupo][$i]['dias'] = $dias;
                            $total = $total + $dias;
                        }
                        if ($total != $diasAnio) {
                            $completo = FALSE;
                        }
                    }
                    
                    if ($completo) {
                        $documento = $this->do_upload('documento');
                        $ruta = NULL;
                        if ($documento) {
                            $ruta = 'assets/uploads/cronograma/' . $documento;
                        }
                        
                        $this->CronogramaLectura_model->insert_cronograma_anual($anio, $cronograma, $periodos, $aprobado, $ruta, $idNivel, $this->idContratante, $this->idActividad);
                        $this->session->set_flashdata('mensaje', array('success', 'El Cronograma de Lectura se ha registrado satisfactoriamente.'));
                        redirect(base_url() . 'toma_estado/cronograma_anual');

                    } else {
                        $this->data['mensaje'] = array('error', 'Verifique que los días asignados coincidan con el total de días del año.');
                    }
                } else {
                    $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
                }
            }
        } //fin de lo que hace en post
        
        $anios = $this->CronogramaLectura_model->get_anios($this->idContratante);
        if (empty($anios)) {
            $anios = array(date('Y') + 1, date('Y'));
        } else {
            $anios = array($anios[0] + 1, $anios[0]);
        }
        
        $diasAnio = ((($anio % 4) == 0) && ((($anio % 100) != 0) || (($anio % 400) == 0))) ? 366 : 365;
//        $ultimoPeriodo = $this->Periodo_model->get_ultimo_periodo($this->idActividad, $anio, $this->idContratante);
        
        $this->data['ultimoPeriodo'] = $ultimoPeriodo;
        $this->data['ultimaLectura'] = $this->CronogramaLectura_model->get_ultima_lectura($ultimoPeriodo);
        $this->data['anios'] = array(date('Y') + 1, date('Y'));
        $this->data['anio'] = $anio;
        $this->data['diasAnio'] = $diasAnio;
        //$this->data['ciclos'] = $this->Catastro_model->get_all_ciclo_localidad();
        $niveles = $this->NivelGrupo_model->get_all_nivelGrupo($this->idContratante);
        $this->data['niveles'] = $niveles;
        $idNivel = isset($idNivel) ? $idNivel : $niveles[0]['NgrId'];
        if(isset($idNivel))
        {
            $this->data['nivelSeleccionado'] = $this->NivelGrupo_model->get_one_nivelGrupo($idNivel);
        }
        $this->data['idNivel'] = $idNivel;
        $this->data['gruposPredio'] = $this->NivelGrupo_model->get_all_grupo_x_nivel_cantidad($idNivel, $ultimoPeriodo);
        
        $nroPeriodos = isset($nroPeriodos) ? $nroPeriodos : 12;
        $this->data['periodos'] = $nroPeriodos;
        
        $periodosRegistrados = $this->Periodo_model->get_all_periodo($this->idActividad , $anio ,  $this->idContratante );
        if(count($periodosRegistrados) < $nroPeriodos )
        {
            $this->session->set_flashdata('mensaje', array('error', 'No existen Periodos Registrados para el año seleccionado o no existen Suficientes para el Cronograma.'));
            $this->data['periodosRegistrados'] = array();
        }
        else
        {
           $this->data['periodosRegistrados'] = $periodosRegistrados;
        }

        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'lectura/CronAnualLectura_uno_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas Anuales de Lectura', 'toma_estado/cronograma_anual'), array('Registrar Cronograma de Lectura', ''));
        $this->data['menu']['hijo'] = 'cronograma_anual';
        $this->load->view('template/Master', $this->data);
    }

    
    
    public function cronograma_anual_editar($idCronograma) {
        $this->acceso_cls->verificarPermiso('editar_cron_lectura_anual');
        $cronograma = $this->CronogramaLectura_model->get_one_cronograma_anual($idCronograma);
        if (!isset($cronograma)) {
            show_404();
        }
        $anio = $cronograma['ClaAni'];
        $diasAnio = ((($anio % 4) == 0) && ((($anio % 100) != 0) || (($anio % 400) == 0))) ? 366 : 365;
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $gruposPredio = $this->NivelGrupo_model->get_all_grupoPredio_x_nivelGrupo($cronograma['NgrId']);
            $periodos = $this->CronogramaLectura_model->get_periodos_x_cronograma_anual($idCronograma);
            
            foreach ($gruposPredio as $grupoPredio) {
                for ($i = 1; $i <= count($periodos); $i++) {
                    $this->form_validation->set_rules('dias_' . $grupoPredio['GprId'] . '_' . $i, 'Dias', 'required');
                    $this->form_validation->set_rules('fch_' . $grupoPredio['GprId'] . '_' . $i, 'Fecha', 'required');
                }
                $this->form_validation->set_rules('anio_' . $grupoPredio['GprId'], 'Dias/Año', 'required');
            }

            if ($this->form_validation->run() == TRUE) {
                $aprobado = 1; // siempre aprobado
                $cronogramaEdit = array();
                $aprobado = ($aprobado == 1);
                $completo = TRUE;
                foreach ($gruposPredio as $grupoPredio) {
                    $cronogramaEdit[$grupoPredio['GprId']] = array();
                    $total = 0;
                    for ($i = 1; $i <= count($periodos); $i++) {
                        $dias = $this->input->post('dias_' . $grupoPredio['GprId']  . '_' . $i);
                        $fecha = $this->input->post('fch_' . $grupoPredio['GprId']  . '_' . $i);
                        $cronogramaEdit[$grupoPredio['GprId'] ][$i]['fecha'] = $fecha;
                        $cronogramaEdit[$grupoPredio['GprId'] ][$i]['dias'] = $dias;
                        $total = $total + $dias;
                    }
                    if ($total != $diasAnio) {
                        $completo = FALSE;
                    }
                }
                if ($completo) {
                    $detalleCronograma = $this->CronogramaLectura_model->get_all_detalle_cronograma_anual_x_cronograma($idCronograma); 
                    $this->CronogramaLectura_model->update_cronograma_anual( $detalleCronograma , $cronogramaEdit, $aprobado, $anio, $idCronograma , $this->idContratante);
                    $this->session->set_flashdata('mensaje', array('success', 'El Cronograma de Lectura se ha actualizado satisfactoriamente.'));
                    redirect(base_url() . 'toma_estado/cronograma_anual');
                } else {
                    $this->session->set_flashdata('mensaje', array('error', 'Verifique que los días asignados coincidan con el total de días del año.'));
                }
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $anios = $this->CronogramaLectura_model->get_anios($this->data['userdata']['contratante']['CntId']);
        if (empty($anios)) {
            $anios = array(date('Y') + 1, date('Y'));
        } else {
            $anios = array($anios[0] + 1, $anios[0]);
        }
        $this->data['anio'] = $anio;
        $this->data['cronograma'] = $cronograma;
        $this->data['ultimaLectura'] = $this->CronogramaLectura_model->get_ultima_lectura_x_cronograma($idCronograma);
        $this->data['periodos'] = $this->CronogramaLectura_model->get_periodos_x_cronograma_anual($idCronograma);
        $this->data['diasAnio'] = $diasAnio;
        $ultimoPeriodo = $this->Periodo_model->get_ultimo_periodo($this->idActividad, $anio, $this->idContratante);
        $this->data['ultimoPeriodo'] = $ultimoPeriodo;
        $this->data['gruposPredio'] = $this->NivelGrupo_model->get_all_grupo_x_nivel_cantidad($cronograma['NgrId'], $ultimoPeriodo);
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'lectura/CronAnualLectura_uno_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas Anuales de Lectura', 'toma_estado/cronograma_anual'), array('Editar Cronograma de Lectura', ''));
        $this->data['menu']['hijo'] = 'cronograma_anual';
        $this->load->view('template/Master', $this->data);
    }

    public function cronograma_nuevo_redirigir() {
        $this->acceso_cls->verificarPermiso('editar_cron_lectura_anual');
        redirect(base_url() . 'toma_estado/cronograma_anual/nuevo/' . (date('Y') + 1));
    }

    public function cronograma_ver($idCronograma)
    {
        $this->acceso_cls->verificarPermiso('ver_cron_lectura_anual');
        $cronograma = $this->CronogramaLectura_model->get_one_cronograma_anual($idCronograma);
        if (!isset($cronograma)) {
            show_404();
        }
        $anio = $cronograma['ClaAni'];
        $anios = $this->CronogramaLectura_model->get_anios($this->data['userdata']['contratante']['CntId']);
        if (empty($anios)) {
            $anios = array(date('Y') + 1, date('Y'));
        } else {
            $anios = array($anios[0] + 1, $anios[0]);
        }
        $diasAnio = ((($anio % 4) == 0) && ((($anio % 100) != 0) || (($anio % 400) == 0))) ? 366 : 365;
        $this->data['anio'] = $anio;
        $this->data['cronograma'] = $cronograma;
        $this->data['ultimaLectura'] = $this->CronogramaLectura_model->get_ultima_lectura_x_cronograma($idCronograma);
        $this->data['periodos'] = $this->CronogramaLectura_model->get_periodos_x_cronograma_anual($idCronograma);
        $this->data['diasAnio'] = $diasAnio;
        $ultimoPeriodo = $this->Periodo_model->get_ultimo_periodo($this->idActividad, $anio, $this->idContratante);
        $this->data['ultimoPeriodo'] = $ultimoPeriodo;
        $this->data['gruposPredio'] = $this->NivelGrupo_model->get_all_grupo_x_nivel_cantidad($cronograma['NgrId'], $ultimoPeriodo);
        $this->data['accion'] = 'ver';
        $this->data['view'] = 'lectura/CronAnualLectura_ver_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas Anuales de Lectura', 'toma_estado/cronograma_anual'), array('Cronograma de Lectura', ''));
        $this->data['menu']['hijo'] = 'cronograma_anual';
        $this->load->view('template/Master', $this->data);
    }
    
    //CRONOGRAMA PERIODO

    public function cronograma_periodo_listar() {
        $this->acceso_cls->verificarPermiso('ver_cron_lectura_periodo');
        $this->data['cronogramas'] = $this->CronogramaLectura_model->get_all_cronograma_periodo($this->data['userdata']['contratante']['CntId']);
        $this->data['view'] = 'lectura/CronPeriodoLectura_list_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas de Lectura por Periodo', ''));
        $this->data['menu']['hijo'] = 'cronograma_periodo';
        $this->load->view('template/Master', $this->data);
    }

    public function cronograma_periodo_redirigir() {
        $this->acceso_cls->verificarPermiso('editar_cron_lectura_periodo');
        $cronActual = $this->CronogramaLectura_model->get_cronograma_anual_actual($this->data['userdata']['contratante']['CntId']);
        if ($cronActual) {
            $anio = $cronActual['ClaAni'];
            $mes = date('m') + 1;
            redirect(base_url() . 'toma_estado/cronograma_periodo/nuevo/' . $anio . '/' . $mes);
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'No hay un Cronograma de Toma de Estado(Lectura) Anual Vigente para este periodo.'));
            redirect(base_url() . 'toma_estado/cronograma_periodo');
        }
    }

    
    public function cronograma_periodo_nuevo($anio, $mes) {
       
        $this->acceso_cls->verificarPermiso('editar_cron_lectura_periodo');
        $ultimoPeriodo = $this->Periodo_model->get_ultimo_periodo($this->idActividad, $anio, $this->idContratante);
        $anios = $this->CronogramaLectura_model->get_anios($this->idContratante);
        if (empty($anios)) {
            $anios = array(date('Y') + 1, date('Y'));
        } else {
            $anios = array($anios[0] + 1, $anios[0]);
        }
        $this->data['lecturaAnual'] = $this->CronogramaLectura_model->get_lectura_anual_ciclos($anio, $mes,  $this->idContratante);
        $this->data['anios'] = $this->CronogramaLectura_model->get_anios( $this->idContratante);
        $this->data['anio'] = $anio;
        $this->data['mes'] = $mes;
        
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $idNivel = $this->input->post('nivel');
        }  
        $cronogramasAnuales = $this->CronogramaLectura_model->get_all_cronograma_anual_x_anio( $this->idContratante,$anio);
        $this->data['nivelesPosibles'] = array_column($cronogramasAnuales, 'NgrId');
        $niveles = $this->NivelGrupo_model->get_all_nivelGrupo($this->idContratante);
        $this->data['niveles'] = $niveles;
        
        $idNivel = isset($idNivel) ? $idNivel : $this->data['nivelesPosibles'][0];
        if(isset($idNivel))
        {
            $this->data['nivelSeleccionado'] = $this->NivelGrupo_model->get_one_nivelGrupo($idNivel);
        }
        $this->data['idNivel'] = $idNivel;
        
        
        if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['btn_guardar_cron_lectura_periodo'])) {
            $this->load->library('form_validation');
                    
            $gruposPredio = $this->NivelGrupo_model->get_all_grupo_x_nivel_cantidad($idNivel, $ultimoPeriodo);
            
            $this->form_validation->set_rules('anio', 'required');
            $this->form_validation->set_rules('mes', 'required');
            foreach ($gruposPredio as $grupoPredio) {
                $this->form_validation->set_rules('fchEntrega_' . $grupoPredio['GprId'], 'Fecha Entrega', 'required');
                $this->form_validation->set_rules('fchToma_' . $grupoPredio['GprId'], 'Fecha Toma', 'required');
                $this->form_validation->set_rules('diasToma_' . $grupoPredio['GprId'], 'Dias Toma', 'required');
                $this->form_validation->set_rules('fchCon_' . $grupoPredio['GprId'], 'Fecha Consistencia', 'required');
                $this->form_validation->set_rules('diasCon_' . $grupoPredio['GprId'], 'Dias Consistencia', 'required');
            }

            if ($this->form_validation->run() == TRUE) {
                $anio = $this->input->post('anio');
                $mes = $this->input->post('mes');
                $idNivel = $this->input->post('nivel');
                $periodoID = $this->Periodo_model->get_one_periodo_x_orden($mes, $anio)['PrdId'];
                $cronograma = array();
                foreach ($gruposPredio as $grupoPredio) {
                    $cronograma[$grupoPredio['GprId']]['fechaEntrega'] = $this->input->post('fchEntrega_' . $grupoPredio['GprId']);
                    $cronograma[$grupoPredio['GprId']]['fechaToma'] = $this->input->post('fchToma_' . $grupoPredio['GprId']);
                    $cronograma[$grupoPredio['GprId']]['diasToma'] = $this->input->post('diasToma_' . $grupoPredio['GprId']);
                    $cronograma[$grupoPredio['GprId']]['fechaCons'] = $this->input->post('fchCon_' . $grupoPredio['GprId']);
                    $cronograma[$grupoPredio['GprId']]['diasCons'] = $this->input->post('diasCon_' . $grupoPredio['GprId']);
                }
                $this->CronogramaLectura_model->insert_cronograma_periodo($anio, $periodoID, $cronograma, $this->idContratante , $idNivel);
                $this->session->set_flashdata('mensaje', array('success', 'El Cronograma de Lectura se ha registrado satisfactoriamente.'));
                redirect(base_url() . 'toma_estado/cronograma_periodo');
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        
        $this->data['NivelSeleccionado'] = $this->NivelGrupo_model->get_one_nivelGrupo($idNivel);
        $this->data['SubNivel'] = $this->NivelGrupo_model->get_subnivel_inferior($idNivel);
        $this->data['gruposPredio'] = $this->NivelGrupo_model->get_all_grupo_x_nivel_cantidad($idNivel, $ultimoPeriodo);

        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'lectura/CronPeriodoLectura_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas de Lectura por Periodo', 'toma_estado/cronograma_periodo'), array('Registrar Cronograma por Periodo de Lectura', ''));
        $this->data['menu']['hijo'] = 'cronograma_periodo';
        $this->load->view('template/Master', $this->data);
    }

    public function cronograma_periodo_editar($idCronograma) {
        $this->acceso_cls->verificarPermiso('editar_cron_lectura_periodo');
        $cronograma = $this->CronogramaLectura_model->get_one_cronograma_periodo($idCronograma);
        if (!isset($cronograma)) {
            show_404();
        }
        
        $periodo = $this->Periodo_model->get_one_periodo($cronograma['ClpPrdId']);
        if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['btn_guardar_cron_lectura_periodo']) ) {
            $this->load->library('form_validation');
            $idNivel = $cronograma['ClpNgrId'];
            $gruposPredio = $this->NivelGrupo_model->get_all_grupo_x_nivel_cantidad($idNivel, $periodo);
            $this->form_validation->set_rules('anio', 'required');
            $this->form_validation->set_rules('mes', 'required');
            foreach ($gruposPredio as $grupoPredio) {
                $this->form_validation->set_rules('fchEntrega_' . $grupoPredio['GprId'], 'Fecha Entrega', 'required');
                $this->form_validation->set_rules('fchToma_' . $grupoPredio['GprId'], 'Fecha Toma', 'required');
                $this->form_validation->set_rules('diasToma_' . $grupoPredio['GprId'], 'Dias Toma', 'required');
                $this->form_validation->set_rules('fchCon_' . $grupoPredio['GprId'], 'Fecha Consistencia', 'required');
                $this->form_validation->set_rules('diasCon_' . $grupoPredio['GprId'], 'Dias Consistencia', 'required');
            }

            if ($this->form_validation->run() == TRUE) {
//                $anio = $this->input->post('anio');
//                $mes = $this->input->post('mes');
                $cronograma = array();
                foreach ($gruposPredio as $grupoPredio) {
                    $cronograma[$grupoPredio['GprId']]['fechaEntrega'] = $this->input->post('fchEntrega_' . $grupoPredio['GprId']);
                    $cronograma[$grupoPredio['GprId']]['fechaToma'] = $this->input->post('fchToma_' . $grupoPredio['GprId']);
                    $cronograma[$grupoPredio['GprId']]['diasToma'] = $this->input->post('diasToma_' . $grupoPredio['GprId']);
                    $cronograma[$grupoPredio['GprId']]['fechaCons'] = $this->input->post('fchCon_' . $grupoPredio['GprId']);
                    $cronograma[$grupoPredio['GprId']]['diasCons'] = $this->input->post('diasCon_' . $grupoPredio['GprId']);
                }
                $this->CronogramaLectura_model->update_cronograma_periodo($idCronograma, $cronograma);
                $this->session->set_flashdata('mensaje', array('success', 'El Cronograma de Lectura se ha actualizado satisfactoriamente.'));
                redirect(base_url() . 'toma_estado/cronograma_periodo');
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        
        $this->data['lecturaAnual'] = $this->CronogramaLectura_model->get_lectura_anual_ciclos($cronograma['ClpAni'], $periodo["PrdOrd"], $this->data['userdata']['contratante']['CntId']);
        $this->data['cronograma'] = $cronograma['detalle'];
        $this->data['anio'] = $cronograma['ClpAni'];
        $this->data['mes'] = $periodo["PrdOrd"];
        $idNivel = $cronograma['ClpNgrId'];
        $ultimoPeriodo = $this->Periodo_model->get_ultimo_periodo($this->idActividad, $cronograma['ClpAni'] , $this->idContratante);
        
        $this->data['NivelSeleccionado'] = $this->NivelGrupo_model->get_one_nivelGrupo($idNivel);
        $this->data['SubNivel'] = $this->NivelGrupo_model->get_subnivel_inferior($idNivel);
        $this->data['gruposPredio'] = $this->NivelGrupo_model->get_all_grupo_x_nivel_cantidad($cronograma['ClpNgrId'], $ultimoPeriodo);
        
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'lectura/CronPeriodoLectura_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas de Lectura por Periodo', 'toma_estado/cronograma_periodo'), array('Editar Cronograma de Lectura por Periodo', ''));
        $this->data['menu']['hijo'] = 'cronograma_periodo';
        $this->load->view('template/Master', $this->data);
    }

    public function cronograma_periodo_ver($idCronograma)
    {
        $this->acceso_cls->verificarPermiso('ver_cron_lectura_periodo');
        $cronograma = $this->CronogramaLectura_model->get_one_cronograma_periodo($idCronograma);
        if (!isset($cronograma)) {
            show_404();
        }
        $periodo = $this->Periodo_model->get_one_periodo($cronograma['ClpPrdId']);
        $this->data['lecturaAnual'] = $this->CronogramaLectura_model->get_lectura_anual_ciclos($cronograma['ClpAni'], $periodo["PrdOrd"], $this->data['userdata']['contratante']['CntId']);
        $this->data['cronograma'] = $cronograma['detalle'];
        $this->data['anio'] = $cronograma['ClpAni'];
        $this->data['mes'] = $periodo["PrdOrd"];
        $idNivel = $cronograma['ClpNgrId'];
        $ultimoPeriodo = $this->Periodo_model->get_ultimo_periodo($this->idActividad, $cronograma['ClpAni'] , $this->idContratante);
        $this->data['NivelSeleccionado'] = $this->NivelGrupo_model->get_one_nivelGrupo($idNivel);
        $this->data['SubNivel'] = $this->NivelGrupo_model->get_subnivel_inferior($idNivel);
        $this->data['gruposPredio'] = $this->NivelGrupo_model->get_all_grupo_x_nivel_cantidad($cronograma['ClpNgrId'], $ultimoPeriodo);
        $this->data['accion'] = 'ver';
        $this->data['view'] = 'lectura/CronPeriodoLectura_ver_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas de Lectura por Periodo', 'toma_estado/cronograma_periodo'), array('Cronograma de Lectura por Periodos', ''));
        $this->data['menu']['hijo'] = 'cronograma_periodo';
        $this->load->view('template/Master', $this->data);
    }

    public function do_upload($field_name) {
        $this->load->library('upload');
        $this->load->helper('file');

        $image_upload_folder = FCPATH . 'assets/uploads/cronograma';

        if (!file_exists($image_upload_folder)) {
            mkdir($image_upload_folder, DIR_WRITE_MODE, true);
        }

        $this->upload_config = array(
            'upload_path' => $image_upload_folder,
            'allowed_types' => 'pdf',
            'max_size' => 2048,
            'remove_space' => TRUE,
            'encrypt_name' => TRUE,
        );
        $this->upload->initialize($this->upload_config);
        if (!$this->upload->do_upload($field_name)) {
            $upload_error = $this->upload->display_errors();
            /* print_r($upload_error);
              exit(); */
            return false;
        } else {
            $file_info = array($this->upload->data());
            return $file_info[0]['file_name'];
        }
    }

    // <editor-fold defaultstate="collapsed" desc="borrar">
    public function cronograma_pdf($idCronograma) {
        $this->acceso_cls->verificarPermiso('ver_cron_lectura_anual');
        $cronograma = $this->CronogramaLectura_model->get_one_cronograma_anual($idCronograma);
        if (!isset($cronograma)) {
            show_404();
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            
        }
        $this->data['ultimaLectura'] = $this->CronogramaLectura_model->get_ultima_lectura($cronograma['ClaAno']);
        $this->data['cronograma'] = $cronograma;
        $this->data['anio'] = $cronograma['ClaAno'];
        $this->data['ciclos'] = $this->Catastro_model->get_all_ciclo_localidad();
        $this->data['view'] = 'lectura/CronogramaLectura_ver_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas Anuales de Lectura', 'toma_estado/cronograma_anual'), array('Editar Cronograma de Lectura', ''));
//        $this->load->view('template/Master', $this->data);
//        $data = [];
        //load the view and saved it into $html variable
        $html = $this->load->view('lectura/CronogramaLectura_ver_view', $this->data, true);

        //this the the PDF filename that user will get to download
        $pdfFilePath = "cronograma.pdf";

        //load mPDF library
        $params = array('"", "A4-L"');
        $this->load->library('pdf_cls', $params);

        //generate the PDF from the given html
        $this->pdf_cls->pdf->WriteHTML($html);

        //download it.
        $this->pdf_cls->pdf->Output($pdfFilePath, "I");
    }

// </editor-fold>
}
