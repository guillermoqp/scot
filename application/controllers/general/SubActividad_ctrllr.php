<?php

class SubActividad_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('general/Actividad_model');
        $this->load->model('general/SubActividad_model');
        $this->load->model('general/Unidad_model');
 
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();
        $this->data['menu']['padre'] = 'configuracion_global';
        $this->data['menu']['hijo'] = 'subactividad';
    }

    public function subactividad_listar() 
    {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['actividades'] = $this->Actividad_model->get_all_actividad_subactividad();
        

        $this->data['view'] = 'general/SubActividades_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Sub Actividades', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function subactividad_nuevo() 
    {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['actividades'] = $this->Actividad_model->get_all_actividad();
        $this->data['unidades'] = $this->Unidad_model->get_all_unidad();

        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'general/SubActividad_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Sub Actividades', 'configuracion/subactividad'), array('Registrar Sub Actividad Comercial', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function subactividad_nuevo_guardar() 
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('actividad', 'Actividad', 'required');
        $this->form_validation->set_rules('unidad', 'Unidad de medida', 'required');
        $this->form_validation->set_rules('nombre', 'Nombre de Sub Actividad', 'required');

        if ($this->form_validation->run() == TRUE) 
        {
            $actividad = $this->input->post('actividad');
            $nombre = $this->input->post('nombre');
            $unidad = $this->input->post('unidad');

            $this->SubActividad_model->insert_subactividad($actividad, $unidad , $nombre);

            $this->session->set_flashdata('mensaje', array('success', 'La Sub Actividad Comercial se ha registrado satisfactoriamente.'));
            redirect(base_url() . 'configuracion/subactividad');
        } else 
        {
            $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            $this->subactividad_nuevo();
        }
    }

    public function subactividad_editar($idSubactividad) 
    {
        $subactividad = $this->SubActividad_model->get_one_subactividad($idSubactividad);

        if (!isset($subactividad)) 
        {
            show_404();
        }

        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);

        $this->data['actividades'] = $this->Actividad_model->get_all_actividad();
        $this->data['unidades'] = $this->Unidad_model->get_all_unidad();

        $this->data['subactividad'] = $subactividad;
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'general/SubActividad_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Sub Actividades', 'configuracion/subactividad'), array('Editar Sub Actividad Comercial', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function subactividad_editar_guardar($idSubactividad) 
    {
        $subactividad = $this->SubActividad_model->get_one_subactividad($idSubactividad);

        if (!isset($subactividad)) 
        {
            show_404();
        }
        $this->load->library('form_validation');

        $this->form_validation->set_rules('actividad', 'Actividad', 'required');
        $this->form_validation->set_rules('unidad', 'Unidad de Medida', 'required');
        $this->form_validation->set_rules('nombre', 'Nombre de Sub Actividad', 'required');


        if ($this->form_validation->run() == TRUE) 
        {

            $actividad = $this->input->post('actividad');
            $nombre = $this->input->post('nombre');
            $unidad = $this->input->post('unidad');

            $this->SubActividad_model->update_subactividad($idSubactividad, $actividad, $unidad , $nombre);

            $this->session->set_flashdata('mensaje', array('exito', 'La Sub Actividad se ha actualizado correctamente.'));
            redirect(base_url() . 'configuracion/subactividad/editar/' . $idSubactividad);
        } else 
        {
            $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            $this->subactividad_editar($idSubactividad);
        }
    }

    public function subactividad_eliminar($idSubactividad) 
    {
        $subactividad = $this->SubActividad_model->get_one_subactividad($idSubactividad);
        if (!isset($subactividad )) 
        {
            show_404();
        }
        $this->SubActividad_model->delete_subactividad($idSubactividad);
        $this->session->set_flashdata('mensaje', array('success', 'La Sub Actividad se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'configuracion/subactividad');
    }

    
}
