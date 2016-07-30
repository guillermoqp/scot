<?php

class OrdenValorizacion_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('general/Contrato_model');
        $this->load->model('general/Actividad_model');
        $this->load->model('general/SubActividad_model');
        $this->load->model('general/Contratista_model');
        $this->load->model('general/Penalizacion_model');
        $this->load->model('general/OrdenValorizacion_model');

        $this->load->library('acceso_cls');
        $this->load->library('utilitario_cls');

        $this->acceso_cls->isLogin();
        //$this->data['proceso'] = 'Toma de Estado';
        //$this->data['menu']['padre'] = 'toma_estado';
        //$this->data['menu']['hijo'] = 'valorizacion';
    }

    // Orden_General_Valorizaciones: (:any)/valorizacion(:any)
    public function valorizacion_listar($Cod,$PerVal) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_valorizacion');
        /*  VALIDAR QUE PUEDA GENERAR ORDENES DE TRABAJO SEGUN CONTRATO Y ACTIVIDAD CONTRATADA   */
        $idContratante = $this->data['userdata']['contratante']['CntId'];
        $actividades = $this->Actividad_model->get_all_actividad_permiso();
        foreach ($actividades as $actividad):
            if ($actividad['ActCod'] == $Cod) {
                $idActividad = $actividad['ActId'];
                $padre = $actividad['ActCod'];
                $des = $actividad['ActDes'];
                foreach ($actividad['permisos'] as $permiso){
                    if( $permiso['GpeDes'] == 'Valorizaciones'){
                        $hijo = $permiso['GpeVal'];
                    }
                }
            }
        endforeach;
        $idContrato = $this->Contrato_model->get_contrato_vigente_x_actividad($idActividad, $idContratante);
        $idGpeVal = $this->Actividad_model->get_id_x_GpeVal($padre);
        //Si $PerVal es igual al id de grupo_permiso de actividad en tabla: Grupo_permiso
        if ($idGpeVal===$PerVal)
        {
            if ($idContrato != FALSE) {
                $this->data['valorizaciones'] = $this->OrdenValorizacion_model->get_all_valorizaciones_x_act($idActividad);
                $this->data['view'] = 'general/OrdenValorizacion_lista_view';
                $this->data['menu']['padre'] = $padre;
                $this->data['menu']['hijo'] = $hijo;
                $this->data['padre'] = $padre;
                $this->data['hijo'] = $hijo;
                $this->data['proceso'] = $des;
                $this->data['breadcrumbs'] = array(array('Valorizaciones', ''));
                $this->load->view('template/Master', $this->data);
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'No tiene esta Actividad Contratada, no puede hacer Valorizaciones de '.$des.'.'));
                $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                redirect(base_url() . '401');
            }
        }
        else {
            show_404();
        }
    }

    // Orden_General_Valorizaciones: (:any)/valorizacion(:any)/nuevo
    public function valorizacion_nuevo($Cod,$PerVal) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_valorizacion');
        $actividades = $this->Actividad_model->get_all_actividad_permiso();
        foreach ($actividades as $actividad):
            if ($actividad['ActCod'] == $Cod) {
                $idActividad = $actividad['ActId'];
                $padre = $actividad['ActCod'];
                $des = $actividad['ActDes'];
                foreach ($actividad['permisos'] as $permiso){
                    if( $permiso['GpeDes'] == 'Valorizaciones'){
                        $hijo = $permiso['GpeVal'];
                    }
                }
            }
        endforeach;
        $idGpeVal = $this->Actividad_model->get_id_x_GpeVal($padre);
        if ($idGpeVal===$PerVal)
        {
            /*  VALIDAR QUE PUEDA GENERAR ORDENES DE TRABAJO SEGUN CONTRATO Y ACTIVIDAD CONTRATADA   */
            $idContratante = $this->data['userdata']['contratante']['CntId'];
            $idContrato = $this->Contrato_model->get_contrato_vigente_x_actividad($idActividad, $idContratante);
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('numero', 'Numero', 'required');
                $this->form_validation->set_rules('anio', 'Año', 'required');
                $this->form_validation->set_rules('mes', 'Mes', 'required');
                $this->form_validation->set_rules('PrlFchIn', 'Fecha Inicio', 'required');
                $this->form_validation->set_rules('PrlFchFn', 'Fecha Fin', 'required');
                if ($this->form_validation->run() == TRUE) {
                    $numero = $this->input->post('numero');
                    $anio = $this->input->post('anio');
                    $mes = $this->input->post('mes');
                    $nroMes = $this->utilitario_cls->numeroMes($mes);
                    $fechaIn = $this->input->post('PrlFchIn');
                    $fechaFn = $this->input->post('PrlFchFn');
                    $this->OrdenValorizacion_model->insert_valorizacion($numero, $anio, $nroMes, $fechaIn, $fechaFn, $idActividad, $idContrato, $idContratante);
                    $this->session->set_flashdata('mensaje', array('exito', 'La valorización N° ' . $numero . ' se ha creado satisfactoriamente.'));
                    redirect(base_url() . $padre.'/'.$hijo);
                } else {
                    $this->session->set_flashdata('error', 'Complete los campos.');
                }
            } else {
                $this->data['anio'] = date('Y');
                $this->data['mes'] = $this->utilitario_cls->nombreMes(date('m') + 0);
            }
            $this->data['nroValorizacion'] = $this->OrdenValorizacion_model->get_sgte_num($idActividad, $idContrato);
            $this->data['view'] = 'general/OrdenValorizacion_uno_view';
            $this->data['accion'] = 'nuevo';
            $this->data['menu']['padre'] = $padre;
            $this->data['menu']['hijo'] = $hijo;
            $this->data['padre'] = $padre;
            $this->data['hijo'] = $hijo;
            $this->data['proceso'] = $des;
            $this->data['breadcrumbs'] = array(array('Valorizaciones', $padre.'/'.$hijo), array('Registrar Valorización', ''));
            $this->load->view('template/Master', $this->data);
        }
        else {
            show_404();
        }
    }

    // toma_estado/valorizacion/editar/(:num)
    public function valorizacion_editar($idValorizacion) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_valorizacion');
        $valorizacion = $this->OrdenValorizacion_model->get_one_valorizacion($idValorizacion);
        if (!isset($valorizacion)) {
            show_404();
        }
        $this->OrdenValorizacion_model->update_valorizacion($idValorizacion);
        $this->session->set_flashdata('mensaje', array('success', 'La valorización se ha actualizado satisfactoriamente.'));
        redirect(base_url() . 'toma_estado/valorizacion');
    }

    // toma_estado/valorizacion/eliminar/(:num)
    public function valorizacion_eliminar($idValorizacion) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_valorizacion');
        $valorizacion = $this->OrdenValorizacion_model->get_one_valorizacion($idValorizacion);
        if (!isset($valorizacion)) {
            show_404();
        }
        $this->OrdenValorizacion_model->delete_valorizacion($idValorizacion);
        $this->session->set_flashdata('mensaje', array('success', 'La valorización se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'toma_estado/valorizacion');
    }

    // toma_estado/valorizacion/detalle/(:num)
    public function valorizacion_detalle($idValorizacion) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_valorizacion');
        $valorizacion = $this->OrdenValorizacion_model->get_one_valorizacion($idValorizacion);
        if (!isset($valorizacion)) {
            show_404();
        }
        $actividad = $this->Actividad_model->get_one_actividad($valorizacion['VlrActId']);
        $contratista = $this->Contratista_model->get_one_contratista_x_contrato($valorizacion['VlrConId']);
        $valSubs = $this->OrdenValorizacion_model->get_valorizacion_orden_subactividad_x_valorizacion($valorizacion['VlrId']);
        foreach ($valSubs as $key => $valSub) {
            $valSubs[$key]['subAct'] = $this->SubActividad_model->get_one_subactividad($valSub['VloSacId']);
        }
        $ordenes = $this->OTTomaEstado_model->get_all_orden_x_valorizacion($idValorizacion);
        $this->data['valorizacion'] = $valorizacion;
        $this->data['valSubs'] = $valSubs;
        $this->data['ordenes'] = $ordenes;
        $this->data['actividad'] = $actividad;
        $this->data['contratista'] = $contratista;
        $this->data['view'] = 'lectura/Valorizacion_detalle_view';
        $this->data['breadcrumbs'] = array(array('Valorizaciones', 'toma_estado/valorizacion'), array('Valorización ' . $valorizacion['VlrNum'], ''));
        $this->load->view('template/Master', $this->data);
    }
    
    // toma_estado/valorizacion/detalle/(:num)/orden/(:num)
    public function valorizacion_orden($idValorizacion, $idOrden) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_valorizacion');
        $valorizacion = $this->OrdenValorizacion_model->get_one_valorizacion($idValorizacion);
        if (!isset($valorizacion)) {
            show_404();
        }
        $actividad = $this->Actividad_model->get_one_actividad($valorizacion['VlrActId']);
        $contratista = $this->Contratista_model->get_one_contratista_x_contrato($valorizacion['VlrConId']);
        // Orden de trabajo que se esta detallando
        $orden = $this->OTTomaEstado_model->get_one_orden_trabajo($idOrden);
        $orden['metradoEj'] = 0;
        $orden['subtotal'] = 0;
        $orden['descuento'] = $this->OTTomaEstado_model->get_descuento_x_idOrden($idOrden);
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
        $this->data['view'] = 'lectura/Valorizacion_orden_view';
        $this->data['breadcrumbs'] = array(array('Valorizaciones', 'toma_estado/valorizacion'), array('Valorización ' . $valorizacion['VlrNum'], 'toma_estado/valorizacion/detalle/'.$valorizacion['VlrId']), array('Orden Nro. '.$orden['OrtNum'],''));
        $this->load->view('template/Master', $this->data);
    }

}
