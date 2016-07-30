<?php

class Lecturista_cst_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('lectura/Lecturista_model');
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->load->library('utilitario_cls');
        $this->acceso_cls->isLogin();
        $this->data['userdata'] = $this->acceso_cls->get_userdata();
        $this->data['proceso'] = 'Toma de Estado de Suministros';
        $this->data['menu']['padre'] = 'toma_estado';
        $this->data['menu']['hijo'] = 'lecturista';
    }

    public function lecturista_listar() {
        $this->acceso_cls->verificarPermiso('ver_lecturista');
        $this->data['lecturistas'] = $this->Lecturista_model->get_all_lecturista($this->data['userdata']['contratante']['CntId']);
        $this->data['view'] = 'contratista/lectura/Lecturista_lista_cst_view';
        $this->data['breadcrumbs'] = array(array('Lecturistas', ''));
        $this->load->view('contratista/template/Master_cst_view', $this->data);
    }
}
