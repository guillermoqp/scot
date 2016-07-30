<?php

class Variable_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('sistema/Variable_model');
        $this->load->model('general/Periodo_model');
        $this->load->model('general/Actividad_model');  
        
        $this->load->model('sistema/SegundaConexion_model'); 
        
        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();

        $this->data['menu']['padre'] = 'configuracion';
        $this->data['menu']['hijo'] = 'variable';
        
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->idContratante = $this->data['userdata']['contratante']['CntId'];
        $this->idActividad = $this->Actividad_model->get_id_actividad_x_codigo('toma_estado');
        
    }

    public function variable_listar() {
        $this->acceso_cls->verificarPermiso('ver_variable');
        $this->data['variables'] = $this->Variable_model->get_all_variable($this->data['userdata']['contratante']['CntId']);
        $this->data['view'] = 'sistema/Variables_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Variables', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function variable_nuevo() {
        $this->acceso_cls->verificarPermiso('editar_variable');
        
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('VarDes', 'Nombre', 'required');
            $this->form_validation->set_rules('VarCod', 'Código', 'required');
            $this->form_validation->set_rules('VarVal', 'Valor', 'required');
            $this->form_validation->set_rules('VarPrdId', 'Periodo', 'required');
            
            if ($this->form_validation->run() == TRUE) {
                
                $descripcion = $this->input->post('VarDes');
                $codigo = $this->input->post('VarCod');
                $valor = $this->input->post('VarVal');
                $VarPrdId = $this->input->post('VarPrdId');
                $this->Variable_model->insert_variable($codigo, $descripcion, $valor, $VarPrdId , $this->data['userdata']['contratante']['CntId'] );
                $this->session->set_flashdata('mensaje', array('success', 'La variable se ha registrado satisfactoriamente.'));
                redirect(base_url() . 'configuracion/variables');
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        
        $this->data['periodos'] = $this->Periodo_model->get_all_periodo($this->idActividad, date("Y") , $this->idContratante);
        
        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'sistema/Variable_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Variables', 'configuracion/variables'), array('Registrar Variables', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function variable_editar($idVariable) {
        $this->acceso_cls->verificarPermiso('editar_variable');
        $variable = $this->Variable_model->get_one_variable($idVariable);
        if ((!isset($variable))) {
            show_404();
        }
        if ($variable['VarCntId'] != $this->data['userdata']['contratante']['CntId']) {
            show_404();
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('VarDes', 'Nombre', 'required');
            $this->form_validation->set_rules('VarCod', 'Código', 'required');
            $this->form_validation->set_rules('VarVal', 'Valor', 'required');
            $this->form_validation->set_rules('VarPrdId', 'Periodo', 'required');
                
            if ($this->form_validation->run() == TRUE) {
                
                $codigo = $this->input->post('VarCod');
                $descripcion = $this->input->post('VarDes');
                $valor = $this->input->post('VarVal');
                $VarPrdId = $this->input->post('VarPrdId');
                
                $this->Variable_model->update_variable($codigo, $descripcion, $valor, $VarPrdId, $idVariable );
                
                $this->session->set_flashdata('mensaje', array('exito', 'La Variable se ha actualizado correctamente.'));
                redirect(base_url() . 'configuracion/variable/editar/' . $idVariable);
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['periodos'] = $this->Periodo_model->get_all_periodo($this->idActividad, date("Y") , $this->idContratante);
        
        $this->data['variable'] = $variable;
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'sistema/Variable_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Variables', 'configuracion/variables'), array('Editar Variable', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function variable_eliminar($idVariable) {
        //$this->acceso_cls->verificarPermiso('eliminar_grupo_penalidad');
        $variable = $this->Variable_model->get_one_variable($idVariable);
        if (!isset($variable)) {
            show_404();
        }
        $this->Variable_model->delete_variable($idVariable);
        $this->session->set_flashdata('mensaje', array('success', 'La Variable se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'configuracion/variables');
    }
    
    
    public function segunda_conexion() {
       
        $this->data['ciclos'] = $this->SegundaConexion_model->get_all_ciclos();
        //$this->data['usuarios'] = $this->SegundaConexion_model->get_all_usuarios();
        
        $this->data['view'] = 'sistema/SegundaConexion_view';
        
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Desde otra BD', ''));
        $this->load->view('template/Master', $this->data);
    }

}
