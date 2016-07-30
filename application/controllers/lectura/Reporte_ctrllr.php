<?php

class Reporte_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('lectura/Reporte_model');
        $this->load->model('lectura/CronogramaLectura_model');
        $this->load->library('acceso_cls');
        $this->load->library('utilitario_cls');
        $this->acceso_cls->isLogin();
        $this->data['userdata'] = $this->acceso_cls->get_userdata();
        $this->data['proceso'] = 'Toma de Estado';

        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->idContratante = $this->data['userdata']['contratante']['CntId'];
        $this->idActividad = $this->Actividad_model->get_id_actividad_x_codigo('toma_estado');
        $this->data['menu']['padre'] = 'toma_estado';
        $this->data['menu']['hijo'] = 'ordenes';
    }

    // toma_estado/reportes/control/(:num)/(:num)
    public function control_lectura($anio, $mes) {
        $this->acceso_cls->verificarPermiso('editar_cron_lectura_periodo');
        $idContratante = $this->data['userdata']['contratante']['CntId'];
        $periodo = $this->Periodo_model->get_last_periodo_x_orden($mes, $anio);
        
        
        $periodo = $this->Periodo_model->get_one_periodo($periodo['PrdId']);
        $this->data['niveles'] = $this->NivelGrupo_model->get_all_nivelGrupo($this->idContratante);
        $idNivel = isset($idNivel) ? $idNivel : $this->data['niveles'][0]['NgrId'];
        $this->data['idNivel'] = $idNivel;
        
        if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['nivel'])) {
            $idNivel = $this->input->post('nivel');
        }
        if(isset($idNivel))
        {
            $this->data['nivelSeleccionado'] = $this->NivelGrupo_model->get_one_nivelGrupo($idNivel);
        }
        $ciclos = $this->Reporte_model->get_ciclos_control($idNivel , $periodo['PrdId'], $idContratante);

        $this->data['anios'] = $this->Periodo_model->get_anios($this->idContratante, $this->idActividad);
        
        
        $anio = $periodo['PrdAni'];
        $this->data['anio'] = $anio;
        $this->data['meses'] = $this->Periodo_model->get_ordenes_x_anio($this->idContratante, $this->idActividad, $anio);
        $this->data['mes'] = $periodo['PrdCod'];
        $this->data['ciclos'] = $ciclos;
        $this->data['view'] = 'lectura/ReporteControlLect_view';
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'),array('Control de Lecturas', ''));
        $this->load->view('template/Master', $this->data);
    }

    // toma_estado/reportes/control
    public function control_lectura_redirigir() {
        $this->acceso_cls->verificarPermiso('editar_cron_lectura_periodo');
        $anios = $this->CronogramaLectura_model->get_anios($this->idContratante);
        if(empty($anios)){
            $this->session->set_flashdata('mensaje', array('error', 'No existe Cronograma Anual'));
            redirect(base_url() . 'toma_estado/ordenes');
        }
        $anio = date('Y');
        $mes = date('m');
        redirect(base_url() . 'toma_estado/reportes/control/' . $anio . '/' . $mes);
    }

}
