<?php

class Distribuidor_cst_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('distribucion/Distribuidor_model');
        
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->load->library('utilitario_cls');
        
        $this->acceso_cls->isLogin();
        $this->data['userdata'] = $this->acceso_cls->get_userdata();
        $this->data['proceso'] = 'Distribucion de Recibos y Comunicaciones';
        
        $this->data['menu']['padre'] = 'distribucion';
        $this->data['menu']['hijo'] = 'distribuidor';
    }

    public function distribuidor_listar() {
        $this->acceso_cls->verificarPermisoCst('ver_distribuidor');
        
        $this->data['distribuidores'] = $this->Distribuidor_model->get_all_distribuidor($this->data['userdata']['contratante']['CntId']);
        
        $this->data['view'] = 'contratista/distribucion/Distribuidor_lista_cst_view';
        
        $this->data['breadcrumbs'] = array(array('Distribuidores de Recibos y Comunicaciones', ''));
        $this->load->view('contratista/template/Master_cst_view', $this->data);
    }
}
