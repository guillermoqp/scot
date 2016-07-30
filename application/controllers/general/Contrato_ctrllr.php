<?php

class Contrato_ctrllr extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('general/Contrato_model');
        $this->load->model('general/Contratista_model');
        $this->load->model('general/Contratante_model');
        $this->load->model('general/Actividad_model');
        $this->load->model('general/Penalidad_model');
        $this->load->model('general/SubActividad_model');
        $this->load->model('general/Unidad_model');

        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();
        $this->data['menu']['padre'] = 'contratacion';
        $this->data['menu']['hijo'] = 'contrato';
    }

    public function contrato_listar() 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_contrato');
        $this->data['contratos'] = $this->Contrato_model->get_all_contrato_x_contratante($this->data['userdata']['contratante']['CntId']);
            
        $this->data['view'] = 'general/Contratos_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Contratos',''));
        $this->load->view('template/Master', $this->data);
    }
    
    public function get_contrato_x_id_json()
    {   
        /* Obtener valosres por json    */
        $idContrato = $this->input->post('idContrato');
        $contrato = $this->Contrato_model->get_one_contrato($idContrato);
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($contrato));
        /*  print json_encode($data); */
    } 
    
    public function get_detalle_contrato_x_id_json()
    {   
        /* Obtener valosres por json    */
        $idContrato = $this->input->post('idContrato');
        $detalleContrato = $this->Contrato_model->get_detalle_contrato_x_id($idContrato);
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($detalleContrato));
        /*  print json_encode($data); */
    } 
    
    public function get_contratista_contrato_x_id_json()
    {   
        /* Obtener valosres por json    */
        $idContratista = $this->input->post('idContratista');
        $contratista = $this->Contratista_model->get_one_contratista($idContratista);
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($contratista));
        /*  print json_encode($data); */
    } 
    
    
    public function contrato_nuevo() 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_contrato');
        $this->data['actividades'] = $this->Actividad_model->get_all_actividad_subactividad();
        
        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'general/Contrato_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Contratos', 'contratos'), array('Registrar Contrato', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function contrato_nuevo_guardar() 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_contrato');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('numContrato', 'Numero de Contrato', 'required');
        $this->form_validation->set_rules('numConcurso', 'Numero de Concurso Publico', 'required');
        $this->form_validation->set_rules('FchIn', 'Fecha de Inicio Contrato', 'required');
        $this->form_validation->set_rules('FchFn', 'Fecha Fin Contrato', 'required');
        $this->form_validation->set_rules('descripcion', 'Descripcion de Contrato', 'required');

        /*
         * $this->form_validation->set_rules('actividades', 'Actividades Comerciales', 'required');
        $subactividades = $this->SubActividad_model->get_all_subactividad();
        foreach ($subactividades as $subactividad) 
        {
            $this->form_validation->set_rules('met_' . $subactividad['SacId'] , 'Metrado por Subactividad' , 'required');
            $this->form_validation->set_rules('pru_' . $subactividad['SacId'] , 'Fecha', 'required');
            $this->form_validation->set_rules('ren_' . $subactividad['SacId'] , 'Dias', 'required');
        }
       */
        
        if ($this->form_validation->run() == TRUE) 
        {
            $numContrato = $this->input->post('numContrato');
            $FchIn = $this->input->post('FchIn');
            $FchFn  = $this->input->post('FchFn');
            $descripcion   = $this->input->post('numConcurso')." , " .$this->input->post('descripcion');
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            $IdContratante = $this->data['userdata']['UsrCntId'];
            $IdContratista = $this->input->post('contratista_id');
            $actividades = $this->input->post('actividades');
            $subactividades = $this->SubActividad_model->get_all_subactividad();
            foreach ($subactividades as $subactividad) 
            {
                $metrado = $this->input->post('met_' . $subactividad['SacId'] );
                $precioUnitario = $this->input->post('pru_' . $subactividad['SacId'] );
                $rendimiento = $this->input->post('ren_' . $subactividad['SacId'] );
                if($metrado!=""&&$precioUnitario!=""&&$rendimiento!="")
                {
                    $ContratoSubActividad[$subactividad['SacId']]['idSubActividad'] = $subactividad['SacId'];
                    $ContratoSubActividad[$subactividad['SacId']]['metrado'] = $metrado;
                    $ContratoSubActividad[$subactividad['SacId']]['precioUnitario'] = $precioUnitario;
                    $ContratoSubActividad[$subactividad['SacId']]['rendimiento'] = $rendimiento;
                }
            }
            $idContrato = $this->Contrato_model->insert_contrato($numContrato,$descripcion,$FchIn,$FchFn,$IdContratante,$IdContratista,$ContratoSubActividad);
            
            if($idContrato!=false)
            {
                $this->session->set_flashdata('mensaje', array('success', 'El contrato se ha Guardado Satisfactoriamente. Registre Grupo de Penalidades a este contrato.'));
                /*$this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                $this->data['accion'] = 'nuevo';
                $this->data['view'] = 'general/Contrato_view';
                $this->data['breadcrumbs'] = array(array('Contratos', ''));
                $this->load->view('template/Master', $this->data);
                redirect(base_url().'contratos');*/
                redirect(base_url().'contrato/'.$idContrato.'/precios_penalidades/nuevo');
            }
            else
            {
                $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al momento de guardar el contrato.'));
                $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                $this->data['accion'] = 'nuevo';
                $this->data['view'] = 'general/Contrato_view';
                $this->data['breadcrumbs'] = array(array('Contratos', 'contratos'), array('Registrar Contrato', ''));
                $this->load->view('template/Master', $this->data);
                redirect(base_url().'contrato/nuevo');
            }
        } 
        else 
        {
            $this->session->set_flashdata('mensaje', array('error', 'Complete los campos o algun error adicional.'));
            $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            $this->data['accion'] = 'nuevo';
            $this->data['view'] = 'general/Contrato_view';
            $this->data['breadcrumbs'] = array(array('Contratos', 'contratos'), array('Registrar Contrato', ''));
            $this->load->view('template/Master', $this->data);
            redirect(base_url().'contrato/nuevo');
        }
        
    }
    
    public function contrato_editar($idContrato) 
    {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_contrato');
        $contratoEdit = $this->Contrato_model->get_one_contrato($idContrato);
        if (!isset($contratoEdit)) 
        {
            show_404();
        }
        $this->data['contratoEdit'] = $contratoEdit;
        $this->data['detallesContrato'] = $this->Contrato_model->get_detalle_contrato_x_id($contratoEdit['ConId']);
        $this->data['actividadesContratadas'] = $this->Contrato_model->get_actividades_x_contrato_id($contratoEdit['ConId']);
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'general/Contrato_view';
        $this->data['proceso'] = 'Contratos';
        $this->data['breadcrumbs'] = array(array('Contratos','contratos'),array('Editar Contrato',''));
        $this->load->view('template/Master', $this->data);
    }
    
    public function contrato_editar_guardar($idContrato) 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_contrato');
        if (!isset($idContrato)) 
        {
            show_404();
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('numContrato', 'Numero de Contrato', 'required');
        $this->form_validation->set_rules('FchIn', 'Fecha de Inicio Contrato', 'required');
        $this->form_validation->set_rules('FchFn', 'Fecha Fin Contrato', 'required');
        
        if ($this->form_validation->run() == TRUE) 
        {
            $numContrato = $this->input->post('numContrato');
            $FchIn = $this->input->post('FchIn');
            $FchFn  = $this->input->post('FchFn');
            $descripcion   = $this->input->post('descripcion');
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            $IdContratante = $this->data['userdata']['UsrCntId'];
            $IdContratista = $this->input->post('contratista_id');
            $actividades = $this->input->post('actividades');
            $subactividades = $this->SubActividad_model->get_all_subactividad();
            
            $ConVig = $this->input->post('ConVig');

            foreach ($subactividades as $subactividad) 
            {
                $metrado = $this->input->post('met_' . $subactividad['SacId'] );
                $precioUnitario = $this->input->post('pru_' . $subactividad['SacId'] );
                $rendimiento = $this->input->post('ren_' . $subactividad['SacId'] );
                if($metrado!=""&&$precioUnitario!=""&&$rendimiento!="")
                {
                    $ContratoSubActividad[$subactividad['SacId']]['idSubActividad'] = $subactividad['SacId'];
                    $ContratoSubActividad[$subactividad['SacId']]['metrado'] = $metrado;
                    $ContratoSubActividad[$subactividad['SacId']]['precioUnitario'] = $precioUnitario;
                    $ContratoSubActividad[$subactividad['SacId']]['rendimiento'] = $rendimiento;
                }
            }
            $retorno = $this->Contrato_model->update_contrato($numContrato,$descripcion,$FchIn,$FchFn,$ConVig ,$IdContratante,$IdContratista,$ContratoSubActividad,$idContrato);
            if($retorno)
            {
                $this->session->set_flashdata('mensaje', array('success','Los datos del Contrato se han actualizado satisfactoriamente.'));
                redirect(base_url() . 'contrato/editar/'.$idContrato);
            }
            else
            {
                $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al momento de guardar el contrato.'));
                $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                $this->data['accion'] = 'editar';
                $this->data['view'] = 'general/Contrato_view';
                $this->data['breadcrumbs'] = array(array('Contratos', 'contratos'), array('Editar Contrato', ''));
                $this->load->view('template/Master', $this->data);
                redirect(base_url() . 'contrato/editar/'.$idContrato);
            }  
        } 
        else 
        {
            $this->session->set_flashdata('mensaje', array('error', 'Porfavor, Complete los campos obligatorios.'));
            $this->contrato_editar($idContrato);
        } 
    }
    
    public function contrato_eliminar($idContrato) 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('eliminar_contrato');
        $this->Contrato_model->delete_contrato($idContrato);
        $this->session->set_flashdata('mensaje', array('success', 'El Contrato por servicios Comerciales se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'contratos');
    }

    public function autoCompleteContratista()
    {
        if (isset($_GET['term']))
        {
            $q = strtolower($_GET['term']);
            $this->Contrato_model->autoCompleteContratista($q);
        }
    }

    /* PRECIOS DE PENALIDADES   */
    
    public function contrato_precios_penalidades($idContrato) 
    {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_contrato');
        $detallesContratoPenalidad = $this->Contrato_model->get_detalle_contrato_penalidad($idContrato);
        $contrato = $this->Contrato_model->get_one_contrato($idContrato); 
        if (!isset($detallesContratoPenalidad)) 
        {
            show_404();
        }
        $this->data['contrato'] = $contrato ;
        $this->data['detallesContratoPenalidad'] =  $detallesContratoPenalidad;
        //$this->data['gruposPenalidad'] = $this->Penalidad_model->get_all_grpPenalidad_penalidad($this->data['userdata']['contratante']['CntId']);
        $this->data['gruposPenalidad'] = $this->Contrato_model->get_all_grupo_penalidad_x_contrato($idContrato);
        $this->data['accion'] = 'ver';
        $this->data['view'] = 'general/PreciosPenalidades_view';
        $this->data['proceso'] = 'Contratos';
        $this->data['breadcrumbs'] = array(array('Contratos','contratos'),array('Precios de Penalidades por Contrato',''));
        $this->load->view('template/Master', $this->data);
    }
    
    public function contrato_precios_penalidades_nuevo($idContrato) 
    {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_contrato');
        if (!isset($idContrato)) 
        {
            show_404();
        }
        $this->data['gruposPenalidad'] = $this->Penalidad_model->get_all_grpPenalidad_penalidad($this->data['userdata']['contratante']['CntId']);
        $this->data['accion'] = 'ver';
        $this->data['view'] = 'general/PrecioPenalidad_view';
        $this->data['proceso'] = 'Contratos';
        $this->data['breadcrumbs'] = array(array('Contratos','contratos'),array('Precios de Penalidades por Contrato','contrato/'.$idContrato.'/precios_penalidades'),array('Registrar Precios por Penalidades al contrato',''));
        $this->load->view('template/Master', $this->data);
    }
    
    public function contrato_precios_penalidades_nuevo_guardar($idContrato) 
    {
        $grupoPenalidades = $this->input->post('grupoPenalidades');
        $penalidades = $this->Penalidad_model->get_all_penalidad();
        
        $DcpConId = $idContrato;
     
        foreach ($penalidades as $penalidad) 
        {
            $multa = $this->input->post('mul_' . $penalidad['PenId']);
     
            if ($multa != "") 
            {
                $ContratoPreciosPenalidades[$penalidad['PenId']]['idPenalidad'] = $penalidad['PenId'];
                $ContratoPreciosPenalidades[$penalidad['PenId']]['multa'] = $multa;
            }
        }
        
        $retorno = $this->Contrato_model->insert_detalle_contrato_penalidades($DcpConId ,  $ContratoPreciosPenalidades );
        
        if($retorno) 
        {
            $this->session->set_flashdata('mensaje', array('success', 'Los Precios por Penalidades del Contrato se han Guardado Correctamente'));
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            $this->data['accion'] = 'ver';
            $this->data['view'] = 'general/PreciosPenalidades_view';
            $this->data['proceso'] = 'Contratos';
            $this->data['breadcrumbs'] = array(array('Contratos', 'contratos'), array('Precios de Penalidades por Contrato', ''));
            $this->load->view('template/Master', $this->data);
            redirect(base_url() . 'contrato/'.$idContrato.'/precios_penalidades');
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al momento de guardar los precios de Penalidades por Contrato.'));
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            $this->data['accion'] = 'ver';
            $this->data['view'] = 'general/PrecioPenalidad_view';
            $this->data['proceso'] = 'Contratos';
            $this->data['breadcrumbs'] = array(array('Contratos', 'contratos'),array('Precios de Penalidades por Contrato','contrato/'.$idContrato.'/precios_penalidades'), array('Registrar Precios por Penalidades al contrato', ''));
            $this->load->view('template/Master', $this->data);
             redirect(base_url() . 'contrato/'.$idContrato.'/precios_penalidades/nuevo');
        }
        
    }
    
    public function contrato_precios_penalidades_editar($idContrato , $idPrecioPenalidad) 
    {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_contrato');
        
        $this->data['precioPenalidadEdit'] = $this->Contrato_model->get_one_detalle_contrato_penalidad_precio($idContrato,$idPrecioPenalidad);
                
        if (!isset($idPrecioPenalidad)) 
        {
            show_404();
        }
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'general/PrecioPenalidad_view';
        $this->data['proceso'] = 'Contratos';
        $this->data['breadcrumbs'] = array(array('Contratos','contratos'),array('Precios de Penalidades por Contrato','contrato/'.$idContrato.'/precios_penalidades'),array('Editar Precio por Penalidad del contrato',''));
        $this->load->view('template/Master', $this->data);
    }
    
    public function contrato_precios_penalidades_editar_guardar($idContrato , $idPrecioPenalidad) 
    {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_contrato');
        if (!isset($idPrecioPenalidad)) 
        {
            show_404();
        }
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('multa', 'Precio de Multa', 'required');
  
        if ($this->form_validation->run() == TRUE) 
        {
            $DcpId = $idPrecioPenalidad;
            $DcpVal = $this->input->post('multa');
        
            $retorno =  $this->Contrato_model->update_precio_penalidad($DcpId , $DcpVal);
            if($retorno)
            {
                $this->session->set_flashdata('mensaje', array('success','La Multa de la Penalidad se ah actualizado.'));
                redirect(base_url() . 'contrato/'.$idContrato.'/precios_penalidades/editar/'.$idPrecioPenalidad);  
            }
            else
            {
                $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al momento de guardar la Multa de la Penalidad.'));
                $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                $this->data['view'] = 'general/PrecioPenalidad_view';
                $this->data['proceso'] = 'Contratos';
                $this->data['breadcrumbs'] = array(array('Contratos','contratos'),array('Precios de Penalidades por Contrato','contrato/'.$idContrato.'/precios_penalidades'),array('Editar Precio por Penalidad del contrato',''));
                $this->load->view('template/Master', $this->data);
            }  
        }
        else 
        {
            $this->session->set_flashdata('mensaje', array('error', 'Porfavor, Complete los campos obligatorios.'));
            $this->contrato_precios_penalidades_editar($idContrato , $idPrecioPenalidad);
        } 
    }
    
    public function contrato_precios_penalidades_eliminar($idContrato , $idPrecioPenalidad) 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_contrato');

        $this->Contrato_model->delete_precio_penalidad($idContrato , $idPrecioPenalidad);
 
        $this->session->set_flashdata('mensaje', array('success', 'El Precio de Penalidad por Contrato de servicios Comerciales se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'contrato/'.$idContrato.'/precios_penalidades');  
    }

    
}
