<?php

class Unidad_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('general/Unidad_model');
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();
        $this->data['menu']['padre'] = 'configuracion_global';
        $this->data['menu']['hijo'] = 'unidad';
    }

    public function unidad_listar() {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['unidades'] = $this->Unidad_model->get_all_unidad();

        $this->data['view'] = 'general/Unidades_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Unidades de Medida', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function unidad_nuevo() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('descripcion', 'Nombre', 'required');
            if ($this->form_validation->run() == TRUE) {
                $descripcion = $this->input->post('descripcion');
                $this->Unidad_model->insert_unidad($descripcion);
                $this->session->set_flashdata('mensaje', array('success', 'La unidad de medida se ha registrado satisfactoriamente.'));
                redirect(base_url() . 'configuracion/unidades');
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'general/Unidad_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Unidades de Medida', 'configuracion/unidades'), array('Registrar Unidad de Medida', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function unidad_editar($idUnidad) {
        $unidad = $this->Unidad_model->get_one_unidad($idUnidad);
        if (!isset($unidad)) {
            show_404();
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('descripcion', 'Nombre', 'required');

            if ($this->form_validation->run() == TRUE) {
                $descripcion = $this->input->post('descripcion');
                $this->Unidad_model->update_unidad($idUnidad, $descripcion);
                $this->session->set_flashdata('mensaje', array('exito', 'La Unidad de medida se ha actualizado correctamente.'));
                redirect(base_url() . 'configuracion/unidad/editar/' . $idUnidad);
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['unidad'] = $unidad;
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'general/Unidad_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Unidades de Medida', 'configuracion/unidades'), array('Editar Unidad de medida', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function unidad_eliminar($idUnidad) {
        $unidad = $this->Unidad_model->get_one_unidad($idUnidad);
        if (!isset($unidad)) {
            show_404();
        }
        $this->Unidad_model->delete_unidad($idUnidad);
        $this->session->set_flashdata('mensaje', array('success', 'La Unidad de medida se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'configuracion/unidades');
    }

}
