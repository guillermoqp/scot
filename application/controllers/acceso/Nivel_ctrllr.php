<?php

class Nivel_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('acceso/Nivel_model');
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();
        $this->data['menu']['padre'] = 'permisos';
        $this->data['menu']['hijo'] = 'nivel';
    }

    public function nivel_listar() {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_nivel');
        $this->data['niveles1'] = $this->Nivel_model->get_nivel_x_tipo(false); //del contratante
        $this->data['niveles2'] = $this->Nivel_model->get_nivel_x_tipo(true); //del contratista

        $this->data['view'] = 'acceso/Niveles_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Niveles Organizacionales', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function nivel_nuevo() {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_nivel');
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('tipoOrg', 'Tipo de Organización', 'required');
            $this->form_validation->set_rules('descripcion', 'Nombre', 'required');

            if ($this->form_validation->run() == TRUE) {
                $tipoOrgId = $this->input->post('tipoOrg');
                $descripcion = $this->input->post('descripcion');
                $this->Nivel_model->insert_nivel($tipoOrgId, $descripcion);
                $this->session->set_flashdata('mensaje', array('success', 'El nivel se ha registrado satisfactoriamente.'));
                redirect(base_url() . 'permisos/niveles');
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'acceso/Nivel_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Niveles Organizacionales', 'permisos/niveles'), array('Registrar Nivel Organizacional', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function nivel_editar($idNivel) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_nivel');
        $nivel = $this->Nivel_model->get_one_nivel($idNivel);
        if (!isset($nivel)) {
            show_404();
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('descripcion', 'Nombre', 'required');

            if ($this->form_validation->run() == TRUE) {
                $descripcion = $this->input->post('descripcion');
                $this->Nivel_model->update_nivel($idNivel, $descripcion);
                $this->session->set_flashdata('mensaje', array('exito', 'El Nivel Organizacional se ha actualizado correctamente.'));
                redirect(base_url() . 'permisos/niveles');
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['nivel'] = $nivel;
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'acceso/Nivel_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Niveles Organizacionales', 'permisos/niveles'), array('Editar Nivel Organizacional', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function nivel_eliminar($idNivel) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('eliminar_nivel');
        $nivel = $this->Nivel_model->get_one_nivel($idNivel);
        if (!isset($nivel)) {
            show_404();
        }
        $this->Nivel_model->delete_nivel($idNivel);
        $this->session->set_flashdata('mensaje', array('success', 'El nivel organizacional se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'permisos/niveles');
    }

    public function nivel_subir($idNivel) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_nivel');
        $nivel = $this->Nivel_model->get_one_nivel($idNivel);
        if (!isset($nivel)) {
            show_404();
        }
        $this->Nivel_model->go_up_nivel($idNivel);
        $this->session->set_flashdata('mensaje', array('success', 'El nivel organizacional se ha modificado satisfactoriamente.'));
        redirect(base_url() . 'permisos/niveles');
    }

    public function nivel_bajar($idNivel) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_nivel');
        $nivel = $this->Nivel_model->get_one_nivel($idNivel);
        if (!isset($nivel)) {
            show_404();
        }
        $this->Nivel_model->go_down_nivel($idNivel);
        $this->session->set_flashdata('mensaje', array('success', 'El nivel organizacional se ha  modificado satisfactoriamente.'));
        redirect(base_url() . 'permisos/niveles');
    }

}
