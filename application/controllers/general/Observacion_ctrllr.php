<?php

class Observacion_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('general/Observacion_model');
        $this->load->model('general/Actividad_model');
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();
        $this->data['userdata'] = $this->acceso_cls->get_userdata();
        $this->data['menu']['padre'] = 'configuracion';
    }

    public function grupoObservacion_listar() {
        $this->acceso_cls->verificarPermiso('ver_grupo_observacion');
        $this->data['actividades'] = $this->Observacion_model->get_all_grupoObservacion_actividad($this->data['userdata']['contratante']['CntId']);
        $this->data['view'] = 'general/GruposObservacion_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Grupo de Observaciones', ''));
        $this->data['menu']['hijo'] = 'grupo_observacion';
        $this->load->view('template/Master', $this->data);
    }

    public function grupoObservacion_nuevo() {
        $this->acceso_cls->verificarPermiso('editar_grupo_observacion');
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('descripcion', 'Nombre', 'required');
            $this->form_validation->set_rules('actividad', 'Actividad', 'required');

            if ($this->form_validation->run() == TRUE) {
                $descripcion = $this->input->post('descripcion');
                $idActividad = $this->input->post('actividad');
                $this->Observacion_model->insert_grupoObservacion($descripcion, $idActividad, $this->data['userdata']['contratante']['CntId']);
                $this->session->set_flashdata('mensaje', array('success', 'El Grupo de Observaciones se ha registrado satisfactoriamente.'));
                redirect(base_url() . 'configuracion/grupos_observacion');
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['actividades'] = $this->Actividad_model->get_all_actividad();
        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'general/GrupoObservacion_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Grupo de Observaciones', 'configuracion/grupos_observacion'), array('Registrar Grupo de Observaciones', ''));
        $this->data['menu']['hijo'] = 'grupo_observacion';
        $this->load->view('template/Master', $this->data);
    }

    public function grupoObservacion_editar($idGrupoObservacion) {
        $this->acceso_cls->verificarPermiso('editar_grupo_observacion');
        $grupoObservacion = $this->Observacion_model->get_one_grupoObservacion($idGrupoObservacion);
        if (!isset($grupoObservacion)) {
            show_404();
        }
        if ($grupoObservacion['GoaCntId'] != $this->data['userdata']['contratante']['CntId']) {
            show_404();
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('descripcion', 'Nombre', 'required');
            $this->form_validation->set_rules('actividad', 'Actividad', 'required');

            if ($this->form_validation->run() == TRUE) {
                $descripcion = $this->input->post('descripcion');
                $idActividad = $this->input->post('actividad');
                $this->Observacion_model->update_grupoObservacion($idGrupoObservacion, $idActividad, $descripcion);
                $this->session->set_flashdata('mensaje', array('exito', 'El grupo de Observaciones se ha actualizado correctamente.'));
                redirect(base_url() . 'configuracion/grupo_observacion/editar/' . $idGrupoObservacion);
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
                $this->grupoObservacion_editar($idGrupoObservacion);
            }
        }
        $this->data['grupoObservacion'] = $grupoObservacion;
        $this->data['actividades'] = $this->Actividad_model->get_all_actividad();
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'general/GrupoObservacion_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Grupo de Observaciones', 'configuracion/grupos_observacion'), array('Editar Grupo de Observaciones', ''));
        $this->data['menu']['hijo'] = 'grupo_observacion';
        $this->load->view('template/Master', $this->data);
    }

    public function grupoObservacion_eliminar($idGrupoObservacion) {
        $this->acceso_cls->verificarPermiso('eliminar_grupo_observacion');
        $grupoObservacion = $this->Observacion_model->get_one_grupoObservacion($idGrupoObservacion);
        if (!isset($grupoObservacion)) {
            show_404();
        }
        $this->Observacion_model->delete_grupoObservacion($idGrupoObservacion);
        $this->session->set_flashdata('mensaje', array('success', 'El Grupo de Observaciones se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'configuracion/grupos_observacion');
    }

    public function observacion_listar() {
        $this->acceso_cls->verificarPermiso('ver_observacion');
        $this->data['actividades'] = $this->Observacion_model->get_all_observacion($this->data['userdata']['contratante']['CntId']);
        $this->data['view'] = 'general/Observaciones_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Observaciones', ''));
        $this->data['menu']['hijo'] = 'observacion';
        $this->load->view('template/Master', $this->data);
    }

    public function observacion_nuevo() {
        $this->acceso_cls->verificarPermiso('editar_observacion');
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('descripcion', 'Nombre', 'required');
            $this->form_validation->set_rules('grupo', 'Grupo', 'required');
            $this->form_validation->set_rules('actividad', 'Actividad', 'required');
            $this->form_validation->set_rules('codigo', 'Codigo', 'required');
            if ($this->form_validation->run() == TRUE) {
                $descripcion = $this->input->post('descripcion');
                $codigo = $this->input->post('codigo');
                $idGrupo = $this->input->post('grupo');
                $this->Observacion_model->insert_observacion($codigo, $descripcion, $idGrupo, $this->data['userdata']['contratante']['CntId']);
                $this->session->set_flashdata('mensaje', array('success', 'La Observación se ha registrado satisfactoriamente.'));
                redirect(base_url() . 'configuracion/observaciones');
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['actividades'] = $this->Actividad_model->get_all_actividad();
        $this->data['gruposObservacion'] = $this->Observacion_model->get_all_grupoObservacion_x_actividad($this->data['actividades'][0]['ActId'], $this->data['userdata']['contratante']['CntId']);
        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'general/Observacion_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Observaciones', 'configuracion/observaciones'), array('Registrar Observación', ''));
        $this->data['menu']['hijo'] = 'observacion';
        $this->load->view('template/Master', $this->data);
    }

    public function observacion_editar($idObservacion) {
        $this->acceso_cls->verificarPermiso('editar_observacion');
        $observacion = $this->Observacion_model->get_one_observacion($idObservacion);
        if (!isset($observacion)) {
            show_404();
        }
        if ($observacion['ObsCntId'] != $this->data['userdata']['contratante']['CntId']) {
            show_404();
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('descripcion', 'Nombre', 'required');
            $this->form_validation->set_rules('grupo', 'Grupo', 'required');
            $this->form_validation->set_rules('actividad', 'Actividad', 'required');
            $this->form_validation->set_rules('codigo', 'Codigo', 'required');
            if ($this->form_validation->run() == TRUE) {
                $descripcion = $this->input->post('descripcion');
                $codigo = $this->input->post('codigo');
                $idGrupo = $this->input->post('grupo');
                $this->Observacion_model->update_observacion($codigo, $descripcion, $idGrupo, $idObservacion);
                $this->session->set_flashdata('mensaje', array('exito', 'La Observación se ha actualizado correctamente.'));
                redirect(base_url() . 'configuracion/observacion/editar/' . $idObservacion);
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['observacion'] = $observacion;
        $this->data['actividades'] = $this->Actividad_model->get_all_actividad();
        $this->data['gruposObservacion'] = $this->Observacion_model->get_all_grupoObservacion_x_actividad($observacion['AuxActId'], $this->data['userdata']['contratante']['CntId']);
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'general/Observacion_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Observaciones', 'configuracion/observaciones'), array('Editar Observación', ''));
        $this->data['menu']['hijo'] = 'observacion';
        $this->load->view('template/Master', $this->data);
    }

    public function observacion_eliminar($idObservacion) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('eliminar_observacion');
        $observacion = $this->Observacion_model->get_one_observacion($idObservacion);
        if (!isset($observacion)) {
            show_404();
        }
        if ($observacion['ObsCntId'] != $this->data['userdata']['contratante']['CntId']) {
            show_404();
        }
        $this->Observacion_model->delete_observacion($idObservacion);
        $this->session->set_flashdata('mensaje', array('success', 'La Observación se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'configuracion/observaciones');
    }

    public function ajax_obtener_grupos($idActividad) {
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($this->Observacion_model->get_all_grupoObservacion_x_actividad($idActividad, $this->data['userdata']['contratante']['CntId'])));
    }

}
