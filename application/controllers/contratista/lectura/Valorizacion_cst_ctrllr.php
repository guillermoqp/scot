<?php

class Valorizacion_cst_ctrllr extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model('general/Contrato_model');
        $this->load->model('general/Actividad_model');
        $this->load->model('general/SubActividad_model');
        $this->load->model('general/Contratista_model');
        $this->load->model('general/Penalizacion_model');
        $this->load->model('lectura/Valorizacion_model');
               
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->load->library('utilitario_cls');
        
        $this->acceso_cls->isLogin();
        $this->data['proceso'] = 'Toma de Estado';
        $this->data['menu']['padre'] = 'toma_estado';
        $this->data['menu']['hijo'] = 'valorizacion';
    }
    
    /*  METODOS PARA VALORIZACIONES DE LA ACTIVIDAD TOMA DE ESTADO  */
    
    public function valorizacion_listar() 
    {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_valorizacion');
        
        /*  VALIDAR QUE PUEDA GENERAR ORDENES DE TRABAJO SEGUN CONTRATO Y ACTIVIDAD CONTRATADA   */
        $idContratante = $this->data['userdata']['contratante']['CntId'];
        $idActividad = $this->Actividad_model->get_id_actividad_x_codigo('toma_estado');
        $idContrato = $this->Contrato_model->get_contrato_vigente_x_actividad($idActividad, $idContratante);

        if ($idContrato != FALSE) 
        {  
            $Actividad = $this->Actividad_model->get_id_actividad_x_descripcion($this->data['proceso']);
            $idActividad = implode(",",$Actividad);
            $this->data['valorizaciones'] = $this->Valorizacion_model->get_all_valorizaciones_x_act($idActividad);
            $this->data['view'] = 'contratista/lectura/Valorizacion_lista_cst_view'; 
            $this->data['breadcrumbs'] = array(array('Valorizaciones', ''));
            $this->load->view('contratista/template/Master_cst_view', $this->data);  
        }
        else {
            $this->session->set_flashdata('mensaje', array('error', 'No tiene esta Actividad Contratada, no puede hacer Valorizaciones de Toma de Estado de Suministros.'));
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            redirect(base_url() . '401');
        }
    }
    
    // cst/toma_estado/valorizacion/detalle/(:num)
    public function valorizacion_detalle($idValorizacion) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        //$this->acceso_cls->verificarPermiso('editar_valorizacion');
        $valorizacion = $this->Valorizacion_model->get_one_valorizacion($idValorizacion);
        if (!isset($valorizacion)) {
            show_404();
        }
        $actividad = $this->Actividad_model->get_one_actividad($valorizacion['VlrActId']);
        $contratista = $this->Contratista_model->get_one_contratista_x_contrato($valorizacion['VlrConId']);
        $valSubs = $this->Valorizacion_model->get_valorizacion_orden_subactividad_x_valorizacion($valorizacion['VlrId']);
        foreach ($valSubs as $key => $valSub) {
            $valSubs[$key]['subAct'] = $this->SubActividad_model->get_one_subactividad($valSub['VloSacId']);
        }
        $ordenes = $this->OTTomaEstado_model->get_all_orden_x_valorizacion($idValorizacion);
        $this->data['valorizacion'] = $valorizacion;
        $this->data['valSubs'] = $valSubs;
        $this->data['ordenes'] = $ordenes;
        $this->data['actividad'] = $actividad;
        $this->data['contratista'] = $contratista;
        $this->data['view'] = 'contratista/lectura/Valorizacion_detalle_cst_view';
        $this->data['breadcrumbs'] = array(array('Valorizaciones', 'cst/toma_estado/valorizacion'), array('Valorización ' . $valorizacion['VlrNum'], ''));
        $this->load->view('contratista/template/Master_cst_view', $this->data);
    }
    
    // toma_estado/valorizacion/detalle/(:num)/orden/(:num)
    public function valorizacion_orden($idValorizacion, $idOrden) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        //$this->acceso_cls->verificarPermiso('editar_valorizacion');
        $valorizacion = $this->Valorizacion_model->get_one_valorizacion($idValorizacion);
        if (!isset($valorizacion)) {
            show_404();
        }
        $actividad = $this->Actividad_model->get_one_actividad($valorizacion['VlrActId']);
        $contratista = $this->Contratista_model->get_one_contratista_x_contrato($valorizacion['VlrConId']);
        // Orden de trabajo que se esta detallando
        $orden = $this->OTTomaEstado_model->get_one_orden_trabajo($idOrden);
        $orden['metradoEj'] = 0;
        $orden['subtotal'] = 0;
        $desc = $this->OTTomaEstado_model->get_descuento_x_idOrden($idOrden);
        if(is_null($desc)){
            $orden['descuento'] = 0;
        } else {
            $orden['descuento'] = $desc;
        }
        // La orden dividida entre las subactividades que tiene
        $ordenSubs = $this->OTTomaEstado_model->get_orden_subactividad_x_orden($idOrden);
        foreach ($ordenSubs as $key => $ordenSub) {
            $orden['metradoEj'] += $ordenSub['OtsMetEj'];
            $orden['subtotal'] += $ordenSub['OtsMonEj'];
        }
        $orden['total'] = $orden['subtotal'] - $orden['descuento'];
        $orden['metradoPl'] = $this->Lectura_model->get_cant_lect_x_orden($idOrden);
        // Las penalizaciones de la orden
        $penalizaciones = $this->Penalizacion_model->get_all_penalizaciones_x_orden($idOrden);
        $this->data['valorizacion'] = $valorizacion;
        $this->data['ordenSubs'] = $ordenSubs;
        $this->data['orden'] = $orden;
        $this->data['penalizaciones'] = $penalizaciones;
        $this->data['actividad'] = $actividad;
        $this->data['contratista'] = $contratista;
        $this->data['view'] = 'contratista/lectura/Valorizacion_orden_cst_view';
        $this->data['breadcrumbs'] = array(array('Valorizaciones', 'cst/toma_estado/valorizacion'), array('Valorización ' . $valorizacion['VlrNum'], 'cst/toma_estado/valorizacion/detalle/'.$valorizacion['VlrId']), array('Orden Nro. '.$orden['OrtNum'],''));
        $this->load->view('contratista/template/Master_cst_view', $this->data);
    }
}
