<?php

class CronogramaDistribucion_cst_ctrllr extends CI_Controller {

    var $idActividad;
    var $idContratante;

    public function __construct() {
        parent::__construct();
        $this->load->model('distribucion/CronogramaDistribucion_model');
        $this->load->model('lectura/CronogramaLectura_model');
        $this->load->model('general/Periodo_model');
        $this->load->model('general/NivelGrupo_model');
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
        $this->acceso_cls->verificarPermisoCst('ver_cron_distribucion_periodo');
        $this->data['cronogramas'] = $this->CronogramaDistribucion_model->get_all_cronograma_periodo($this->data['userdata']['contratante']['CntId']);
        $this->data['view'] = 'contratista/distribucion/CronPeriodoDistribucion_list_cst_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas de Disctribución por Periodos', ''));
        $this->data['menu']['hijo'] = 'cronograma_distribucion_periodo';
        $this->load->view('contratista/template/Master_cst_view', $this->data);
    }

    public function cronograma_periodo_ver($idCronograma)
    {
        $this->acceso_cls->verificarPermisoCst('ver_cron_distribucion_periodo');
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
        $this->data['view'] = 'contratista/distribucion/CronPeriodoDistribucion_ver_cst_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas de Disctribución por Periodos', 'cst/distribucion/cronograma_periodo'), array('Cronograma de Disctribución por Periodos', ''));
        $this->data['menu']['hijo'] = 'cronograma_distribucion_periodo';
        $this->load->view('contratista/template/Master_cst_view', $this->data);
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
