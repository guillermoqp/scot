<?php

class CronogramaDistribucion_ctrllr extends CI_Controller {

    var $idActividad;
    var $idContratante;

    public function __construct() {
        parent::__construct();
        $this->load->model('distribucion/CronogramaDistribucion_model');
        $this->load->model('lectura/CronogramaLectura_model');
        $this->load->model('general/Periodo_model');
        $this->load->model('general/NivelGrupo_model');
        $this->load->model('catastro/Catastro_model');
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->load->library('utilitario_cls');
        $this->acceso_cls->isLogin();
        $this->data['userdata'] = $this->acceso_cls->get_userdata();
        $this->data['proceso'] = 'Distribución de Recibos y Comunicaciones al Cliente';
        $this->data['menu']['padre'] = 'distribucion';
        $this->idActividad = $this->Actividad_model->get_id_actividad_x_codigo('distribucion');
        $this->idContratante = $this->data['userdata']['contratante']['CntId'];
    }
    
    //CRONOGRAMA PERIODO

    public function cronograma_periodo_listar() {
        $this->acceso_cls->verificarPermiso('ver_cron_distribucion_periodo');
        $this->data['cronogramas'] = $this->CronogramaDistribucion_model->get_all_cronograma_periodo($this->data['userdata']['contratante']['CntId']);
        $this->data['view'] = 'distribucion/CronPeriodoDistribucion_list_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas de Disctribución por Periodos', ''));
        $this->data['menu']['hijo'] = 'cronograma_distribucion_periodo';
        $this->load->view('template/Master', $this->data);
    }

    public function cronograma_periodo_redirigir() {
        $this->acceso_cls->verificarPermiso('editar_cron_distribucion_periodo');
        $cronActual = $this->CronogramaLectura_model->get_cronograma_anual_actual($this->data['userdata']['contratante']['CntId']);
        if ($cronActual) {
            $anio = $cronActual['ClaAni'];
            $mes = date('m') + 1;
            $periodo = $this->Periodo_model->get_one_periodo_PrdOrd($mes);
            $cronPeriodo = $this->CronogramaLectura_model->get_cronograma_periodo_actual($this->data['userdata']['contratante']['CntId'],$periodo['PrdId']);
            if(empty($cronPeriodo)){
                $this->session->set_flashdata('mensaje', array('error', 'No se puede crear un Cronograma Periodo de Distribución si no hay un Cronograma Periodo de Lectura.'));
                redirect(base_url() . 'distribucion/cronograma_periodo');
            }
            else{
                redirect(base_url() . 'distribucion/cronograma_periodo/nuevo/' . $anio . '/' . $mes);
            }
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'No hay un Cronograma Anual de Toma de Estado (Lecturas) Vigente para este periodo.'));
            redirect(base_url() . 'distribucion/cronograma_periodo');
        }
    }
    
    public function cronograma_periodo_nuevo($anio, $mes) {
       
        $this->acceso_cls->verificarPermiso('editar_cron_distribucion_periodo');
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
                $this->form_validation->set_rules('diasCrg_' . $grupoPredio['GprId'], 'Días Carga', 'required');
                $this->form_validation->set_rules('fchCal_' . $grupoPredio['GprId'], 'Fecha Calculo', 'required');
                $this->form_validation->set_rules('fchImp_' . $grupoPredio['GprId'], 'Fecha Impresión', 'required');
                $this->form_validation->set_rules('diasImp_' . $grupoPredio['GprId'], 'Días Impresión', 'required');
                $this->form_validation->set_rules('fchDis_' . $grupoPredio['GprId'], 'Fecha Distribución', 'required');
            }

            if ($this->form_validation->run() == TRUE) {
                $anio = $this->input->post('anio');
                $mes = $this->input->post('mes');
                $idNivel = $this->input->post('nivel');
                $periodoID = $this->Periodo_model->get_one_periodo_x_orden($mes, $anio)['PrdId'];
                $cronograma = array();
                foreach ($gruposPredio as $grupoPredio) {
                    $cronograma[$grupoPredio['GprId']]['diasCrg'] = $this->input->post('diasCrg_' . $grupoPredio['GprId']);
                    $cronograma[$grupoPredio['GprId']]['fchCal'] = $this->input->post('fchCal_' . $grupoPredio['GprId']);
                    $cronograma[$grupoPredio['GprId']]['fchImp'] = $this->input->post('fchImp_' . $grupoPredio['GprId']);
                    $cronograma[$grupoPredio['GprId']]['diasImp'] = $this->input->post('diasImp_' . $grupoPredio['GprId']);
                    $cronograma[$grupoPredio['GprId']]['fchDis'] = $this->input->post('fchDis_' . $grupoPredio['GprId']);
                }
                $this->CronogramaDistribucion_model->insert_cronograma_periodo($anio, $periodoID, $cronograma, $this->idContratante , $idNivel);
                $this->session->set_flashdata('mensaje', array('success', 'El Cronograma de Distribución de Recibos y Comunicaciones al Cliente se ha registrado satisfactoriamente.'));
                redirect(base_url() . 'distribucion/cronograma_periodo');
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['NivelSeleccionado'] = $this->NivelGrupo_model->get_one_nivelGrupo($idNivel);
        $this->data['SubNivel'] = $this->NivelGrupo_model->get_subnivel_inferior($idNivel);
        $this->data['gruposPredio'] = $this->NivelGrupo_model->get_all_grupo_x_nivel_cantidad($idNivel, $ultimoPeriodo);

        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'distribucion/CronPeriodoDistribucion_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas de Disctribución por Periodos', 'distribucion/cronograma_periodo'), array('Registrar Cronograma por Periodo de Distribución', ''));
        $this->data['menu']['hijo'] = 'cronograma_distribucion_periodo';
        $this->load->view('template/Master', $this->data);
    }

    public function cronograma_periodo_editar($idCronograma) {
        $this->acceso_cls->verificarPermiso('editar_cron_distribucion_periodo');
        $cronograma = $this->CronogramaDistribucion_model->get_one_cronograma_periodo($idCronograma);
        if (!isset($cronograma)) {
            show_404();
        }
        
        $periodo = $this->Periodo_model->get_one_periodo($cronograma['CdpPrdId']);
        if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['btn_guardar_cron_distribucion_periodo']) ) {
            $this->load->library('form_validation');
            $idNivel = $cronograma['CdpNgrId'];
            $gruposPredio = $this->NivelGrupo_model->get_all_grupo_x_nivel_cantidad($idNivel, $periodo);
            $this->form_validation->set_rules('anio', 'required');
            $this->form_validation->set_rules('mes', 'required');
            foreach ($gruposPredio as $grupoPredio) {
                $this->form_validation->set_rules('diasCrg_' . $grupoPredio['GprId'], 'Días Carga', 'required');
                $this->form_validation->set_rules('fchCal_' . $grupoPredio['GprId'], 'Fecha Calculo', 'required');
                $this->form_validation->set_rules('fchImp_' . $grupoPredio['GprId'], 'Fecha Impresión', 'required');
                $this->form_validation->set_rules('diasImp_' . $grupoPredio['GprId'], 'Días Impresión', 'required');
                $this->form_validation->set_rules('fchDis_' . $grupoPredio['GprId'], 'Fecha Distribución', 'required');
            }

            if ($this->form_validation->run() == TRUE) {
//                $anio = $this->input->post('anio');
//                $mes = $this->input->post('mes');
                $cronograma = array();
                foreach ($gruposPredio as $grupoPredio) {
                    $cronograma[$grupoPredio['GprId']]['diasCrg'] = $this->input->post('diasCrg_' . $grupoPredio['GprId']);
                    $cronograma[$grupoPredio['GprId']]['fchCal'] = $this->input->post('fchCal_' . $grupoPredio['GprId']);
                    $cronograma[$grupoPredio['GprId']]['fchImp'] = $this->input->post('fchImp_' . $grupoPredio['GprId']);
                    $cronograma[$grupoPredio['GprId']]['diasImp'] = $this->input->post('diasImp_' . $grupoPredio['GprId']);
                    $cronograma[$grupoPredio['GprId']]['fchDis'] = $this->input->post('fchDis_' . $grupoPredio['GprId']);
                }
                $this->CronogramaDistribucion_model->update_cronograma_periodo($idCronograma, $cronograma);
                $this->session->set_flashdata('mensaje', array('success', 'El Cronograma de Distribución de Recibos y Comunicaciones al Cliente se ha actualizado satisfactoriamente.'));
                redirect(base_url() . 'distribucion/cronograma_periodo');
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        
        $this->data['lecturaAnual'] = $this->CronogramaLectura_model->get_lectura_anual_ciclos($cronograma['CdpAni'], $periodo["PrdOrd"], $this->data['userdata']['contratante']['CntId']);
        $this->data['cronograma'] = $cronograma['detalle'];
        $this->data['anio'] = $cronograma['CdpAni'];
        $this->data['mes'] = $periodo["PrdOrd"];
        $idNivel = $cronograma['CdpNgrId'];
        $ultimoPeriodo = $this->Periodo_model->get_ultimo_periodo($this->idActividad, $cronograma['CdpAni'] , $this->idContratante);
        
        $this->data['NivelSeleccionado'] = $this->NivelGrupo_model->get_one_nivelGrupo($idNivel);
        $this->data['SubNivel'] = $this->NivelGrupo_model->get_subnivel_inferior($idNivel);
        $this->data['gruposPredio'] = $this->NivelGrupo_model->get_all_grupo_x_nivel_cantidad($cronograma['CdpNgrId'], $ultimoPeriodo);
        
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'distribucion/CronPeriodoDistribucion_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas de Disctribución por Periodos', 'distribucion/cronograma_periodo'), array('Editar Cronograma de Distribución de Recibos y Comunicaciones al Cliente por Periodo', ''));
        $this->data['menu']['hijo'] = 'cronograma_distribucion_periodo';
        $this->load->view('template/Master', $this->data);
    }

    public function cronograma_periodo_ver($idCronograma)
    {
        $this->acceso_cls->verificarPermiso('ver_cron_distribucion_periodo');
        $cronograma = $this->CronogramaDistribucion_model->get_one_cronograma_periodo($idCronograma);
        if (!isset($cronograma)) {
            show_404();
        }
        $periodo = $this->Periodo_model->get_one_periodo($cronograma['CdpPrdId']);
        $this->data['lecturaAnual'] = $this->CronogramaLectura_model->get_lectura_anual_ciclos($cronograma['CdpAni'], $periodo["PrdOrd"], $this->data['userdata']['contratante']['CntId']);
        $this->data['cronograma'] = $cronograma['detalle'];
        $this->data['anio'] = $cronograma['CdpAni'];
        $this->data['mes'] = $periodo["PrdOrd"];
        $idNivel = $cronograma['CdpNgrId'];
        $ultimoPeriodo = $this->Periodo_model->get_ultimo_periodo($this->idActividad, $cronograma['CdpAni'] , $this->idContratante);
        $this->data['NivelSeleccionado'] = $this->NivelGrupo_model->get_one_nivelGrupo($idNivel);
        $this->data['SubNivel'] = $this->NivelGrupo_model->get_subnivel_inferior($idNivel);
        $this->data['gruposPredio'] = $this->NivelGrupo_model->get_all_grupo_x_nivel_cantidad($cronograma['CdpNgrId'], $ultimoPeriodo);
        $this->data['accion'] = 'ver';
        $this->data['view'] = 'distribucion/CronPeriodoDistribucion_ver_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas de Disctribución por Periodos', 'distribucion/cronograma_periodo'), array('Cronograma de Disctribución por Periodos', ''));
        $this->data['menu']['hijo'] = 'cronograma_distribucion_periodo';
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

}
