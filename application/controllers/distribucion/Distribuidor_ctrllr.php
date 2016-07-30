<?php

class Distribuidor_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('distribucion/Distribuidor_model');
        
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();
        $this->data['userdata'] = $this->acceso_cls->get_userdata();
        $this->data['proceso'] = 'Distribucion de Recibos y Comunicaciones al Cliente';
        $this->data['menu']['padre'] = 'distribucion';
        $this->data['menu']['hijo'] = 'distribuidor';
    }

    public function distribuidor_listar() {
        $this->acceso_cls->verificarPermiso('ver_distribuidor');
        
        $this->data['distribuidores'] = $this->Distribuidor_model->get_all_distribuidor($this->data['userdata']['contratante']['CntId']);
        
        $this->data['view'] = 'distribucion/Distribuidor_lista_view';
        $this->data['breadcrumbs'] = array(array('Distribuidores', ''));
        $this->load->view('template/Master', $this->data);
    }
}
