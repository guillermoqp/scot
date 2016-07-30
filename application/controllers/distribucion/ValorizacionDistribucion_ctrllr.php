<?php

class ValorizacionDistribucion_ctrllr extends CI_Controller {
    var $idContratante;
    var $idActividad;
    public function __construct() {
        parent::__construct();
        $this->load->model('general/Contrato_model');
        $this->load->model('general/Actividad_model');
        $this->load->model('general/SubActividad_model');
        $this->load->model('distribucion/Distribucion_model');
        $this->load->model('distribucion/OTDistribucion_model');
        $this->load->model('distribucion/ValorizacionDistribucion_model');
        $this->load->model('general/Contratista_model');
        $this->load->model('general/Penalizacion_model');


        $this->load->library('acceso_cls');
        $this->load->library('utilitario_cls');

        $this->acceso_cls->isLogin();
        $this->data['proceso'] = 'Distribución de recibos';
        $this->data['menu']['padre'] = 'distribucion';
        $this->data['menu']['hijo'] = 'valorizacion_distribucion';
        
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->idContratante = $this->data['userdata']['contratante']['CntId'];
        $this->idActividad = $this->Actividad_model->get_id_actividad_x_codigo('distribucion');
    }

    // distribucion/valorizacion
    public function valorizacion_distribucion_listar() {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_valorizacion');

        /*  VALIDAR QUE PUEDA GENERAR ORDENES DE TRABAJO SEGUN CONTRATO Y ACTIVIDAD CONTRATADA   */
        $idContrato = $this->Contrato_model->get_contrato_vigente_x_actividad($this->idActividad, $this->idContratante);

        if ($idContrato != FALSE) {
            $this->data['valorizaciones'] = $this->ValorizacionDistribucion_model->get_all_valorizaciones_x_act($this->idActividad);
            $this->data['view'] = 'distribucion/ValorizacionDistribucion_lista_view';
            $this->data['breadcrumbs'] = array(array('Valorizaciones', ''));
            $this->load->view('template/Master', $this->data);
        } else {
            $this->session->set_flashdata('mensaje', array('error', 'No tiene esta Actividad Contratada, no puede hacer Valorizaciones de Distribucion de recibos.'));
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            redirect(base_url() . '401');
        }
    }

    // distribucion/valorizacion/nuevo
    public function valorizacion_distribucion_nuevo() {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_valorizacion');

        /*  VALIDAR QUE PUEDA GENERAR ORDENES DE TRABAJO SEGUN CONTRATO Y ACTIVIDAD CONTRATADA   */
        $idContrato = $this->Contrato_model->get_contrato_vigente_x_actividad($this->idActividad, $this->idContratante);
        /*  VALIDAR QUE EXISTAN ORDENES CREADAS  */
        $ordenes = $this->OTDistribucion_model->get_all_orden_trabajo_x_contratante_cerradas($this->idContratante, $this->idActividad);

        if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['btn_valorizacion_guardar'] )) {
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
                $this->ValorizacionDistribucion_model->insert_valorizacion($numero, $anio, $nroMes, $fechaIn, $fechaFn, $this->idActividad, $idContrato, $this->idContratante);
                $this->session->set_flashdata('mensaje', array('exito', 'La valorización N° ' . $numero . ' se ha creado satisfactoriamente.'));
                redirect(base_url() . 'distribucion/valorizacion');
            } else {
                $this->session->set_flashdata('error', 'Complete los campos.');
            }
        }

        if($idContrato!=FALSE)
        {
            if(count($ordenes) > 0)
            {
                $this->data['anio'] = date('Y');
                $this->data['mes'] = $this->utilitario_cls->nombreMes(date('m') + 0);
                $this->data['nroValorizacion'] = $this->ValorizacionDistribucion_model->get_sgte_num($this->idActividad, $idContrato);
                $this->data['view'] = 'distribucion/ValorizacionDistribucion_uno_view';
                $this->data['accion'] = 'nuevo';
                $this->data['breadcrumbs'] = array(array('Valorizaciones', 'distribucion/valorizacion'), array('Registrar Valorización', ''));
                $this->load->view('template/Master', $this->data);
            }
            else{
                $this->session->set_flashdata('mensaje', array('error', 'Aun no existen Ordenes de Distribución de Recibos CERRADAS para Valorizar.'));
                $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                redirect(base_url() . 'distribucion/valorizacion');
            }
        }
        else{
            $this->session->set_flashdata('mensaje', array('error', 'No tiene esta Actividad Contratada, no puede hacer Distribución de Recibos.'));
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            redirect(base_url() . '401');
            } 
    }

    // distribucion/valorizacion/editar/(:num)
