<?php

class Actividad_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('general/Actividad_model');
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();
        $this->data['menu']['padre'] = 'configuracion_global';
        $this->data['menu']['hijo'] = 'actividad';
    }

    public function actividad_listar() 
    {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['actividades'] = $this->Actividad_model->get_all_actividad();

        $this->data['view'] = 'general/Actividades_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Actividades Comerciales', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function actividad_nuevo() 
    {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'general/Actividad_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Actividades Comerciales', 'configuracion/actividad'), array('Registrar Actividad Comercial', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function actividad_nuevo_guardar() 
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('descripcion', 'Nombre', 'required');

        if ($this->form_validation->run() == TRUE) 
        {
            $descripcion = $this->input->post('descripcion');
            $this->Actividad_model->insert_actividad($descripcion);
            $idActividad = $this->db->insert_id();
            $Actividad=$this->Actividad_model->get_one_actividad($idActividad);
            $idGrpPermiso = $this->Actividad_model->insert_grupo_permiso($descripcion,$Actividad['ActCod']);
            $this->Actividad_model->insert_grupo_permiso_id('Ordenes de Trabajo','ordenes'.$idGrpPermiso,$idGrpPermiso);
            $idGrp1 = $this->db->insert_id();
            $this->Actividad_model->insert_permiso('Ver','ver_orden',$idGrp1);
            $this->Actividad_model->insert_permiso('Nuevo/Editar','editar_orden',$idGrp1);
            $this->Actividad_model->insert_permiso('Eliminar','eliminar_orden',$idGrp1);
            $this->Actividad_model->insert_permiso('Penalizar','penalizar_orden',$idGrp1);
            $this->Actividad_model->insert_grupo_permiso_id('Valorizaciones','valorizacion'.$idGrpPermiso,$idGrpPermiso);
            $idGrp2 = $this->db->insert_id();
            $this->Actividad_model->insert_permiso('Ver','ver_valorizacion',$idGrp2);
            $this->Actividad_model->insert_permiso('Nuevo/Editar','editar_valorizacion',$idGrp2);
            $this->Actividad_model->insert_permiso('Eliminar','eliminar_valorizacion',$idGrp2);
            $this->session->set_flashdata('mensaje', array('success', 'La Actividad Comercial se ha registrado satisfactoriamente. Registre las Sub-Actividades correspondientes a esta.'));
            redirect(base_url() . 'configuracion/subactividad/nuevo');
        } else 
        {
            $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            $this->actividad_nuevo();
        }
    }

    public function actividad_editar($idActividad) 
    {
        $actividad = $this->Actividad_model->get_one_actividad($idActividad);
        if (!isset($actividad)) 
        {
            show_404();
        }
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);

        $this->data['actividad'] = $actividad;
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'general/Actividad_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Actividades Comerciales', 'configuracion/actividad'), array('Editar Actividad Comercial', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function actividad_editar_guardar($idActividad) 
    {
        $actividad = $this->Actividad_model->get_one_actividad($idActividad);
        if (!isset($actividad)) 
        {
            show_404();
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('descripcion', 'Nombre', 'required');

        if ($this->form_validation->run() == TRUE) 
        {
            $descripcion = $this->input->post('descripcion');
            $this->Actividad_model->update_actividad($idActividad, $descripcion);
            $this->session->set_flashdata('mensaje', array('exito', 'La Actividad Comercial se ha actualizado correctamente.'));
            redirect(base_url() . 'configuracion/actividad/editar/' . $idActividad);
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            $this->actividad_editar($idActividad);
        }
    }

    public function actividad_eliminar($idActividad) 
    {
        $actividad = $this->Actividad_model->get_one_actividad($idActividad);
        if (!isset($actividad)) 
        {
            show_404();
        }
        $this->Actividad_model->delete_actividad($idActividad);
        $this->session->set_flashdata('mensaje', array('success', 'La Actividad Comercial se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'configuracion/actividad');
    }
    
}
