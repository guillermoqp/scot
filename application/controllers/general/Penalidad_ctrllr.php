<?php

class Penalidad_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('general/Penalidad_model');
        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();
        $this->data['userdata'] = $this->acceso_cls->get_userdata();
        $this->data['menu']['padre'] = 'configuracion';
        //$this->data['menu']['hijo'] = 'penalidad';
    }

    public function grupoPenalidad_listar() {
        $this->acceso_cls->verificarPermiso('ver_grupo_penalidad');
        $this->data['grpPenalidades'] = $this->Penalidad_model->get_all_grpPenalidad($this->data['userdata']['contratante']['CntId']);
        $this->data['view'] = 'general/GruposPenalidad_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Grupo de Penalidades', ''));
        $this->data['menu']['hijo'] = 'grupo_penalidad';
        $this->load->view('template/Master', $this->data);
    }

    public function grupoPenalidad_nuevo() {
        $this->acceso_cls->verificarPermiso('editar_grupo_penalidad');
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('descripcion', 'Nombre', 'required');
            $this->form_validation->set_rules('codigo', 'Código', 'required');
            if ($this->form_validation->run() == TRUE) {
                $descripcion = $this->input->post('descripcion');
                $codigo = $this->input->post('codigo');
                $this->Penalidad_model->insert_grpPenalidad($codigo, $descripcion, $this->data['userdata']['contratante']['CntId']);
                $this->session->set_flashdata('mensaje', array('success', 'El Grupo de Penalidades se ha registrado satisfactoriamente.'));
                redirect(base_url() . 'configuracion/grupos_penalidad');
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'general/GrupoPenalidad_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Grupo de Penalidades', 'configuracion/grupos_penalidad'), array('Registrar Grupo de Penalidades', ''));
        $this->data['menu']['hijo'] = 'grupo_penalidad';
        $this->load->view('template/Master', $this->data);
    }

    public function grupoPenalidad_editar($idGrpPenalidad) {
        $this->acceso_cls->verificarPermiso('editar_grupo_penalidad');
        $grpPenalidad = $this->Penalidad_model->get_one_grpPenalidad($idGrpPenalidad);
        if ((!isset($grpPenalidad))) {
            show_404();
        }
        if($grpPenalidad['GpeCntId'] != $this->data['userdata']['contratante']['CntId']){
            show_404();
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('codigo', 'Código', 'required');
            $this->form_validation->set_rules('descripcion', 'Nombre', 'required');

            if ($this->form_validation->run() == TRUE) {
                $codigo = $this->input->post('codigo');
                $descripcion = $this->input->post('descripcion');
                $this->Penalidad_model->update_grpPenalidad($idGrpPenalidad, $descripcion, $codigo);
                $this->session->set_flashdata('mensaje', array('exito', 'El grupo de Penalidad se ha actualizado correctamente.'));
                redirect(base_url() . 'configuracion/grupo_penalidad/editar/' . $idGrpPenalidad);
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['grpPenalidad'] = $grpPenalidad;
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'general/GrupoPenalidad_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Grupo de Penalidades', 'configuracion/grupos_penalidad'), array('Editar Grupo de Penalidades', ''));
        $this->data['menu']['hijo'] = 'grupo_penalidad';
        $this->load->view('template/Master', $this->data);
    }

    public function grupoPenalidad_eliminar($idGrpPenalidad) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('eliminar_grupo_penalidad');
        $grpPenalidad = $this->Penalidad_model->get_one_grpPenalidad($idGrpPenalidad);
        if (!isset($grpPenalidad)) {
            show_404();
        }
        if($grpPenalidad['GpeCntId'] != $this->data['userdata']['contratante']['CntId']){
            show_404();
        }
        $this->Penalidad_model->delete_grpPenalidad($grpPenalidad['GpeId']);
        $this->session->set_flashdata('mensaje', array('success', 'El Grupo de Penalidades se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'configuracion/grupos_penalidad');
    }

    public function penalidad_listar() {
        $this->acceso_cls->verificarPermiso('ver_penalidad');
        $this->data['gruposPenalidad'] = $this->Penalidad_model->get_all_grpPenalidad_penalidad($this->data['userdata']['contratante']['CntId']);
        $this->data['view'] = 'general/Penalidades_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Penalidades', ''));
        $this->data['menu']['hijo'] = 'penalidad';
        $this->load->view('template/Master', $this->data);
    }

    public function penalidad_nuevo() {
        $this->acceso_cls->verificarPermiso('editar_penalidad');
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('grupo', 'Grupo', 'required');
            $this->form_validation->set_rules('descripcion', 'Nombre', 'required');
            $this->form_validation->set_rules('codigo', 'Codigo', 'required');
            if ($this->form_validation->run() == TRUE) {
                $grupo = $this->input->post('grupo');
                $codigo = $this->input->post('codigo');
                $descripcion = $this->input->post('descripcion');
                $this->Penalidad_model->insert_penalidad($grupo, $codigo, $descripcion,$this->data['userdata']['contratante']['CntId']);
                $this->session->set_flashdata('mensaje', array('success', 'La Penalidad se ha registrado satisfactoriamente.'));
                redirect(base_url() . 'configuracion/penalidades');
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['gruposPenalidad'] = $this->Penalidad_model->get_all_grpPenalidad($this->data['userdata']['contratante']['CntId']);
        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'general/Penalidad_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Penalidades', 'configuracion/penalidades'), array('Registrar Penalidad', ''));
        $this->data['menu']['hijo'] = 'penalidad';
        $this->load->view('template/Master', $this->data);
    }

    public function penalidad_editar($idPenalidad) {
        $this->acceso_cls->verificarPermiso('editar_penalidad');
        $penalidad = $this->Penalidad_model->get_one_penalidad($idPenalidad);
        if (!isset($penalidad)) {
            show_404();
        }
        if($penalidad['PenCntId'] != $this->data['userdata']['contratante']['CntId']){
            show_404();
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('grupo', 'Grupo', 'required');
            $this->form_validation->set_rules('descripcion', 'Nombre', 'required');
            $this->form_validation->set_rules('codigo', 'Codigo', 'required');

            if ($this->form_validation->run() == TRUE) {
                $grupo = $this->input->post('grupo');
                $codigo = $this->input->post('codigo');
                $descripcion = $this->input->post('descripcion');
                $this->Penalidad_model->update_penalidad($idPenalidad, $grupo, $codigo, $descripcion);
                $this->session->set_flashdata('mensaje', array('exito', 'La Penalidad se ha actualizado correctamente.'));
                redirect(base_url() . 'configuracion/penalidad/editar/' . $idPenalidad);
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['gruposPenalidad'] = $this->Penalidad_model->get_all_grpPenalidad($this->data['userdata']['contratante']['CntId']);
        $this->data['penalidad'] = $penalidad;
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'general/Penalidad_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Penalidades', 'configuracion/penalidades'), array('Editar Penalidad', ''));
        $this->data['menu']['hijo'] = 'penalidad';
        $this->load->view('template/Master', $this->data);
    }

    public function penalidad_eliminar($idPenalidad) {
        $this->acceso_cls->verificarPermiso('eliminar_penalidad');
        $penalidad = $this->Penalidad_model->get_one_penalidad($idPenalidad);
        if (!isset($penalidad)) {
            show_404();
        }
        if($penalidad['PenCntId'] != $this->data['userdata']['contratante']['CntId']){
            show_404();
        }
        $this->Penalidad_model->delete_penalidad($idPenalidad);
        $this->session->set_flashdata('mensaje', array('success', 'La Penalidad se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'configuracion/penalidades');
    }

    public function ajax_obtener_penalidades_x_grupo($idGrupo)
    {
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($this->Penalidad_model->get_penalidad_x_grupo($idGrupo)));
    }
    
}
