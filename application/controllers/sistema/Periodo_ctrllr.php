<?php

class Periodo_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('general/Periodo_model');
        $this->load->model('general/Actividad_model');     
        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();

        $this->data['menu']['padre'] = 'configuracion_global';
        $this->data['menu']['hijo'] = 'periodos';
        
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->idContratante = $this->data['userdata']['contratante']['CntId'];
        $this->idActividad = $this->Actividad_model->get_id_actividad_x_codigo('toma_estado');
        
    }

    public function periodos_listar() {
        $this->acceso_cls->verificarPermiso('ver_periodo');
        $this->data['periodos'] = $this->Periodo_model->get_all_periodo_listar($this->idActividad, $this->idContratante);
        $this->data['view'] = 'sistema/Periodos_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Periodos', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function periodo_nuevo() {
        $this->acceso_cls->verificarPermiso('editar_periodo');
        
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('PrdCod', 'Nombre', 'required');
            $this->form_validation->set_rules('PrdAni', 'Anio', 'required');
            $this->form_validation->set_rules('PrdOrd', 'Orden', 'required');
            $this->form_validation->set_rules('PrdFchIn', 'Fecha Inicio', 'required');
            $this->form_validation->set_rules('PrdFchFn', 'Fecha Fin', 'required');
            
            if ($this->form_validation->run() == TRUE) {
                $PrdCod = $this->input->post('PrdCod');
                $PrdAni = $this->input->post('PrdAni');
                $PrdOrd = $this->input->post('PrdOrd');
                $PrdFchIn = $this->input->post('PrdFchIn');
                $PrdFchFn = $this->input->post('PrdFchFn');
                $periodos = $this->Periodo_model->get_all_periodo($this->idActividad, $PrdAni, $this->idContratante);
                $ordPeriodos = array_column($periodos, 'PrdOrd');
                $periodosRango = $this->Periodo_model->get_all_periodo_por_rangoFechas($this->idActividad, $PrdAni, $this->idContratante, $PrdFchIn, $PrdFchFn);
                if (in_array($PrdOrd, $ordPeriodos)) {
                    $this->session->set_flashdata('mensaje', array('error', 'El Período ya ha sido registrado.'));
                    redirect(base_url() . 'configuracion/periodo/nuevo');
                }
                else {
                    if(isset($periodosRango))
                    {
                        $this->session->set_flashdata('mensaje', array('error', 'El rango del Período a crear ya esta asignado a un Período.'));
                        redirect(base_url() . 'configuracion/periodo/nuevo');
                    }
                    else {
                        $resultado = $this->Periodo_model->insert_periodo( $PrdOrd , $PrdAni , $PrdCod , $PrdFchIn  , $PrdFchFn , $this->idActividad , $this->idContratante );
                        if($resultado)
                        {
                            $this->session->set_flashdata('mensaje', array('success', 'El Período se ha registrado satisfactoriamente.'));
                            redirect(base_url() . 'configuracion/periodos');
                        }
                        else{
                            $this->session->set_flashdata('mensaje', array('error', 'El Período NO se ha registrado'));
                            redirect(base_url() . 'configuracion/periodo/nuevo');
                        }
                    }
                }
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $anio = date('Y');
        $this->data['anio'] = $anio;
        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'sistema/Periodo_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Periodos', 'configuracion/periodos'), array('Registrar Periodo', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function periodo_editar($idPeriodo) {
        $this->acceso_cls->verificarPermiso('editar_periodo');
        $periodo = $this->Periodo_model->get_one_periodo($idPeriodo);
        if ((!isset($periodo))) {
            show_404();
        }
        if ($periodo['PrdCntId'] != $this->data['userdata']['contratante']['CntId']) {
            show_404();
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('PrdCod', 'Nombre', 'required');
            $this->form_validation->set_rules('PrdFchIn2', 'Fecha Inicio', 'required');
            $this->form_validation->set_rules('PrdFchFn2', 'Fecha Fin', 'required');
            if ($this->form_validation->run() == TRUE) {
                $PrdCod = $this->input->post('PrdCod');
                $PrdAni = $this->input->post('PrdAni');
                $PrdOrd = $this->input->post('PrdOrd');
                $PrdFchIn = $this->input->post('PrdFchIn2');
                $PrdFchFn = $this->input->post('PrdFchFn2');$periodosRango = $this->Periodo_model->get_all_periodo_por_rangoFechas($this->idActividad, $PrdAni, $this->idContratante, $PrdFchIn, $PrdFchFn);
                if(isset($periodosRango))
                {
                    $this->session->set_flashdata('mensaje', array('error', 'El rango del Período a crear ya esta asignado a un Período.'));
                    redirect(base_url() . 'configuracion/periodo/editar/' . $idPeriodo);
                }
                else {
                    $resultado = $this->Periodo_model->update_periodo($PrdCod , $PrdFchIn  , $PrdFchFn , $idPeriodo);
                    if($resultado)
                    {
                        $this->session->set_flashdata('mensaje', array('success', 'El Período se ha actualizado correctamente.'));
                        redirect(base_url() . 'configuracion/periodo/editar/' . $idPeriodo);
                    }
                    else{
                        $this->session->set_flashdata('mensaje', array('error', 'El Período NO se ha registrado'));
                        redirect(base_url() . 'configuracion/periodo/editar/' . $idPeriodo);
                    }
                }
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['periodo'] = $periodo;
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'sistema/Periodo_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Periodos', 'configuracion/periodos'), array('Editar Período', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function periodo_eliminar($idPeriodo) {
        $this->acceso_cls->verificarPermiso('eliminar_periodo');
        $periodo = $this->Periodo_model->get_one_periodo($idPeriodo);
        if (!isset($periodo)) {
            show_404();
        }
        $resultado = $this->Periodo_model->delete_periodo($idPeriodo);
        if($resultado)
        {
            $this->session->set_flashdata('mensaje', array('success', 'El Periodo se ha eliminado satisfactoriamente.'));
            redirect(base_url() . 'configuracion/periodos');
        }else
        {
            $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun Error. El Periodo NO se ha eliminado.'));
            redirect(base_url() . 'configuracion/periodos');
        }
    }

}