//    public function valorizacion_editar($idValorizacion) {
//        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
//        $this->acceso_cls->verificarPermiso('editar_valorizacion');
//        $valorizacion = $this->Valorizacion_model->get_one_valorizacion($idValorizacion);
//        if (!isset($valorizacion)) {
//            show_404();
//        }
//        $this->Valorizacion_model->update_valorizacion($idValorizacion);
//        $this->session->set_flashdata('mensaje', array('success', 'La valorización se ha actualizado satisfactoriamente.'));
//        redirect(base_url() . 'toma_estado/valorizacion');
//    }

    // distribucion/valorizacion/eliminar/(:num)
    public function valorizacion_distribucion_eliminar($idValorizacion) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_valorizacion');
        $valorizacion = $this->ValorizacionDistribucion_model->get_one_valorizacion($idValorizacion);
        if (!isset($valorizacion)) {
            show_404();
        }
        $this->ValorizacionDistribucion_model->delete_valorizacion($idValorizacion);
        $this->session->set_flashdata('mensaje', array('success', 'La valorización se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'distribucion/valorizacion');
    }
    
    public function valorizacion_detalle($idValorizacion){
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_valorizacion');
        $valorizacion = $this->ValorizacionDistribucion_model->get_one_valorizacion($idValorizacion);
    }

    // distribucion/valorizacion/detalle/(:num)
    public function valorizacion_distribucion_detalle($idValorizacion) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_valorizacion');
        $valorizacion = $this->ValorizacionDistribucion_model->get_one_valorizacion($idValorizacion);
        if (!isset($valorizacion)) {
            show_404();
        }
        $actividad = $this->Actividad_model->get_one_actividad($valorizacion['VlrActId']);
        $contratista = $this->Contratista_model->get_one_contratista_x_contrato($valorizacion['VlrConId']);
        $valSubs = $this->ValorizacionDistribucion_model->get_valorizacion_orden_subactividad_x_valorizacion($valorizacion['VlrId']);
        foreach ($valSubs as $key => $valSub) {
            $valSubs[$key]['subAct'] = $this->SubActividad_model->get_one_subactividad($valSub['VloSacId']);
        }
        $ordenes = $this->OTDistribucion_model->get_all_orden_x_valorizacion($idValorizacion);
        $this->data['valorizacion'] = $valorizacion;
        $this->data['valSubs'] = $valSubs;
        $this->data['ordenes'] = $ordenes;
        $this->data['actividad'] = $actividad;
        $this->data['contratista'] = $contratista;
        $this->data['view'] = 'distribucion/ValorizacionDistribucion_detalle_view';
        $this->data['breadcrumbs'] = array(array('Valorizaciones', 'distribucion/valorizacion'), array('Valorización ' . $valorizacion['VlrNum'], ''));
        $this->load->view('template/Master', $this->data);
    }

    // distribucion/valorizacion/detalle/(:num)/orden/(:num)
    public function valorizacion_distribucion_orden($idValorizacion, $idOrden) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_valorizacion');
        $valorizacion = $this->ValorizacionDistribucion_model->get_one_valorizacion($idValorizacion);
        if (!isset($valorizacion)) {
            show_404();
        }
        $actividad = $this->Actividad_model->get_one_actividad($valorizacion['VlrActId']);
        $contratista = $this->Contratista_model->get_one_contratista_x_contrato($valorizacion['VlrConId']);
        // Orden de trabajo que se esta detallando
        $orden = $this->OTDistribucion_model->get_one_orden_trabajo($idOrden);
        $orden['metradoEj'] = 0;
        $orden['subtotal'] = 0;
        $desc = $this->OTDistribucion_model->get_descuento_x_idOrden($idOrden);
        if(is_null($desc)){
            $orden['descuento'] = 0;
        } else {
            $orden['descuento'] = $desc;
        }
        // La orden dividida entre las subactividades que tiene
        $ordenSubs = $this->OTDistribucion_model->get_orden_subactividad_x_orden($idOrden);

        /*foreach ($ordenSubs as $key => $ordenSub) {
            if ($ordenSub['SacCod'] == 'capturador') {
                //CON CAPTURADOR DE DATOS
                $tipo = TRUE;
            } elseif ($ordenSub['SacCod'] == 'hoja_lectura') {
                //CON HOJA DE LECTURA
                $tipo = FALSE;
            }
            $lecturas = $this->Lectura_model->get_lecturas_capturador_x_orden_x_tipo($ordenSub['OtsOrtId'], $tipo);
            $lecturasControl = $this->Lectura_model->get_lecturas_control_capturador_x_orden_x_tipo($ordenSub['OtsOrtId'], $tipo);
            $lecturasAtip = $this->Lectura_model->get_lecturas_atipico_capturador_x_orden_x_tipo($ordenSub['OtsOrtId'], $tipo);
            $ordenSubs[$key]['cantLecturas'] = count($lecturas);
            $ordenSubs[$key]['cantLecturasControl'] = count($lecturasControl);
            $ordenSubs[$key]['cantLecturasAtip'] = count($lecturasAtip);

            $orden['metradoEj'] += $ordenSub['OtsMetEj'];
            $orden['subtotal'] += $ordenSub['OtsMonEj'];
        }*/

        foreach ($ordenSubs as $key => $ordenSub){
            if ($ordenSub['SacCod'] == 'distribucion_continua') {
                $tipo = 1;
            } elseif ($ordenSub['SacCod'] == 'distribucion dispersa') {
                $tipo = 2;
            } elseif ($ordenSub['SacCod'] == 'distribucion_con_cedula') {
                $tipo = 3;
            } elseif ($ordenSub['SacCod'] == 'distribucion_sin_cedula') {
                $tipo = 4;
            }
            $distribucionRegular = $this->Distribucion_model->get_distribucion_regular_x_orden_x_tipo($ordenSub['OtsOrtId'], $tipo);
            $distribucionControl = $this->Distribucion_model->get_distribucion_control_x_orden_x_tipo($ordenSub['OtsOrtId'], $tipo);
            $distribucionDispersa = $this->Distribucion_model->get_distribucion_dispersa_x_orden_x_tipo($ordenSub['OtsOrtId'], $tipo);
            $ordenSubs[$key]['cantDistribucionRegular'] = count($distribucionRegular);
            $ordenSubs[$key]['cantDistribucionControl'] = count($distribucionControl);
            $ordenSubs[$key]['cantDistribucionDispersa'] = count($distribucionDispersa);
            
            $orden['metradoEj'] += $ordenSub['OtsMetEj'];
            $orden['subtotal'] += $ordenSub['OtsMonEj'];
        }
        
        $orden['total'] = $orden['subtotal'] - $orden['descuento'];
        $orden['metradoPl'] = $this->Distribucion_model->get_cant_distrib_x_orden($idOrden);
        // Las penalizaciones de la orden
        $penalizaciones = $this->Penalizacion_model->get_all_penalizaciones_x_orden($idOrden);
        $this->data['valorizacion'] = $valorizacion;
        $this->data['ordenSubs'] = $ordenSubs;
        $this->data['orden'] = $orden;
        $this->data['penalizaciones'] = $penalizaciones;
        $this->data['actividad'] = $actividad;
        $this->data['contratista'] = $contratista;
        $this->data['view'] = 'distribucion/ValorizacionDistribucion_orden_view';
        $this->data['breadcrumbs'] = array(array('Valorizaciones', 'distribucion/valorizacion'), array('Valorización ' . $valorizacion['VlrNum'], 'distribucion/valorizacion/detalle/'.$valorizacion['VlrId']), array('Orden Nro. '.$orden['OrtNum'],''));
        $this->load->view('template/Master', $this->data);
    }
}