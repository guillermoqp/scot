<?php

class Cargo_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('acceso/Cargo_model');
        $this->load->model('acceso/Permiso_model');
        $this->load->model('planificacion/Actividad_model');
        $this->load->model('acceso/Nivel_model');
        $this->load->library('session'); 
        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();
        $this->data['userdata'] = $this->acceso_cls->get_userdata();
        $this->data['menu']['padre'] = 'permisos';
        $this->data['menu']['hijo'] = 'cargo';
    }

    public function cargo_listar() {
        $this->acceso_cls->verificarPermiso('ver_cargo');
        $this->data['tipos'] = $this->Cargo_model->get_all_cargos_x_tipo($this->data['userdata']['contratante']['CntId']);
        $this->data['view'] = 'acceso/Cargo_lista_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Cargos', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function cargo_nuevo() {
        $this->acceso_cls->verificarPermiso('editar_cargo');
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('descripcion', 'Nombre', 'required');
            $this->form_validation->set_rules('tipo', 'Tipo', 'required');
            $this->form_validation->set_rules('nivel', 'Nivel', 'required');

            if ($this->form_validation->run() == TRUE) {
                $descripcion = $this->input->post('descripcion');
                $esContratista = $this->input->post('tipo');
                $esContratista = ($esContratista == 1); //true es contratista
                $idNivel = $this->input->post('nivel');
                $this->Cargo_model->insert_cargo($descripcion, $idNivel, $esContratista, $this->data['userdata']['contratante']['CntId']);
                $this->session->set_flashdata('mensaje', array('success', 'El Cargo se ha registrado satisfactoriamente.'));
                redirect(base_url() . 'permisos/cargo');
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['actividades'] = $this->Actividad_model->get_all_actividad();
        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'acceso/Cargo_editar_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Cargos', 'permisos/cargo'), array('Registrar Cargo', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function cargo_editar($idCargo) {
        $this->acceso_cls->verificarPermiso('editar_cargo');
        $cargo = $this->Cargo_model->get_one_cargo($idCargo);
        if (!isset($cargo)) {
            show_404();
        }
        if ($cargo['CarCntId'] != $this->data['userdata']['contratante']['CntId']) {
            show_404();
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('descripcion', 'Nombre', 'required');

            if ($this->form_validation->run() == TRUE) {
                $descripcion = $this->input->post('descripcion');
                $this->Cargo_model->update_cargo($idCargo, $descripcion);
                $this->session->set_flashdata('mensaje', array('exito', 'El Cargo se ha actualizado correctamente.'));
                redirect(base_url() . 'permisos/cargo');
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['cargo'] = $cargo;
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'acceso/Cargo_editar_view';
        $this->data['proceso'] = 'Administraci�n General';
        $this->data['breadcrumbs'] = array(array('Cargos', 'permisos/cargo'), array('Editar Cargo', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function cargo_eliminar($idCargo) {
        $this->acceso_cls->verificarPermiso('eliminar_cargo');
        $cargo = $this->Cargo_model->get_one_cargo($idCargo);
        if (!isset($cargo)) {
            show_404();
        }
        if ($cargo['CarCntId'] != $this->data['userdata']['contratante']['CntId']) {
            show_404();
        }
        $this->Cargo_model->delete_cargo($idCargo);
        $this->session->set_flashdata('mensaje', array('success', 'El Cargo se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'permisos/cargo');
    }

    public function ajax_obtener_niveles($tipo) {
        header('Content-Type: application/x-json; charset=utf-8');
        $tipo = ($tipo == 1);
        echo(json_encode($this->Nivel_model->get_nivel_x_tipo($tipo)));
    }

}
