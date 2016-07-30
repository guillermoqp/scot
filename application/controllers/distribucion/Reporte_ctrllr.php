<?php

class Reporte_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('distribucion/Reporte_model');
        $this->load->model('distribucion/CronogramaDistribucion_model');
        $this->load->model('lectura/CronogramaLectura_model');

        $this->load->library('acceso_cls');
        $this->load->library('utilitario_cls');
        $this->acceso_cls->isLogin();
        
        $this->data['userdata'] = $this->acceso_cls->get_userdata();
        $this->data['proceso'] = 'Distribucion de Recibos y Comunicaciones';

        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->idContratante = $this->data['userdata']['contratante']['CntId'];
        
        $this->idActividad = $this->Actividad_model->get_id_actividad_x_codigo('distribucion');
        $this->data['menu']['padre'] = 'distribucion';
        $this->data['menu']['hijo'] = 'ordenes_distribucion';
    }

    // distribucion/reportes/control/(:num)/(:num)
    public function control_distribucion($anio, $mes) {
        
        $this->acceso_cls->verificarPermiso('editar_cron_distribucion_periodo');
        $periodo = $this->Periodo_model->get_last_periodo_x_orden($mes, $anio);
        $periodo = $this->Periodo_model->get_one_periodo($periodo['PrdId']);
       
        $this->data['niveles'] = $this->NivelGrupo_model->get_all_nivelGrupo($this->idContratante);
        
        if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['nivel'])) {
            $idNivel = $this->input->post('nivel');
        }
        if(isset($idNivel))
        {
            $this->data['nivelSeleccionado'] = $this->NivelGrupo_model->get_one_nivelGrupo($idNivel);
        }
        $idNivel = isset($idNivel) ? $idNivel : $this->data['niveles'][0]['NgrId'];
        $this->data['idNivel'] = $idNivel;

        $ciclos = $this->Reporte_model->get_ciclos_control($idNivel , $periodo['PrdId'],$this->idContratante);
        $this->data['anios'] = $this->Periodo_model->get_anios($this->idContratante, $this->idActividad);
        $anio = $periodo['PrdAni'];
        $this->data['anio'] = $anio;
        $this->data['periodos'] = $this->Periodo_model->get_ordenes_x_anio($this->idContratante, $this->Actividad_model->get_id_actividad_x_codigo('toma_estado') , $anio);
        $this->data['periodo'] = $periodo['PrdCod'];
        $this->data['ciclos'] = $ciclos;
        
        $this->data['view'] = 'distribucion/ReporteControlDist_view';
        
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo de Distribucion de Recibos y Comunicaciones', 'distribucion/ordenes'),array('Control de Distribucion de Recibos y Comunicaciones', ''));
        $this->load->view('template/Master', $this->data);
    }

    // distribucion/reportes/control
    public function control_distribucion_redirigir() {
        
        $this->acceso_cls->verificarPermiso('editar_cron_distribucion_periodo');
        
        $anios = $this->CronogramaLectura_model->get_anios($this->idContratante);
        
        if(empty($anios)){
            $this->session->set_flashdata('mensaje', array('error', 'No existe Cronograma Anual de Toma de Estado'));
            redirect(base_url() . 'distribucion/ordenes');
        }
        $anio = date('Y');
        $mes = date('m');
        redirect(base_url() . 'distribucion/reportes/control/' . $anio . '/' . $mes);
    }

}
