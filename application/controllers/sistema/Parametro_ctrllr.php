<?php

class Parametro_ctrllr extends CI_Controller 
{

    public function __construct() {
        parent::__construct();
        $this->load->model('sistema/Parametro_model');
        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();

        $this->data['userdata'] = $this->acceso_cls->get_userdata();
        $this->idContratante = $this->data['userdata']['contratante']['CntId'];
        
        $this->data['menu']['padre'] = 'configuracion_global';
        $this->data['menu']['hijo'] = 'parametros';
    }

    public function parametros_listar() 
    {
        $parametros  = $this->Parametro_model->get_all_Parametro();
        if (!isset($parametros)) {
            show_404();
        }
        $this->data['parametros'] = $parametros;
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);

        $this->data['view'] = 'sistema/Parametros_list_view';
        $this->data['proceso'] = 'Administración General';
        
        $this->data['breadcrumbs'] = array( array('Parametros del Sistema', ''));
        $this->load->view('template/Master', $this->data);
    }
    
    public function parametro_eliminar($idParametro) 
    {
        $parametro  = $this->Parametro_model->get_one_parametro_x_id($idParametro);
        if (!isset($parametro)) {
            show_404();
        }
        $this->Parametro_model->delete_parametro($parametro['ParId']);
        $this->session->set_flashdata('mensaje', array('success', 'El '.$parametro['ParDes'].' se ha eliminado satisfactoriamente.'));
        redirect(base_url() . "configuracion/parametros");
    }
    
    public function detParam_listar($codParametro) 
    {
        $parametro  = $this->Parametro_model->get_one_parametro($codParametro);
        if (!isset($parametro)) {
            show_404();
        }
        $this->data['parametro'] = $parametro;
        $this->data['detalleParams'] = $this->Parametro_model->get_all_detParam($parametro['ParId']);
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);

        $this->data['view'] = 'sistema/Parametros_view';
        $this->data['proceso'] = 'Administración General';
        
        $this->data['breadcrumbs'] = array( array($parametro['ParDes'], ''));
        $this->load->view('template/Master', $this->data);
    }

    public function detParam_nuevo($codParametro) 
    {
        $parametro  = $this->Parametro_model->get_one_parametro($codParametro);
        if (!isset($parametro)) {
            show_404();
        }
        $this->data['parametro'] = $parametro;
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'sistema/Parametro_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array($parametro['ParDes'] , 'configuracion/'.$parametro['ParCod']), array('Registrar '.$parametro['ParDes'], ''));
        $this->load->view('template/Master', $this->data);
    }

    public function detParam_nuevo_guardar($codParametro) 
    {
        $parametro  = $this->Parametro_model->get_one_parametro($codParametro);
        if (!isset($parametro)) {
            show_404();
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('descripcion', 'Nombre', 'required');

        if ($this->form_validation->run() == TRUE) {
            $descripcion = $this->input->post('descripcion');
            $this->Parametro_model->insert_detParam($parametro['ParId'],$descripcion);
            $this->session->set_flashdata('mensaje', array('success', 'El '.$parametro['ParDes'].' se ha registrado satisfactoriamente.'));
            redirect(base_url() . "configuracion" .$parametro['ParCod']);
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            $this->unidad_nuevo();
        }
    }

    public function detParam_editar($codParametro, $idDetParam) 
    {
        $parametro  = $this->Parametro_model->get_one_parametro($codParametro);
        if (!isset($parametro)) {
            show_404();
        }
        $detParam = $this->Parametro_model->get_one_detParam($idDetParam);
        if (!isset($detParam)) {
            show_404();
        }
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['parametro'] = $parametro;
        $this->data['detParam'] = $detParam;
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'sistema/Parametro_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array( array( $parametro['ParDes'] , 'configuracion/'.$parametro['ParCod'] ), array('Editar '.$parametro['ParDes'], ''));
        $this->load->view('template/Master', $this->data);
    }

    public function detParam_editar_guardar($codParametro, $idDetParam) 
    {
        $parametro  = $this->Parametro_model->get_one_parametro($codParametro);
        if (!isset($parametro)) {
            show_404();
        }
        $detParam = $this->Parametro_model->get_one_detParam($idDetParam);
        if (!isset($detParam)) {
            show_404();
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('descripcion', 'Nombre', 'required');

        if ($this->form_validation->run() == TRUE) {
            $descripcion = $this->input->post('descripcion');
            $this->Parametro_model->update_detParam($idDetParam, $descripcion);
            $this->session->set_flashdata('mensaje', array('exito', 'El '.$parametro['ParDes'].' se ha actualizado correctamente.'));
            redirect(base_url(). "configuracion" .$parametro['ParCod'].'/editar/' . $detParam['DprId']);
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            $this->detParam_editar($parametro['ParCod'],$detParam['DprId']);
        }
    }

    public function detParam_eliminar($codParametro, $idDetParam) 
    {
        $parametro  = $this->Parametro_model->get_one_parametro($codParametro);
        if (!isset($parametro)) {
            show_404();
        }
        $detParam = $this->Parametro_model->get_one_detParam($idDetParam);
        if (!isset($detParam)) {
            show_404();
        }
        $this->Parametro_model->delete_detParam($detParam['DprId']);
        $this->session->set_flashdata('mensaje', array('success', 'El '.$parametro['DprDes'].' se ha eliminado satisfactoriamente.'));
        redirect(base_url() . "configuracion/" .$parametro['ParCod']);
    }

}
