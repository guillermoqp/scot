<?php

class Perfil_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('acceso/Perfil_model');
        $this->load->model('acceso/Permiso_model');
        $this->load->model('general/Actividad_model');
        $this->load->model('acceso/Nivel_model');
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();
        $this->data['userdata'] = $this->acceso_cls->get_userdata();
        $this->data['menu']['padre'] = 'permisos';
        $this->data['menu']['hijo'] = 'rol';
    }

    public function rol_listar() {
        $this->acceso_cls->verificarPermiso('ver_rol');
        $this->data['tipos'] = $this->Perfil_model->get_all_roles_x_tipo($this->data['userdata']['contratante']['CntId']);
        $this->data['view'] = 'acceso/Perfiles_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Perfiles', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function rol_nuevo() {
        $this->acceso_cls->verificarPermiso('ver_rol');
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('descripcion', 'Nombre', 'required');
            $this->form_validation->set_rules('codigo', 'Codigo', 'required');

            if ($this->form_validation->run() == TRUE) {
                $descripcion = $this->input->post('descripcion');
                $codigo = $this->input->post('codigo');
                $permisos = $this->input->post('permisos');
                $this->Perfil_model->insert_rol($descripcion, $codigo, $permisos, $this->data['userdata']['contratante']['CntId']);
                $this->session->set_flashdata('mensaje', array('success', 'El Perfil se ha registrado satisfactoriamente.'));
                redirect(base_url() . 'permisos/perfil');
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['actividades'] = $this->Actividad_model->get_all_actividad();
        $this->data['permisos'] = $this->Permiso_model->get_all_permiso_grupos();
        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'acceso/Perfil_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Perfiles', 'permisos/perfil'), array('Registrar Perfil', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function rol_editar($idRol) {
        $this->acceso_cls->verificarPermiso('editar_rol');
        $rol = $this->Perfil_model->get_one_rol($idRol);
        if (!isset($rol)) {
            show_404();
        }
        if ($rol['RolCntId'] != $this->data['userdata']['contratante']['CntId']) {
            show_404();
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('descripcion', 'Nombre', 'required');

            if ($this->form_validation->run() == TRUE) {
                $descripcion = $this->input->post('descripcion');
                $permisos = $this->input->post('permisos');
                $this->Perfil_model->update_rol($idRol, $descripcion, $permisos);
                $this->session->set_flashdata('mensaje', array('exito', 'El Perfil se ha actualizado correctamente.'));
                redirect(base_url() . 'permisos/perfil/editar/' . $idRol);
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['permisos'] = $this->Permiso_model->get_all_permiso_grupos();
        $this->data['rol'] = $rol;
        $this->data['rol']['permisos'] = $this->Permiso_model->get_permisos_x_rol($rol['RolId']);
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'acceso/Perfil_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Perfiles', 'permisos/perfil'), array('Editar Perfil', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function rol_eliminar($idRol) {
        $this->acceso_cls->verificarPermiso('eliminar_rol');
        $rol = $this->Perfil_model->get_one_rol($idRol);
        if (!isset($rol)) {
            show_404();
        }
        if ($rol['RolCntId'] != $this->data['userdata']['contratante']['CntId']) {
            show_404();
        }
        $this->Perfil_model->delete_rol($idRol);
        $this->session->set_flashdata('mensaje', array('success', 'El Perfil se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'permisos/perfil');
    }
}
