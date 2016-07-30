<?php

class Inicio_cst_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('contratista/lectura/OTTomaEstado_cst_model');
        $this->load->model('acceso/Usuario_model');
        $this->load->model('lectura/Lecturista_model');
        $this->load->model('lectura/Lectura_model');

        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->load->library('utilitario_cls');
        $this->acceso_cls->isLogin();

        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->idContratante = $this->data['userdata']['contratante']['CntId'];
        $this->idActividad = $this->Actividad_model->get_id_actividad_x_codigo('toma_estado');
    }

    public function inicio() 
    {
        $this->acceso_cls->isLogin();
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        if (is_null($this->data['userdata']['UsrCstId'])) 
        {
            redirect(base_url() . 'inicio');
        } else {
            $bienvenido = $this->input->get('bienvenido');
            if (isset($bienvenido)) {
                $dias = (strtotime("now") - strtotime($this->data['userdata']['UsrFchEx'])) / 86400;
                $dias = abs($dias);
                $dias = floor($dias) + 1;
                if ($dias < 15) {
                    $this->session->set_flashdata('mensaje', array('error', 'Le quedan : ' . $dias . ' dias de acceso al sistema.'));
                }
            }
            $esLecturista = $this->Usuario_model->es_lecturista($this->data['userdata']['UsrId']);
            if($esLecturista)
            {
                //date("Y-m-d");  
                $this->data['subordenes'] = $this->Lectura_model->get_subordenes_x_fechaEj_x_lecturista('2016-07-21', $esLecturista['LtaId']);
                $this->data['view'] = 'contratista/acceso/Inicio_Lecturista_cst_view';
                $this->data['menu']['padre'] = 'toma_estado';
                $this->data['menu']['hijo'] = 'ordenes';
                $this->data['proceso'] = 'SubOrdenes de Trabajo del Dia';
                $this->data['breadcrumbs'] = array();
                $this->load->view('contratista/template/Master_cst_view', $this->data);
            }
            else
            {
                $this->data['view'] = 'contratista/acceso/Inicio_cst_view';
                $this->data['proceso'] = 'Administración General';
                $this->data['menu']['padre'] = '';
                $this->data['menu']['hijo'] = '';
                $this->data['breadcrumbs'] = array();
                $this->load->view('contratista/template/Master_cst_view', $this->data);
            }
        }
    }

    public function sinPermiso() 
    {
        $this->acceso_cls->isLogin();
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['proceso'] = 'Administración General';
        $this->data['menu']['padre'] = '';
        $this->data['menu']['hijo'] = '';
        $this->data['mensaje'] = array('error', 'No tiene el permiso para ver la interfaz.');
        $this->data['breadcrumbs'] = array();
        $this->data['view'] = 'acceso/401';
        $this->load->view('contratista/template/Master_cst_view', $this->data);
    }

}
