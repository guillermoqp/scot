<?php

class CronogramaLectura_cst_ctrllr extends CI_Controller {
    var $idActividad;
    var $idContratante;
    public function __construct() {
        parent::__construct();
        $this->load->model('lectura/CronogramaLectura_model');
        $this->load->model('catastro/Catastro_model');
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

    public function cronograma_anual_listar() 
    {
        $this->acceso_cls->verificarPermiso('ver_cron_lectura_anual');
        $this->data['cronogramas'] = $this->CronogramaLectura_model->get_all_cronograma_anual($this->data['userdata']['contratante']['CntId']);
        $this->data['view'] = 'contratista/lectura/CronogramasLectura_cst_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas Anuales de Lectura', ''));
        $this->data['menu']['hijo'] = 'cronograma_anual';
        $this->load->view('contratista/template/Master_cst_view', $this->data);
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
        $this->data['ultimaLectura'] = $this->CronogramaLectura_model->get_ultima_lectura_x_cronograma($idCronograma);
        $this->data['cronograma'] = $cronograma;
        $this->data['anio'] =  $anio;
        $this->data['diasAnio'] = $diasAnio;
        $ultimoPeriodo = $this->Periodo_model->get_ultimo_periodo($this->idActividad, $anio, $this->idContratante);
        $this->data['ultimoPeriodo'] = $ultimoPeriodo;
        $this->data['periodos'] = $this->CronogramaLectura_model->get_periodos_x_cronograma_anual($idCronograma);
        $this->data['gruposPredio'] = $this->NivelGrupo_model->get_all_grupo_x_nivel_cantidad($cronograma['NgrId'], $ultimoPeriodo, $this->idContratante);
        $this->data['accion'] = 'ver';
        $this->data['view'] = 'contratista/lectura/CronogramaLectura_ver_cst_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas Anuales de Lectura', 'cst/toma_estado/cronograma_anual'), array('Cronograma de Lectura', ''));
        $this->data['menu']['hijo'] = 'cronograma_anual';
        $this->load->view('contratista/template/Master_cst_view', $this->data);
    }

    //CRONOGRAMA MENSUAL

    public function cronograma_periodo_listar() 
    {
        $this->acceso_cls->verificarPermiso('ver_cron_lectura_periodo');
        $this->data['cronogramas'] = $this->CronogramaLectura_model->get_all_cronograma_periodo($this->data['userdata']['contratante']['CntId']);
        $this->data['view'] = 'contratista/lectura/CronPeriodoLectura_list_cst_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas de Lectura por periodo', ''));
        $this->data['menu']['hijo'] = 'cronograma_periodo';
        $this->load->view('contratista/template/Master_cst_view', $this->data);
    }

    public function cronograma_periodo_ver($idCronograma) 
    {
        $this->acceso_cls->verificarPermiso('ver_cron_lectura_periodo');
        $cronograma = $this->CronogramaLectura_model->get_one_cronograma_periodo($idCronograma);
        if (!isset($cronograma)) {
            show_404();
        }
        $periodo = $this->Periodo_model->get_one_periodo($cronograma['ClpPrdId']);
        $this->data['lecturaAnual'] = $this->CronogramaLectura_model->get_lectura_anual_ciclos($cronograma['ClpAni'], $periodo["PrdOrd"] , $this->data['userdata']['contratante']['CntId']);
        $this->data['cronograma'] = $cronograma['detalle'];
        $this->data['anio'] = $cronograma['ClpAni'];
        $this->data['mes'] = $periodo["PrdOrd"];
        $idNivel = $cronograma['ClpNgrId'];
        $ultimoPeriodo = $this->Periodo_model->get_ultimo_periodo($this->idActividad, $cronograma['ClpAni'] , $this->idContratante);
        $this->data['NivelSeleccionado'] = $this->NivelGrupo_model->get_one_nivelGrupo($idNivel);
        $this->data['SubNivel'] = $this->NivelGrupo_model->get_subnivel_inferior($idNivel);
        $this->data['gruposPredio'] = $this->NivelGrupo_model->get_all_grupo_x_nivel_cantidad($cronograma['ClpNgrId'], $ultimoPeriodo, $this->idContratante);
        $this->data['accion'] = 'ver';
        $this->data['view'] = 'contratista/lectura/CronPeriodoLectura_ver_cst_view';
        $this->data['breadcrumbs'] = array(array('Cronogramas Lectura por Periodos', 'cst/toma_estado/cronograma_periodo'), array('Cronograma de Lectura por Periodo', ''));
        $this->data['menu']['hijo'] = 'cronograma_periodo';
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
