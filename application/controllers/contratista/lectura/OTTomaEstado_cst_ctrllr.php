<?php
class OTTomaEstado_cst_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('contratista/lectura/OTTomaEstado_cst_model');
        $this->load->model('general/Actividad_model');
        $this->load->model('sistema/Parametro_model');
        $this->load->model('general/Observacion_model');
        $this->load->model('contratista/acceso/Usuario_cst_model');
        $this->load->model('general/Contrato_model');
        $this->load->model('lectura/Lecturista_model');
        $this->load->model('lectura/Lectura_model');
        $this->load->model('lectura/OTTomaEstado_model');
        $this->load->model('general/Contratista_model');
        $this->load->library('utilitario_cls');
        $this->load->helper(array('download', 'file', 'url', 'html', 'form'));
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->load->library('utilitario_cls');
        $this->acceso_cls->isLogin();
        $this->data['menu']['padre'] = 'toma_estado';
        $this->data['menu']['hijo'] = 'ordenes';
        $this->data['padre'] = 'toma_estado';
        $this->data['hijo'] = 'ordenes';
        $this->data['proceso'] = 'Toma de Estado';
        
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->idContratante = $this->data['userdata']['contratante']['CntId'];
        $this->idActividad = $this->Actividad_model->get_id_actividad_x_codigo('toma_estado');
        
    }

    public function orden_listar() 
    {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_orden_lectura');

        $this->data['ordenesTrabajo'] = $this->OTTomaEstado_model->get_all_orden_trabajo_x_contratante($this->idContratante, $this->idActividad);
        $this->data['ordenesTrabajoTemp'] = $this->OTTomaEstado_model->get_all_orden_trabajo_temp($this->idContratante);
   
        $this->data['view'] = 'contratista/lectura/OTsTomaEstado_cst_view';
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', ''));
        $this->load->view('contratista/template/Master_cst_view', $this->data);
    }

    public function orden_ver($idOrdenTrabajo) {
        
        $OrdenTrabajo = $this->OTTomaEstado_cst_model->get_one_orden_trabajo($idOrdenTrabajo);
        if (!isset($OrdenTrabajo)) {
            show_404();
        }
        else{
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_orden_lectura');
        $idParametro = $this->Parametro_model->get_one_detParam_x_codigo('no_rec')['DprId'];
        //SI ESTA EN NO RECIBIDO
        $OrtUsrRs = $this->data['userdata']['UsrId'];
        $this->OTTomaEstado_cst_model->update_usr_rs_orden_trabajo($idOrdenTrabajo, $OrtUsrRs);
        $orden = $this->OTTomaEstado_cst_model->get_one_orden_trabajo_all_datos($idOrdenTrabajo);
        if ($orden['OrtEstId'] == $idParametro) {
            $idParametro = $this->Parametro_model->get_one_detParam_x_codigo('rec')['DprId'];
            $this->OTTomaEstado_cst_model->update_estado_orden_trabajo($idOrdenTrabajo, $idParametro);
        }
        $ordenTrabajoEdit = $this->OTTomaEstado_cst_model->get_one_orden_trabajo($idOrdenTrabajo);
        if (!isset($ordenTrabajoEdit)) {
            show_404();
        }
        $this->data['ordenTrabajoEdit'] = $ordenTrabajoEdit;
        $this->data['OTTomaEstado'] = $orden;
        $idUsrEn = $this->data['OTTomaEstado']['OrtUsrEn'];
        $idUsrRs = $this->data['OTTomaEstado']['OrtUsrRs'];
        $this->data['UsrEn'] = $this->Usuario_cst_model->get_one_usuario_de_contratante($idUsrEn);
        $this->data['UsrRs'] = $this->Usuario_cst_model->get_one_usuario_de_contratista($idUsrRs);
        $idContrato = $this->validar_contrato_actividad($this->idActividad, $this->idContratante);
        $rendimientosPorActividad = $this->Contrato_model->get_rendimientos_x_contrato_x_actividad($idContrato, $this->idActividad);
        $rendimiento = implode(",", max($rendimientosPorActividad));
        $this->data['rendimiento'] = $rendimiento;
         
        //nro de lecturistas activos registrado
        $this->data['totalLecturistas'] = $this->Lecturista_model->get_cant_lecturista_x_contrato($idContrato);
        $subOr = $this->OTTomaEstado_cst_model->get_cantSubords_por_ordenTrabajo_porDia($idOrdenTrabajo);
        $this->data['totalSubordenes'] = array_sum(array_column($subOr, 'cantidad'));
        
        
        $SubOrdenes = $this->OTTomaEstado_cst_model->get_cantLec_por_suborden_por_ordenTrabajo_porDia($idOrdenTrabajo);
        foreach ($SubOrdenes as $nroSuborden => $suborden) {
            $SubOrdenes[$nroSuborden]['nroLecturistas'] = $this->OTTomaEstado_cst_model->get_cantSubords_por_ordenTrbj_porDia($idOrdenTrabajo,$SubOrdenes[$nroSuborden]['SolFchEj']);
            $subordenesSinAsignar = $this->Lectura_model->get_detalle_suministros_sin_asignar_lecturista($idOrdenTrabajo,$SubOrdenes[$nroSuborden]['SolFchEj']);  
            if($subordenesSinAsignar[0]['SolLtaId']!=NULL) { 
                $SubOrdenes[$nroSuborden]['asignadas'] = TRUE ;
            }
            else {$SubOrdenes[$nroSuborden]['asignadas'] = FALSE ;}
            
        }
        $this->data['SubOrdenesDia'] = $SubOrdenes;

        //AGREGAR LA CANTIDAD
        $this->data['cantidad'] = $this->Lectura_model->get_cant_lect_x_orden($idOrdenTrabajo);
        
        $subordenes = $this->Lectura_model->get_detalle_suministros_lecturista($idOrdenTrabajo);
        $subordenesTotales = $this->OTTomaEstado_cst_model->get_cantidad_subordenes_x_orden($idOrdenTrabajo);
        
        if(empty($subordenes)||count($subordenes)<$subordenesTotales)
        {
            $this->session->set_flashdata('mensaje', array('error', 'Faltan asignar Lecturistas a las Sub ordenes de Toma de Estado.'));
            $this->data['Subciclos'] = $this->Lectura_model->get_desc_ciclo_x_orden($idOrdenTrabajo);
            $this->data['cicloOrden'] = $this->OTTomaEstado_cst_model->get_ciclo_orden_trabajo($idOrdenTrabajo);
            $this->data['LecSubCiclos'] = $this->Lectura_model->get_lecturistas_x_ciclo_x_orden($idOrdenTrabajo);
        }
        else
        {    
            $this->data['subordenes'] = $subordenes;
            $this->data['LecSubCiclos'] = $this->Lectura_model->get_lecturistas_x_ciclo_x_orden($idOrdenTrabajo);
            $this->data['cicloOrden'] = $this->OTTomaEstado_cst_model->get_ciclo_orden_trabajo($idOrdenTrabajo);
            $this->data['lecturistas'] = $this->Usuario_cst_model->get_all_usuario_lecturista();
            $this->data['Subciclos'] = $this->Lectura_model->get_desc_ciclo_x_orden($idOrdenTrabajo);
        }
        $this->data['view'] = 'contratista/lectura/OTTomaEstado_ver_ciclos_cst_view';
        $this->data['accion'] = 'ver';
        $this->data['proceso'] = 'Toma de Estado';
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'cst/toma_estado/ordenes'), array('Ver Orden de Trabajo', ''));
        $this->data['menu']['hijo'] = 'ordenes';
        $this->load->view('contratista/template/Master_cst_view', $this->data);
        }
    }
    
    
    public function orden_ver_detalle_ciclo($idOrdenTrabajo,$fechaEj) {
        
        $subordenesSinAsignar = $this->Lectura_model->get_detalle_suministros_sin_asignar_lecturista($idOrdenTrabajo,$fechaEj);
        $this->data['subordenesSinAsignar'] = $subordenesSinAsignar;
        $this->data['error'] = FALSE;
        
        $idContrato = $this->validar_contrato_actividad($this->idActividad, $this->idContratante);
        $lecturistas = $this->Lecturista_model->get_lecturista_disponibles($this->idContratante, $idContrato, $fechaEj);
        
        if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['asignar_lecturistas'])) {

            $aleatorio = $this->input->post('asignacion') == 0;
            $subordenes = $subordenesSinAsignar;

            $isOk = TRUE;
            if (!$aleatorio) {
                //CUANDO ES MANUAL
                foreach ($subordenes as $nroSuborden => $suborden) {
                    if (isset($_POST['lecturista_' . $nroSuborden])) {
                        $idLecturista = $this->input->post('lecturista_' . $nroSuborden);
                        if ($idLecturista != 0) {
                            //SELECCIONO UN LECTURISTA DEL COMBO
                            if (isset($lecturistas[$idLecturista])) {
                                //AUN NO ESTA ASIGNADO
                                $subordenes[$nroSuborden]['idLecturista'] = $idLecturista;
                                unset($lecturistas[$idLecturista]);
                            } else {
                                //YA ESTA ASIGNADO Y SE REPITE
                                $lecturistas[$idLecturista] = 0;
                                $isOk = FALSE;
                            }
                        } else {
                            //NO SELECCIONO A UN LECTURISTA
                            $lecturistas[$idLecturista] = 0;
                            $isOk = FALSE;
                        }
                    }
                }
                if ($isOk) {
                    $exito = 0;
                    foreach ($subordenes as $nroSuborden => $suborden) {
                        $resultado = $this->OTTomaEstado_cst_model->update_one_subordenlectura_id($suborden["idLecturista"], $suborden["SolId"]);
                        if ($resultado == TRUE) {
                            $exito++;
                        }
                    }
                    if ($exito == count($subordenes))
                    {$this->session->set_flashdata('mensaje', array('success', 'Sin errores en Asignación, Subordenes almacenadas con sus respectivos lecturistas ( Asignación Manual)'));
                    $this->data['cerrar'] = TRUE;
                    }
                    else
                        $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al almacenar la Asignación Manual'));
                }
                else {
                    
                    $errores = "";
                    foreach ($subordenes as $suborden) {
                        if (!isset($suborden['idLecturista'])) {
                            $errores .= $suborden['SolCod'] . ", ";
                        }
                    }
                    $this->data['error'] = TRUE;
                    $this->session->set_flashdata('mensaje', array('error', 'Error en Asignacion de Subordenes : ' . $errores));
                }
            } else {
                
                foreach ($subordenes as $nroSuborden => $suborden) {
                    if (isset($_POST['lecturista_' . $nroSuborden])) {
                        $idLecturista = $this->input->post('lecturista_' . $nroSuborden);
                        $subordenes[$nroSuborden]['idLecturista'] = $idLecturista;
                    }
                }
                $exito = 0;
                foreach ($subordenes as $nroSuborden => $suborden) {
                    $resultado = $this->OTTomaEstado_cst_model->update_one_subordenlectura_id($subordenes[$nroSuborden]['idLecturista'], $suborden["SolId"]);
                    if ($resultado == TRUE) {
                        $exito++;
                    }
                }
                if ($exito == count($subordenes))
                { $this->session->set_flashdata('mensaje', array('success', 'Sin errores en Asignación, Subordenes almacenadas con sus respectivos lecturistas ( Asignación Aleatoria).'));
                $this->data['cerrar'] = TRUE;
                }
                else
                    $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al almacenar la Asignación Aleatoria'));
            }
        }
        $this->data['lecturistas'] = $lecturistas;
        $this->data['cantidad'] = $this->OTTomaEstado_cst_model->get_cantLec_por_suborden_por_ordenTrbj_porDia($idOrdenTrabajo,$fechaEj);
        $this->data['nroLecturistas'] = $this->OTTomaEstado_cst_model->get_cantSubords_por_ordenTrbj_porDia($idOrdenTrabajo,$fechaEj);
        
        $this->data['view'] = 'contratista/lectura/OTTomaEstado_ciclos_detalle_cst_view';
        $this->load->view('template/Reporte', $this->data);
    }
    
    public function get_detalle_x_suborden_json(){
        $idSubOrden = $this->input->post('idSubOrden'); 
        $detalleSuborden = $this->Lectura_model->get_lecturas_x_idsuborden($idSubOrden);
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($detalleSuborden));
    }
    
    public function validar_contrato_actividad($idActividad, $idContratante) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $contratosVigentes = $this->Contrato_model->get_contratos_x_contratante($idContratante);
        foreach ($contratosVigentes as $contratoVigente) {
            $actividadesContratadas = $this->Contrato_model->get_actividades_x_contrato_id($contratoVigente['ConId']);
            $idActividadesContratadas = array_column($actividadesContratadas, 'ActId');
            /*      VALIDAR CONTRATO VIGENTE        */
            if (in_array($idActividad, $idActividadesContratadas) && $contratoVigente['ConVig'] == true) {
                $idContrato = $contratoVigente['ConId'];
                return $idContrato;
            }
        }
        return FALSE;
    }

    
    /*   IMPRESION DE SUB ORDENES DE TRABAJO (CARGAS)   */

    public function orden_imprimir_suborden($idOrdenTrabajo, $idSubOrden , $pagina = 1) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('imprimir_suborden');

        $OTTomaEstado = $this->OTTomaEstado_cst_model->get_one_orden_trabajo_all_datos($idOrdenTrabajo);
        if (empty($OTTomaEstado)) {
            show_404();
        }
        $filasHoja = $this->utilitario_cls->getLecturasXhoja();
        $filaInicio = ($pagina - 1) * $filasHoja;
        
        $lecturas = $this->Lectura_model->get_lecturas_x_suborden_x_pagina($idOrdenTrabajo, $idSubOrden , $filasHoja, $filaInicio);
        
        if (empty($lecturas)) {
            $suborden = $this->Lectura_model->get_one_suborden($idSubOrden);
            $idLecturista = $suborden['SolLtaId'];
            $this->session->set_flashdata('mensaje', array('error', 'No existen Lecturas para Imprimir, solo para ingresar con Dispositivo Movil'));
            
            $this->data['lecturista'] = $this->Usuario_cst_model->get_one_usuario_lecturista($idLecturista);
            $this->data['OTTomaEstado'] = $this->OTTomaEstado_cst_model->get_one_orden_trabajo_all_datos($idOrdenTrabajo);

            $this->data['accion'] = 'imprimir';
            $this->data['view'] = 'contratista/lectura/OTTomaEstado_impdig_error_cst_view';
            $this->data['proceso'] = 'Toma de Estado';
            $this->load->view('template/Reporte', $this->data);
        }
        else{
        $suborden = $this->Lectura_model->get_one_suborden($idSubOrden);
        $idLecturista = $suborden['SolLtaId'];
        $subciclos = $this->Lectura_model->get_desc_ciclos_x_orden_lecturista($idOrdenTrabajo, $idLecturista);
        $lectTotal = $this->Lectura_model->get_cant_lectura_total_x_suborden_x_ciclo_x_orden($idOrdenTrabajo ,  $idSubOrden );
        
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'cst/toma_estado/orden/' . $idOrdenTrabajo . '/imprimir_suborden/' .  $idSubOrden ;
        $config['total_rows'] = $lectTotal;
        $config['per_page'] = $filasHoja;
        $config['next_link'] = 'Siguiente';
        $config['prev_link'] = 'Anterior';
        $config['full_tag_open'] = '<div class="box-footer clearfix"><ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul></div>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li><a><b>';
        $config['cur_tag_close'] = '</b></a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = 'Primera';
        $config['last_link'] = 'Última';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['uri_segment'] = 7;
        $config['use_page_numbers'] = TRUE;
        $this->pagination->initialize($config);
        $this->data['results'] = $lecturas;
        $nroPaginas = ceil($lectTotal / $filasHoja);
        $this->data['nroPaginas'] = $nroPaginas;
        $this->data['paginaActual'] = $pagina;
        $this->data['nroSuministros'] = $lectTotal;
        
        $this->data['localidades'] = implode(", ", array_column($subciclos, "GprDes"));
        
        $this->data['lecturista'] = $this->Usuario_cst_model->get_one_usuario_lecturista($idLecturista);
        $this->data['OTTomaEstado'] = $this->OTTomaEstado_cst_model->get_one_orden_trabajo_all_datos($idOrdenTrabajo);

        $this->data['accion'] = 'imprimir';
        $this->data['view'] = 'contratista/lectura/OTTomaEstado_imprimir_cst_view';
        $this->data['proceso'] = 'Toma de Estado';
        $this->load->view('template/Reporte', $this->data);
        }
    }

    public function orden_digitar_suborden($idOrdenTrabajo, $idSubOrden , $pagina = 1) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('digitar_suborden');
        $OTTomaEstado = $this->OTTomaEstado_cst_model->get_one_orden_trabajo_all_datos($idOrdenTrabajo);
        if (empty($OTTomaEstado)) {
            show_404();
        }
        $filasHoja = $this->utilitario_cls->getLecturasXhoja();
        $filaInicio = ($pagina - 1) * $filasHoja;
        $lecturas = $this->Lectura_model->get_lecturas_x_suborden_x_pagina($idOrdenTrabajo, $idSubOrden , $filasHoja, $filaInicio);
        if (empty($lecturas)) {
            $suborden = $this->Lectura_model->get_one_suborden($idSubOrden);
            $idLecturista = $suborden['SolLtaId'];
            $this->session->set_flashdata('mensaje', array('error', 'No existen Lecturas para Digitar, solo para ingresar con Dispositivo Movil'));
            $this->data['lecturista'] = $this->Usuario_cst_model->get_one_usuario_lecturista($idLecturista);
            $this->data['OTTomaEstado'] = $this->OTTomaEstado_cst_model->get_one_orden_trabajo_all_datos($idOrdenTrabajo);

            $this->data['accion'] = 'imprimir';
            $this->data['view'] = 'contratista/lectura/OTTomaEstado_impdig_error_cst_view';
            $this->data['proceso'] = 'Toma de Estado';
            $this->load->view('template/Reporte', $this->data);
        }
        else
        {
            $suborden = $this->Lectura_model->get_one_suborden($idSubOrden);
            $idLecturista = $suborden['SolLtaId'];
            
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $this->orden_digitar_suborden_guardar($idOrdenTrabajo, $idLecturista, $lecturas, $this->data['userdata']['UsrId']);
                $lecturas = $this->Lectura_model->get_lecturas_x_suborden_x_pagina($idOrdenTrabajo, $idSubOrden , $filasHoja, $filaInicio);
            }

            $subciclos = $this->Lectura_model->get_desc_ciclos_x_orden_lecturista($idOrdenTrabajo, $idLecturista);
            $lectTotal = $this->Lectura_model->get_cant_lectura_total_x_suborden_x_ciclo_x_orden($idOrdenTrabajo ,  $idSubOrden );

            $this->load->library('pagination');
            $config['base_url'] = base_url() . 'cst/toma_estado/orden/' . $idOrdenTrabajo . '/digitar_suborden/' . $idSubOrden;
            $config['total_rows'] = $lectTotal;
            $config['per_page'] = $filasHoja;
            $config['next_link'] = 'Siguiente';
            $config['prev_link'] = 'Anterior';
            $config['full_tag_open'] = '<div class="box-footer clearfix"><ul class="pagination pagination-sm no-margin pull-right">';
            $config['full_tag_close'] = '</ul></div>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li><a><b>';
            $config['cur_tag_close'] = '</b></a></li>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['first_link'] = 'Primera';
            $config['last_link'] = 'Última';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            $config['uri_segment'] = 7;
            $config['use_page_numbers'] = TRUE;
            $this->pagination->initialize($config);
            $this->data['results'] = $lecturas;

            $nroPaginas = ceil($lectTotal / $filasHoja);
            $this->data['nroPaginas'] = $nroPaginas;
            $this->data['paginaActual'] = $pagina;
            $this->data['nroSuministros'] = $lectTotal;
            $this->data['ciclo'] = implode(", ", array_column($subciclos, "GprDes"));
            $this->data['lecturista'] = $this->Usuario_cst_model->get_one_usuario_lecturista($idLecturista);
            $this->data['OTTomaEstado'] = $this->OTTomaEstado_cst_model->get_one_orden_trabajo_all_datos($idOrdenTrabajo);
            $this->data['Suborden'] = $suborden;
            $this->data['GrupoObservaciones'] = $this->Observacion_model->get_all_grupoObservacion_x_actividad($this->idActividad, $this->data['userdata']['contratante']['CntId']);
            $this->data['accion'] = 'digitar';
            $this->data['view'] = 'contratista/lectura/OTTomaEstado_digitar_cst_view';
            $this->data['proceso'] = 'Toma de Estado';
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'cst/toma_estado/ordenes'), array('Digitar Sub Orden de Trabajo (Carga)', ''));
            $this->data['menu']['hijo'] = 'ordenes';
            $this->load->view('template/Reporte', $this->data);
        }
    }

    // $this -> orden_digitar_suborden
    public function orden_digitar_suborden_guardar($idOrdenTrabajo, $idLecturista, $lecturas, $idDigitador) {
        $lecturasInsert = array();
        foreach ($lecturas as $lectura) {
            $LecVal = $this->input->post('LecVal_' . $lectura['LecId']);
            $LecObs = $this->input->post('LecObs_' . $lectura['LecId']);

            if (!is_null($LecVal)) {
                $lecturasInsert[$lectura['LecId']]['data'] = $lectura;
                $lecturasInsert[$lectura['LecId']]['valor'] = $LecVal;
                if(!is_null($LecObs)){
                    $lecturasInsert[$lectura['LecId']]['observaciones'] = $LecObs;
                }else{
                    $lecturasInsert[$lectura['LecId']]['observaciones'] = array();
                }
            }
        }
        $this->OTTomaEstado_cst_model->update_lectura_digitacion($lecturasInsert, $idOrdenTrabajo, $idLecturista, $idDigitador);
    }

}
