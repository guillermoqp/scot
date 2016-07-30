<?php

ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');

class OrdenTrabajo_ctrllr extends CI_Controller {

    var $esnuevo = true;

    public function __construct() {
        parent::__construct();
        $this->load->model('general/OrdenTrabajo_model');
        $this->load->model('general/Actividad_model');
        $this->load->model('general/Penalizacion_model');
        $this->load->model('general/Penalidad_model');
        $this->load->model('lectura/CronogramaLectura_model');
        $this->load->model('general/Contrato_model');
        $this->load->model('sistema/Parametro_model');
        $this->load->model('acceso/Usuario_model');
        $this->load->model('catastro/Catastro_model');
        $this->load->model('lectura/Lecturista_model');
        $this->load->model('lectura/Lectura_model');

        $this->load->model('SubActividad_model');
        $this->load->helper(array('download', 'file', 'url', 'html', 'form'));

        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->load->library('utilitario_cls');
        $this->acceso_cls->isLogin();
        //$this->data['menu']['padre'] = 'toma_estado';
        //$this->data['menu']['hijo'] = 'ordenes';
        //$this->data['proceso'] = 'Toma de Estado';
    }
    
    // <editor-fold defaultstate="collapsed" desc="NOTIFICACIONES DEL MASTER">
    public function obtener_ordenes_no_rec_x_usuario_json() {
        $idUsuario = $this->input->post('idUsuario');
        $parametro = $this->Parametro_model->get_one_detParam_x_codigo('no_rec');
        $OrtEstId = $parametro['DprId'];
        $Ordenes = $this->OrdenTrabajo_model->get_all_orden_trabajo_x_usuario($idUsuario, $OrtEstId);
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($Ordenes));
    }

    public function obtener_ordenes_rec_x_usuario_json() {
        $idUsuario = $this->input->post('idUsuario');
        $parametro = $this->Parametro_model->get_one_detParam_x_codigo('rec');
        $OrtEstId = $parametro['DprId'];
        $Ordenes = $this->OrdenTrabajo_model->get_all_orden_trabajo_x_usuario($idUsuario, $OrtEstId);
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($Ordenes));
    }

    public function obtener_ordenes_cer_x_usuario_json() {
        $idUsuario = $this->input->post('idUsuario');
        $parametro = $this->Parametro_model->get_one_detParam_x_codigo('cer');
        $OrtEstId = $parametro['DprId'];
        $Ordenes = $this->OrdenTrabajo_model->get_all_orden_trabajo_x_usuario($idUsuario, $OrtEstId);
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($Ordenes));
    }

    public function obtener_ordenes_en_ejec_x_usuario_json() {
        $idUsuario = $this->input->post('idUsuario');
        $parametro = $this->Parametro_model->get_one_detParam_x_codigo('eje');
        $OrtEstId = $parametro['DprId'];
        $Ordenes = $this->OrdenTrabajo_model->get_all_orden_trabajo_ejec($idUsuario, $OrtEstId);
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($Ordenes));
    }

// </editor-fold>

    public function orden_listar($Cod,$PerVal) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_orden');
        $actividades = $this->Actividad_model->get_all_actividad_permiso();
        foreach ($actividades as $actividad):
            if ($actividad['ActCod'] == $Cod) {
                $idActividad = $actividad['ActId'];
                $padre = $actividad['ActCod'];
                $des = $actividad['ActDes'];
                foreach ($actividad['permisos'] as $permiso){
                    if( $permiso['GpeDes'] == 'Ordenes de Trabajo'){
                        $hijo = $permiso['GpeVal'];
                    }
                }
            }
        endforeach;
        $idGpeVal = $this->Actividad_model->get_id_x_GpeVal($padre);
        if ($idGpeVal===$PerVal)
        {
            $this->data['ordenesTrabajo'] = $this->OrdenTrabajo_model->get_all_orden_trabajo_x_contratante($this->data['userdata']['contratante']['CntId'], $idActividad);
            $this->data['ordenesTrabajoTemp'] = $this->OrdenTrabajo_model->get_all_orden_trabajo_temp($this->data['userdata']['contratante']['CntId']);
            $this->data['subactividades'] = $this->OrdenTrabajo_model->get_subactividad_x_orden($idActividad);
            $this->data['view'] = 'general/OrdenesTrabajo_view';
            $this->data['menu']['padre'] = $padre;
            $this->data['menu']['hijo'] = $hijo;
            $this->data['padre'] = $padre;
            $this->data['hijo'] = $hijo;
            $this->data['proceso'] = $des;
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', ''));
            $this->load->view('template/Master', $this->data);
        }
        else {
            show_404();
        }
    }

    public function validar_contrato_actividad($idActividad, $idContratante) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_orden');

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

    public function orden_ver($Cod,$PerVal,$idOrdenTrabajo) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_orden');
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $actividades = $this->Actividad_model->get_all_actividad_permiso();
        foreach ($actividades as $actividad):
            if ($actividad['ActCod'] == $Cod) {
                $idActividad = $actividad['ActId'];
                $padre = $actividad['ActCod'];
                $des = $actividad['ActDes'];
                foreach ($actividad['permisos'] as $permiso):
                    if( $permiso['GpeDes'] == 'Ordenes de Trabajo'){
                        $hijo = $permiso['GpeVal'];
                    }
                endforeach;
            }
        endforeach;
        $idGpeVal = $this->Actividad_model->get_id_x_GpeVal($padre);
        if ($idGpeVal===$PerVal)
        {
            $archivosEnv = $this->OrdenTrabajo_model->get_archivos_enviados_x_orden_trabajo($idOrdenTrabajo);
            $this->data['OrdenTrabajo'] = $this->OrdenTrabajo_model->get_one_orden_trabajo_all_datos($idOrdenTrabajo);
            $this->data['subactividades'] = $this->OrdenTrabajo_model->get_subactividad_x_orden($idActividad);
            $this->data['archivosEnv'] = $archivosEnv;
            $idUsrEn = $this->data['OrdenTrabajo']['OrtUsrEn'];
            $idUsrRs = $this->data['OrdenTrabajo']['OrtUsrRs'];
            $this->data['UsrEn'] = $this->Usuario_model->get_one_usuario_de_contratante($idUsrEn);
            $this->data['UsrRs'] = $this->Usuario_model->get_one_usuario_de_contratista($idUsrRs);
            $parametro = $this->Parametro_model->get_one_detParam($this->data['OrdenTrabajo']['OrtEstId']);
            $this->data['EstadoOrden'] = $parametro;
            $this->data['accion'] = 'ver';
            $this->data['view'] = 'general/OrdenTrabajo_ver_view';
            $this->data['menu']['padre'] = $padre;
            $this->data['menu']['hijo'] = $hijo;
            $this->data['proceso'] = $des;
            $this->data['padre'] = $padre;
            $this->data['hijo'] = $hijo;
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Ver Orden de Trabajo', ''));
            $this->load->view('template/Master', $this->data);
        }
        else {
            show_404();
        }
    }

    public function orden_nuevo($Cod,$PerVal) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_orden');

        /*  VALIDAR QUE PUEDA GENERAR ORDENES DE TRABAJO SEGUN CONTRATO Y ACTIVIDAD CONTRATADA   */
        $actividades = $this->Actividad_model->get_all_actividad_permiso();
        foreach ($actividades as $actividad):
            if ($actividad['ActCod'] == $Cod) {
                $idActividad = $actividad['ActId'];
                $padre = $actividad['ActCod'];
                $des = $actividad['ActDes'];
                foreach ($actividad['permisos'] as $permiso):
                    if( $permiso['GpeDes'] == 'Ordenes de Trabajo'){
                        $hijo = $permiso['GpeVal'];
                    }
                endforeach;
            }
        endforeach;
        $idGpeVal = $this->Actividad_model->get_id_x_GpeVal($padre);
        if ($idGpeVal===$PerVal)
        {
            $idContratante = $this->data['userdata']['contratante']['CntId'];
            $idContrato = $this->validar_contrato_actividad($idActividad, $idContratante);
            if ($idContrato != FALSE) {
                $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                $this->data['subactividades'] = $this->SubActividad_model->get_subactividad_x_actividad($idActividad);
                $this->data['accion'] = 'nuevo';
                $this->data['menu']['padre'] = $padre;
                $this->data['menu']['hijo'] = $hijo;
                $this->data['proceso'] = $des;
                $this->data['padre'] = $padre;
                $this->data['hijo'] = $hijo;
                $this->data['view'] = 'general/OrdenTrabajo_view';
                $contrato = $this->Contrato_model->get_one_contrato($idContrato);
                $entregables = $this->Usuario_model->get_recepcion_orden($contrato['ConCstId'], $this->data['userdata']['contratante']['CntId']);
                $entregables = implode(', ', $entregables);
                $Mes = $this->utilitario_cls->nombreMes(date('m') + 1);
                $fchEj = date('d-m-Y', strtotime('+1 day', time()));
                $this->data['entregables'] = $entregables;
                $this->data['anios'] = $this->CronogramaLectura_model->get_anios($idContratante);
                $this->data['Mes'] = $Mes;
                $this->data['fchEj'] = $fchEj;
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Registrar Orden de Trabajo', ''));
                $this->load->view('template/Master', $this->data);
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'No tiene esta Actividad Contratada'));
                $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                redirect(base_url().$padre.'/'.$hijo);
            }
        }
        else {
            show_404();
        }
    }

    public function orden_nuevo_guardar($Cod,$PerVal) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_orden');
        $actividades = $this->Actividad_model->get_all_actividad_permiso();
        foreach ($actividades as $actividad):
            if ($actividad['ActCod'] == $Cod) {
                $idActividad = $actividad['ActId'];
                $padre = $actividad['ActCod'];
                $des = $actividad['ActDes'];
                foreach ($actividad['permisos'] as $permiso):
                    if( $permiso['GpeDes'] == 'Ordenes de Trabajo'){
                        $hijo = $permiso['GpeVal'];
                    }
                endforeach;
            }
        endforeach;
        $idGpeVal = $this->Actividad_model->get_id_x_GpeVal($padre);
        if ($idGpeVal===$PerVal)
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('OrtNum', 'Num. Orden de Trabajo', 'required');
            $this->form_validation->set_rules('OrtEntr', 'Entregado A', 'required');
            $this->form_validation->set_rules('OrtDes', 'Descripcion del Trabajo', 'required');
            $this->form_validation->set_rules('OrtMes', 'Mes de Facturacion', 'required');
            $this->form_validation->set_rules('OrtAnio', 'Año', 'required');
            $this->form_validation->set_rules('OrtFchEj', 'Fecha de Ejecucion de Orden de Trabajo', 'required');
            $this->form_validation->set_rules('OrtFchEm', 'Fecha Maxima de Recepcion de Orden de Trabajo Ejecutada', 'required');
            $this->form_validation->set_rules('OrtObs', 'Observaciones', 'required');
            if ($this->form_validation->run() == TRUE) {
                $subactividades = $this->SubActividad_model->get_subactividad_x_actividad($idActividad);
                foreach ($subactividades as $subactividad) {
                    $metradoPl = $this->input->post('val_' . $subactividad['SacId']);
                    if ($metradoPl != "") {
                        $ordenMetradoPl[$subactividad['SacId']]['idSubActividad'] = $subactividad['SacId'];
                        $ordenMetradoPl[$subactividad['SacId']]['metradoPl'] = $metradoPl;
                    }
                }
                $OrtNum = $this->input->post('OrtNum');
                $OrtEntr = $this->input->post('OrtEntr');
                $OrtDes = $this->input->post('OrtDes');
                $OrtMes = $this->input->post('OrtMes');
                $OrtAnio = $this->input->post('OrtAnio');
                $OrtObs = $this->input->post('OrtObs');
                $OrtFchEj = $this->input->post('OrtFchEj');
                $OrtFchEm = $this->input->post('OrtFchEm');
                $OrtDes = $OrtEntr . "<br>" . $OrtDes . "<br>" . $OrtAnio . "<br>" . $OrtMes ;
                $dir_path = FCPATH . 'assets/archivos/toma_estado';
                if (!file_exists($dir_path)) {
                    mkdir($dir_path, DIR_WRITE_MODE, true);
                }
                $config['upload_path'] = $dir_path;
                $config['allowed_types'] = '*';
                $config['max_filename'] = '255';
                $config['encrypt_name'] = TRUE;
                $config['remove_spaces'] = TRUE;
                $config['max_size'] = '0';
                $i = 0;
                $files = array();
                $is_file_error = FALSE;
                if ($_FILES['upload_file1']['size'] <= 0) {
                    $this->session->set_flashdata('mensaje', array('error', 'Selecciona por lo menos un Archivo.'));
                    $this->data['accion'] = 'nuevo';
                    $this->data['view'] = 'general/OrdenTrabajo_view';
                    $this->data['menu']['padre'] = $padre;
                    $this->data['menu']['hijo'] = $hijo;
                    $this->data['proceso'] = $des;
                    $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Registrar Orden de Trabajo', ''));
                    $this->load->view('template/Master', $this->data);
                    redirect(base_url() . $padre.'/'.$hijo.'/nuevo');
                } else {
                    foreach ($_FILES as $key => $value) {
                        if (!empty($value['name'])) {
                            $this->load->library('upload', $config);
                            if (!$this->upload->do_upload($key)) {
                                $this->upload->display_errors();
                                $is_file_error = TRUE;
                            } else {
                                $files[$i] = $this->upload->data();
                                ++$i;
                            }
                        }
                    }
                }
                /* Se han producido errores, tenemos que eliminar los archivos subidos */
                if ($is_file_error && $files) {
                    for ($i = 0; $i < count($files); $i++) {
                        $file = $dir_path . $files[$i]['file_name'];
                        if (file_exists($file)) {
                            unlink($file);
                        }
                    }
                }
                /* Aqui se almacenan los archivos subidos en la Orden de Trabajo    */
                $archivos = $files;
                $idContratante = $this->data['userdata']['contratante']['CntId'];
                $idContrato = $this->validar_contrato_actividad($idActividad, $idContratante);
                $parametro = $this->Parametro_model->get_one_detParam_x_codigo('no_rec');
                $OrtEstId = $parametro['DprId']; //No Recibido - tabla : Detalle_parametro
                $OrtConId = $idContrato;
                $OrtActId = $idActividad;
                $OrtCntId = $idContratante;
                $OrtUsrEn = $this->data['userdata']['UsrId'];
                if (!$is_file_error && $files) {
                    $resp = $this->OrdenTrabajo_model->insert_orden_trabajo($OrtNum, $OrtDes, $OrtObs, $OrtFchEj, $OrtFchEm, $OrtConId, $OrtEstId, $OrtActId, $OrtUsrEn, $OrtCntId, $archivos, $ordenMetradoPl);
                    if ($resp === TRUE) {
                        $this->session->set_flashdata('mensaje', array('success', 'La Orden de Trabajo ha sido generada Satisfactoriamente.'));
                        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                        $this->data['accion'] = 'nuevo';
                        $this->data['view'] = 'general/OrdenTrabajo_view';
                        $this->data['menu']['padre'] = $padre;
                        $this->data['menu']['hijo'] = $hijo;
                        $this->data['proceso'] = $des;
                        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', ''));
                        $this->load->view('template/Master', $this->data);
                        redirect(base_url() . $padre.'/'.$hijo);
                    } else {
                        for ($i = 0; $i < count($files); $i++) {
                            $file = $dir_path . $files[$i]['file_name'];
                            if (file_exists($file)) {
                                unlink($file);
                            }
                        }
                        $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al momento de guardar.'));
                        redirect(base_url() . $padre.'/'.$hijo.'/nuevo');
                    }
                } else {
                    $this->session->set_flashdata('mensaje', array('error', 'El/los Archivos Ingresados no son DBF.'));
                    $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                    $this->data['accion'] = 'nuevo';
                    $this->data['view'] = 'general/OrdenTrabajo_view';
                    $this->data['menu']['padre'] = $padre;
                    $this->data['menu']['hijo'] = $hijo;
                    $this->data['proceso'] = $des;
                    $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Registrar Orden de Trabajo', ''));
                    $this->load->view('template/Master', $this->data);
                    redirect(base_url() . $padre.'/'.$hijo.'/nuevo');
                }
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Complete los campos'));
                $this->data['accion'] = 'nuevo';
                $this->data['view'] = 'general/OrdenTrabajo_view';
                $this->data['menu']['padre'] = $padre;
                $this->data['menu']['hijo'] = $hijo;
                $this->data['proceso'] = $des;
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Registrar Orden de Trabajo', ''));
                $this->load->view('template/Master', $this->data);
                redirect(base_url() . $padre.'/'.$hijo.'/nuevo');
            }
        }
        else {
            show_404();
        }
    }

    public function orden_editar($Cod,$PerVal,$idOrdenTrabajo) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_orden');
        $ordenTrabajoEdit = $this->OrdenTrabajo_model->get_one_orden_trabajo($idOrdenTrabajo);
        if (!isset($ordenTrabajoEdit)) {
            show_404();
        }
        $actividades = $this->Actividad_model->get_all_actividad_permiso();
        foreach ($actividades as $actividad):
            if ($actividad['ActCod'] == $Cod) {
                $idActividad = $actividad['ActId'];
                $padre = $actividad['ActCod'];
                $des = $actividad['ActDes'];
                foreach ($actividad['permisos'] as $permiso):
                    if( $permiso['GpeDes'] == 'Ordenes de Trabajo'){
                        $hijo = $permiso['GpeVal'];
                    }
                endforeach;
            }
        endforeach;
        $idGpeVal = $this->Actividad_model->get_id_x_GpeVal($padre);
        if ($idGpeVal===$PerVal)
        {
            $parametro = $this->Parametro_model->get_one_detParam_x_codigo('no_rec');
            if ($ordenTrabajoEdit['OrtEstId'] != $parametro['DprId']) {
                $this->session->set_flashdata('mensaje', array('error', 'No se Puede Editar la Orden de Trabajo porque ya ha sido recibida.'));
                redirect(base_url() . $padre.'/'.$hijo);
            } else {
                $archivosEnv = $this->OrdenTrabajo_model->get_archivos_enviados_x_orden_trabajo($idOrdenTrabajo);
                $this->data['ordenTrabajoEdit'] = $ordenTrabajoEdit;
                $this->data['archivosEnv'] = $archivosEnv;
                $this->data['detallesOrden'] =$this->OrdenTrabajo_model->get_detalle_orden_trabajo($idOrdenTrabajo);
                $this->data['accion'] = 'editar';
                $this->data['view'] = 'general/OrdenTrabajo_view';
                $this->data['menu']['padre'] = $padre;
                $this->data['menu']['hijo'] = $hijo;
                $this->data['padre'] = $padre;
                $this->data['hijo'] = $hijo;
                $this->data['proceso'] = $des;
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Editar Orden de Trabajo', ''));
                $this->load->view('template/Master', $this->data);
            }
        }
        else {
            show_404();
        }
    }

    public function orden_archivo_eliminar($Cod, $PerVal, $idOrdenTrabajo, $idArchivo) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_orden');
        if (!isset($idOrdenTrabajo) && !isset($idArchivo)) {
            show_404();
        }
        $actividades = $this->Actividad_model->get_all_actividad_permiso();
        foreach ($actividades as $actividad):
            if ($actividad['ActCod'] == $Cod) {
                //$idActividad = $actividad['ActId'];
                $padre = $actividad['ActCod'];
                $des = $actividad['ActDes'];
                foreach ($actividad['permisos'] as $permiso):
                    if( $permiso['GpeDes'] == 'Ordenes de Trabajo'){
                        $hijo = $permiso['GpeVal'];
                    }
                endforeach;
            }
        endforeach;
        $idGpeVal = $this->Actividad_model->get_id_x_GpeVal($padre);
        if ($idGpeVal===$PerVal)
        {
            $resp = $this->OrdenTrabajo_model->delete_archivo_orden($idOrdenTrabajo, $idArchivo);
            if ($resp === TRUE) {
                $this->session->set_flashdata('mensaje', array('success', 'El Archivo se ha eliminado de La Orden de Trabajo.'));
                $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                $this->data['menu']['padre'] = $padre;
                $this->data['menu']['hijo'] = $hijo;
                $this->data['proceso'] = $des;
                redirect(base_url() . $padre. '/'. $hijo . '/editar/' . $idOrdenTrabajo);
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al momento de eliminar el archivo de la Orden de Trabajo.'));
                $this->data['menu']['padre'] = $padre;
                $this->data['menu']['hijo'] = $hijo;
                $this->data['proceso'] = $des;
                redirect(base_url() . $padre. '/'. $hijo. '/editar/' . $idOrdenTrabajo);
            }
        }
        else {
            show_404();
        }
    }

    public function orden_editar_guardar($Cod,$PerVal,$idOrdenTrabajo) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_orden');
        $actividades = $this->Actividad_model->get_all_actividad_permiso();
        foreach ($actividades as $actividad):
            if ($actividad['ActCod'] == $Cod) {
                $idActividad = $actividad['ActId'];
                $padre = $actividad['ActCod'];
                $des = $actividad['ActDes'];
                foreach ($actividad['permisos'] as $permiso):
                    if( $permiso['GpeDes'] == 'Ordenes de Trabajo'){
                        $hijo = $permiso['GpeVal'];
                    }
                endforeach;
            }
        endforeach;
        $idGpeVal = $this->Actividad_model->get_id_x_GpeVal($padre);
        if ($idGpeVal===$PerVal)
        {
            $this->load->library('form_validation');
            //$this->form_validation->set_rules('OrtNum', 'Num. Orden de Trabajo', 'required');
            $this->form_validation->set_rules('OrtFchEm2', 'Fecha Envio Max.', 'required');
            if ($this->form_validation->run() == TRUE) {
                $orden = $this->OrdenTrabajo_model->get_one_orden_trabajo($idOrdenTrabajo);
                if (isset($orden)) {
                    $campoDes = $orden['OrtDes'];
                    $porciones = explode("<br>", $campoDes);
                    $mes = $porciones[2];
                }
                $OrtNum = $this->input->post('OrtNum');
                $OrtEntr = $this->input->post('OrtEntr');
                $OrtDes = $this->input->post('OrtDes');
                $OrtMes = $mes;
                $OrtAnio = $this->input->post('OrtAnio');
                $OrtObs = $this->input->post('OrtObs');
                //$OrtFchEj = $this->input->post('OrtFchEj2');
                $OrtFchEm = $this->input->post('OrtFchEm2');
                $OrtDes = $OrtEntr . "<br>" . $OrtDes . "<br>" . $OrtMes . "<br>" . $OrtAnio . "<br>" . $OrtObs;
                $subactividades = $this->SubActividad_model->get_subactividad_x_actividad($idActividad);
                foreach ($subactividades as $subactividad) {
                    $metradoPl = $this->input->post('val_' . $subactividad['SacId']);
                    if ($metradoPl != "") {
                        $ordenMetradoPl[$subactividad['SacId']]['idSubActividad'] = $subactividad['SacId'];
                        $ordenMetradoPl[$subactividad['SacId']]['metradoPl'] = $metradoPl;
                    }
                }
                /*   Destino de la Carpeta de los archivos  */
                $dir_path = FCPATH . 'assets/archivos/toma_estado';
                if (!file_exists($dir_path)) {
                    mkdir($dir_path, DIR_WRITE_MODE, true);
                }
                $config['upload_path'] = $dir_path;
                $config['allowed_types'] = '*';
                $config['encrypt_name'] = TRUE;

                /*      Subida de Archivos al Servidor  */
                $i = 0;
                $files = array();
                $is_file_error = FALSE;

                if ($_FILES['upload_file1']['size'] <= 0) {
                    echo 'No se Añaden archivos';
                    $archivos = array();
                    $OrtUsrEn = $this->data['userdata']['UsrId'];
                    $resp = $this->OrdenTrabajo_model->update_orden_trabajo($OrtNum, $OrtDes, $OrtFchEm, $OrtUsrEn, $archivos, $idOrdenTrabajo,$ordenMetradoPl);
                    if ($resp === TRUE) {
                        $this->session->set_flashdata('mensaje', array('success', 'La Orden de Trabajo se ha actualizado Correctamente.'));
                        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                        $this->data['accion'] = 'editar';
                        $this->data['view'] = 'general/OrdenTrabajo_view';
                        $this->data['menu']['padre'] = $padre;
                        $this->data['menu']['hijo'] = $hijo;
                        $this->data['proceso'] = $des;
                        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Editar Orden de Trabajo', ''));
                        $this->load->view('template/Master', $this->data);
                        redirect(base_url() . $padre. '/'. $hijo.'/editar/' . $idOrdenTrabajo);
                    } else {
                        for ($i = 0; $i < count($files); $i++) {
                            $file = $dir_path . $files[$i]['file_name'];
                            if (file_exists($file)) {
                                unlink($file);
                            }
                        }
                        $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al momento de Actualizar.'));
                        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                        $this->data['accion'] = 'editar';
                        $this->data['view'] = 'general/OrdenTrabajo_view';
                        $this->data['menu']['padre'] = $padre;
                        $this->data['menu']['hijo'] = $hijo;
                        $this->data['proceso'] = $des;
                        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Editar Orden de Trabajo', ''));
                        $this->load->view('template/Master', $this->data);
                        redirect(base_url() . $padre. '/'. $hijo. '/editar/' . $idOrdenTrabajo);
                    }
                } else {
                    foreach ($_FILES as $key => $value) {
                        if (!empty($value['name'])) {
                            $this->load->library('upload', $config);
                            if (!$this->upload->do_upload($key)) {
                                $this->upload->display_errors();
                                $is_file_error = TRUE;
                            } else {
                                $files[$i] = $this->upload->data();
                                ++$i;
                            }
                        }
                    }
                }

                /* Se han producido errores, tenemos que eliminar los archivos subidos */
                if ($is_file_error && $files) {
                    for ($i = 0; $i < count($files); $i++) {
                        $file = $dir_path . $files[$i]['file_name'];
                        if (file_exists($file)) {
                            unlink($file);
                        }
                    }
                }
                $archivos = $files;
                $OrtUsrEn = $this->data['userdata']['UsrId'];
                if (!$is_file_error && $files) {
                    $resp = $this->OrdenTrabajo_model->update_orden_trabajo($OrtNum, $OrtDes, $OrtFchEm, $OrtUsrEn, $archivos, $idOrdenTrabajo);
                    if ($resp === TRUE) {
                        $this->session->set_flashdata('mensaje', array('success', 'La Orden de Trabajo se ha actualizado Correctamente.'));
                        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                        $this->data['accion'] = 'editar';
                        $this->data['view'] = 'general/OrdenTrabajo_view';
                        $this->data['menu']['padre'] = $padre;
                        $this->data['menu']['hijo'] = $hijo;
                        $this->data['proceso'] = $des;
                        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Editar Orden de Trabajo', ''));
                        $this->load->view('template/Master', $this->data);
                        redirect(base_url() . $padre. '/'. $hijo. '/editar/' . $idOrdenTrabajo);
                    } else {
                        for ($i = 0; $i < count($files); $i++) {
                            $file = $dir_path . $files[$i]['file_name'];
                            if (file_exists($file)) {
                                unlink($file);
                            }
                        }
                        $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al momento de Actualizar.'));
                        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                        $this->data['accion'] = 'editar';
                        $this->data['view'] = 'general/OrdenTrabajo_view';
                        $this->data['menu']['padre'] = $padre;
                        $this->data['menu']['hijo'] = $hijo;
                        $this->data['proceso'] = $des;
                        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Editar Orden de Trabajo', ''));
                        $this->load->view('template/Master', $this->data);
                        redirect(base_url() . $padre. '/'. $hijo. '/editar/' . $idOrdenTrabajo);
                    }
                }
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Complete los campos.'));
                $this->data['accion'] = 'editar';
                $this->data['view'] = 'general/OrdenTrabajo_view';
                $this->data['menu']['padre'] = $padre;
                $this->data['menu']['hijo'] = $hijo;
                $this->data['proceso'] = $des;
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Editar Orden de Trabajo', ''));
                $this->load->view('template/Master', $this->data);
                redirect(base_url() . $padre. '/'. $hijo. '/editar/' . $idOrdenTrabajo);
            }
        }
        else {
            show_404();
        }
    }

    public function orden_eliminar($Cod,$PerVal,$idOrdenTrabajo) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('eliminar_orden');
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $actividades = $this->Actividad_model->get_all_actividad_permiso();
        foreach ($actividades as $actividad):
            if ($actividad['ActCod'] == $Cod) {
                //$idActividad = $actividad['ActId'];
                $padre = $actividad['ActCod'];
                $des = $actividad['ActDes'];
                foreach ($actividad['permisos'] as $permiso):
                    if( $permiso['GpeDes'] == 'Ordenes de Trabajo'){
                        $hijo = $permiso['GpeVal'];
                    }
                endforeach;
            }
        endforeach;
        $idGpeVal = $this->Actividad_model->get_id_x_GpeVal($padre);
        if ($idGpeVal===$PerVal)
        {
            $this->data['ordenTrabajo'] = $this->OrdenTrabajo_model->get_one_orden_trabajo($idOrdenTrabajo);
            $this->OrdenTrabajo_model->delete_orden($idOrdenTrabajo);
            $this->session->set_flashdata('mensaje', array('success', 'La Orden de Trabajo N° '.$this->data['ordenTrabajo']['OrtNum']. ' se ha eliminado satisfactoriamente.'));
            $this->data['menu']['padre'] = $padre;
            $this->data['menu']['hijo'] = $hijo;
            $this->data['proceso'] = $des;
            redirect(base_url().$padre.'/'.$hijo);
        }
        else {
            show_404();
        }
    }

    /* METODOS PARA PENALIZACIONES A LA ORDEN DE TRABAJO   */

    public function orden_penalizaciones($Cod,$PerVal,$idOrdenTrabajo) {
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $actividades = $this->Actividad_model->get_all_actividad_permiso();
        foreach ($actividades as $actividad):
            if ($actividad['ActCod'] == $Cod) {
                //$idActividad = $actividad['ActId'];
                $padre = $actividad['ActCod'];
                $des = $actividad['ActDes'];
                foreach ($actividad['permisos'] as $permiso):
                    if( $permiso['GpeDes'] == 'Ordenes de Trabajo'){
                        $hijo = $permiso['GpeVal'];
                    }
                endforeach;
            }
        endforeach;
        $idGpeVal = $this->Actividad_model->get_id_x_GpeVal($padre);
        if ($idGpeVal===$PerVal)
        {
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            $this->acceso_cls->verificarPermiso('penalizar_orden');

            $this->data['ordenTrabajoEdit'] = $this->OrdenTrabajo_model->get_one_orden_trabajo($idOrdenTrabajo);
            $this->data['penalizaciones'] = $this->Penalizacion_model->get_all_penalizaciones_x_orden($idOrdenTrabajo);

            $this->data['accion'] = 'editar';
            $this->data['view'] = 'general/OrdenPenalizaciones_view';
            $this->data['menu']['padre'] = $padre;
            $this->data['menu']['hijo'] = $hijo;
            $this->data['padre'] = $padre;
            $this->data['hijo'] = $hijo;
            $this->data['proceso'] = $des;
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajoEdit']['OrtNum'], ''));
            $this->load->view('template/Master', $this->data);
        }
        else {
            show_404();
        }
    }

    public function orden_penalizaciones_nuevo($Cod,$PerVal,$idOrdenTrabajo) {
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $actividades = $this->Actividad_model->get_all_actividad_permiso();
        foreach ($actividades as $actividad):
            if ($actividad['ActCod'] == $Cod) {
                //$idActividad = $actividad['ActId'];
                $padre = $actividad['ActCod'];
                $des = $actividad['ActDes'];
                foreach ($actividad['permisos'] as $permiso):
                    if( $permiso['GpeDes'] == 'Ordenes de Trabajo'){
                        $hijo = $permiso['GpeVal'];
                    }
                endforeach;
            }
        endforeach;
        $idGpeVal = $this->Actividad_model->get_id_x_GpeVal($padre);
        if ($idGpeVal===$PerVal)
        {
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            $this->acceso_cls->verificarPermiso('penalizar_orden');
            $this->data['detallesContratoPenalidad'] = $this->Contrato_model->get_all_detalle_contrato_penalidad();
            $ordenTrabajo = $this->OrdenTrabajo_model->get_one_orden_trabajo($idOrdenTrabajo);
            $idContrato = $ordenTrabajo['OrtConId'];
            $this->data['gruposPenalidad'] = $this->Contrato_model->get_all_grupo_penalidad_x_contrato($idContrato);
            $this->data['penalidades'] = $this->Penalidad_model->get_penalidad_x_grupo($this->data['gruposPenalidad'][0]['GpeId']);
            $this->data['accion'] = 'nuevo';
            $this->data['view'] = 'general/OrdenPenalizacion_view';
            $this->data['menu']['padre'] = $padre;
            $this->data['menu']['hijo'] = $hijo;
            $this->data['proceso'] = $des;
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Penalizaciones de la Orden de Trabajo N° ' . $ordenTrabajo['OrtNum'], $padre.'/'.$hijo . '/' . $idOrdenTrabajo . '/penalizaciones'), array('Registrar Penalización', '')); 
            $this->load->view('template/Master', $this->data); 
        }
        else {
            show_404();
        }
    }

    public function orden_penalizaciones_nuevo_guardar($Cod,$PerVal,$idOrdenTrabajo) {
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $actividades = $this->Actividad_model->get_all_actividad_permiso();
        foreach ($actividades as $actividad):
            if ($actividad['ActCod'] == $Cod) {
                //$idActividad = $actividad['ActId'];
                $padre = $actividad['ActCod'];
                //$des = $actividad['ActDes'];
                foreach ($actividad['permisos'] as $permiso):
                    if( $permiso['GpeDes'] == 'Ordenes de Trabajo'){
                        $hijo = $permiso['GpeVal'];
                    }
                endforeach;
            }
        endforeach;
        $idGpeVal = $this->Actividad_model->get_id_x_GpeVal($padre);
        if ($idGpeVal===$PerVal)
        {
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            $this->data['ordenTrabajo'] = $this->OrdenTrabajo_model->get_one_orden_trabajo($idOrdenTrabajo);
            $this->acceso_cls->verificarPermiso('penalizar_orden');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('PnzCod', 'Codigo de Penalizacion', 'required');
            $this->form_validation->set_rules('PnzDes', 'Descripcion de Penalizacion', 'required');
            $this->form_validation->set_rules('PnzCas', 'Numero de Casos Suscitados', 'required');
            $this->form_validation->set_rules('PnzDia', 'Numero de Dias de retraso hasta la subsanación pertinente', 'required');
            $this->form_validation->set_rules('DcpId', 'Detalle Contrato Penalidad', 'required');
            if ($this->form_validation->run() == TRUE) {
                $PnzCod = $this->input->post('PnzCod');
                $PnzDes = $this->input->post('PnzDes');
                $PnzCas = $this->input->post('PnzCas');
                $PnzDia = $this->input->post('PnzDia');
                $DcpId = $this->input->post('DcpId');
                $Dcp = $this->Contrato_model->get_one_detalle_contrato_penalidad($DcpId);
                $resp = $this->Penalizacion_model->insert_penalizacion($PnzCod, $PnzDes, $PnzCas, $PnzDia, $Dcp, $idOrdenTrabajo);
                if ($resp) {
                    $this->session->set_flashdata('mensaje', array('success', 'Se ha Registrado la Penalización a la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum']));
                    $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                    $this->data['accion'] = 'nuevo';
                    $this->data['view'] = 'general/OrdenPenalizacion_view';
                    $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], $padre .'/'. $hijo . '/' . $idOrdenTrabajo . '/penalizaciones'), array('Registrar Penalización', ''));
                    redirect(base_url() . $padre.'/'. $hijo . '/'. $idOrdenTrabajo . '/penalizaciones');
                } else {
                    $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al momento de guardar.'));
                    $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);

                    $this->data['accion'] = 'nuevo';
                    $this->data['view'] = 'general/OrdenPenalizacion_view';
                    $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], $padre .'/'. $hijo . '/' . $idOrdenTrabajo . '/penalizaciones'), array('Registrar Penalización', ''));
                    redirect(base_url() . $padre .'/'. $hijo . '/' . $idOrdenTrabajo . '/penalizacion/nuevo');
                }
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Complete los campos.'));
                $this->data['accion'] = 'nuevo';
                $this->data['view'] = 'general/OrdenPenalizacion_view';
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], $padre .'/'. $hijo . '/' . $idOrdenTrabajo . '/penalizaciones'), array('Registrar Penalización', ''));
                $this->load->view('template/Master', $this->data);
                redirect(base_url() . $padre .'/'. $hijo . '/' . $idOrdenTrabajo . '/penalizacion/nuevo');
            }
        }
        else {
            show_404();
        }
    }

    public function orden_penalizaciones_editar($Cod, $PerVal, $idOrdenTrabajo, $idPenalizacion) {
        if (!isset($idOrdenTrabajo) && !isset($idPenalizacion)) {
            show_404();
        }
        $actividades = $this->Actividad_model->get_all_actividad_permiso();
        foreach ($actividades as $actividad):
            if ($actividad['ActCod'] == $Cod) {
                //$idActividad = $actividad['ActId'];
                $padre = $actividad['ActCod'];
                $des = $actividad['ActDes'];
                foreach ($actividad['permisos'] as $permiso):
                    if( $permiso['GpeDes'] == 'Ordenes de Trabajo'){
                        $hijo = $permiso['GpeVal'];
                    }
                endforeach;
            }
        endforeach;
        $idGpeVal = $this->Actividad_model->get_id_x_GpeVal($padre);
        if ($idGpeVal===$PerVal)
        {
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            $this->acceso_cls->verificarPermiso('penalizar_orden');
            $this->data['ordenTrabajo'] = $this->OrdenTrabajo_model->get_one_orden_trabajo($idOrdenTrabajo);
            $this->data['datospnz'] = $this->Penalizacion_model->get_datos_penalizacion($idPenalizacion);
            $this->data['penalizacionEdit'] = $this->Penalizacion_model->get_one_penalziacion($idPenalizacion);
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            $this->data['accion'] = 'editar';
            $this->data['view'] = 'general/OrdenPenalizacion_view';
            $this->data['menu']['padre'] = $padre;
            $this->data['menu']['hijo'] = $hijo;
            $this->data['proceso'] = $des;
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], $padre.'/'.$hijo . '/' . $idOrdenTrabajo . '/penalizaciones'), array('Editar Penalización', ''));
            $this->load->view('template/Master', $this->data);
        }
        else {
            show_404();
        }
    }

    public function orden_penalizaciones_editar_guardar($Cod, $PerVal, $idOrdenTrabajo, $idPenalizacion) {
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $actividades = $this->Actividad_model->get_all_actividad_permiso();
        foreach ($actividades as $actividad):
            if ($actividad['ActCod'] == $Cod) {
                //$idActividad = $actividad['ActId'];
                $padre = $actividad['ActCod'];
                $des = $actividad['ActDes'];
                foreach ($actividad['permisos'] as $permiso):
                    if( $permiso['GpeDes'] == 'Ordenes de Trabajo'){
                        $hijo = $permiso['GpeVal'];
                    }
                endforeach;
            }
        endforeach;
        $idGpeVal = $this->Actividad_model->get_id_x_GpeVal($padre);
        if ($idGpeVal===$PerVal)
        {
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            $this->acceso_cls->verificarPermiso('penalizar_orden');
            $this->data['ordenTrabajo'] = $this->OrdenTrabajo_model->get_one_orden_trabajo($idOrdenTrabajo);
            $this->load->library('form_validation');
            $this->form_validation->set_rules('PnzCod', 'Codigo de Penalizacion', 'required');
            $this->form_validation->set_rules('PnzDes', 'Descripcion de Penalizacion', 'required');
            $this->form_validation->set_rules('PnzCas', 'Numero de Casos Suscitados', 'required');
            $this->form_validation->set_rules('PnzDia', 'Numero de Dias de retraso hasta la subsanación pertinente', 'required');
            if ($this->form_validation->run() == TRUE) {
                $PnzCod = $this->input->post('PnzCod');
                $PnzDes = $this->input->post('PnzDes');
                $PnzCas = $this->input->post('PnzCas');
                $PnzDia = $this->input->post('PnzDia');
                $DcpId = $this->input->post('DcpId');
                $Dcp = $this->Contrato_model->get_one_detalle_contrato_penalidad($DcpId);
                $resp = $this->Penalizacion_model->update_penalizacion($PnzCod, $PnzDes, $PnzCas, $PnzDia, $Dcp, $idPenalizacion);
                if ($resp) {
                    $this->session->set_flashdata('mensaje', array('success', 'La Penalización de la Orden de Trabajo N° ' .$this->data['ordenTrabajo']['OrtNum'].' se ha actualizado correctamenrte.'));
                    $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);

                    $this->data['accion'] = 'nuevo';
                    $this->data['view'] = 'general/OrdenPenalizacion_view';
                    $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], $padre.'/'.$hijo. '/' . $idOrdenTrabajo . '/penalizaciones'), array('Editar Penalización', ''));
                    redirect(base_url() . $padre.'/'.$hijo. '/' . $idOrdenTrabajo . '/penalizacion/editar/' . $idPenalizacion);
                } else {
                    $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al momento de Actualizar el registro.'));
                    $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                    $this->data['accion'] = 'nuevo';
                    $this->data['view'] = 'general/OrdenPenalizacion_view';
                    $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], $padre.'/'.$hijo. '/' . $idOrdenTrabajo . '/penalizaciones'), array('Editar Penalización', ''));
                    redirect(base_url() . $padre.'/'.$hijo. '/' . $idOrdenTrabajo . '/penalizacion/editar/' . $idPenalizacion);
                }
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Complete los campos'));
                $this->data['accion'] = 'nuevo';
                $this->data['view'] = 'general/OrdenPenalizacion_view';
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo ), array('Penalizaciones de la Orden de Trabajo N° ' . $this->data['ordenTrabajo']['OrtNum'], $padre.'/'.$hijo. '/' . $idOrdenTrabajo . '/penalizaciones'), array('Editar Penalización', ''));
                $this->load->view('template/Master', $this->data);
                redirect(base_url() . $padre.'/'.$hijo. '/' . $idOrdenTrabajo . '/penalizacion/editar/' . $idPenalizacion);
            }
        }
        else {
            show_404();
        }
    }

    public function orden_penalizaciones_eliminar($Cod,$PerVal,$idOrdenTrabajo, $idPenalizacion) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('penalizar_orden');
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $actividades = $this->Actividad_model->get_all_actividad_permiso();
        foreach ($actividades as $actividad):
            if ($actividad['ActCod'] == $Cod) {
                //$idActividad = $actividad['ActId'];
                $padre = $actividad['ActCod'];
                $des = $actividad['ActDes'];
                foreach ($actividad['permisos'] as $permiso):
                    if( $permiso['GpeDes'] == 'Ordenes de Trabajo'){
                        $hijo = $permiso['GpeVal'];
                    }
                endforeach;
            }
        endforeach;
        $idGpeVal = $this->Actividad_model->get_id_x_GpeVal($padre);
        if ($idGpeVal===$PerVal)
        {
            $this->data['ordenTrabajo'] = $this->OrdenTrabajo_model->get_one_orden_trabajo($idOrdenTrabajo);
            $this->Penalizacion_model->delete_penalizacion($idPenalizacion);
            $this->session->set_flashdata('mensaje', array('success', 'La Penalización de la Orden de Trabajo N° '.$this->data['ordenTrabajo']['OrtNum']. ' se ha eliminado satisfactoriamente.'));
            $this->data['menu']['padre'] = $padre;
            $this->data['menu']['hijo'] = $hijo;
            $this->data['proceso'] = $des;
            redirect(base_url() . $padre.'/'. $hijo . '/'. $idOrdenTrabajo . '/penalizaciones');
        }
        else {
            show_404();
        }
    }
    
    public function orden_valorizar($Cod, $idOrdenTrabajo) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_orden');
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $actividades = $this->Actividad_model->get_all_actividad_permiso();
        foreach ($actividades as $actividad):
            if ($actividad['ActCod'] == $Cod) {
                //$idActividad = $actividad['ActId'];
                $padre = $actividad['ActCod'];
                $des = $actividad['ActDes'];
                foreach ($actividad['permisos'] as $permiso):
                    if( $permiso['GpeDes'] == 'Ordenes de Trabajo'){
                        $hijo = $permiso['GpeVal'];
                    }
                endforeach;
            }
        endforeach;
        $this->data['Orden'] = $this->OrdenTrabajo_model->get_one_orden_trabajo_all_datos($idOrdenTrabajo);
        if (isset($this->data['Orden']['OrtActId'])) {
            $this->data['subactividades'] = $this->SubActividad_model->get_subactividad_x_actividad($this->data['Orden']['OrtActId']);
            //$valorizaciones = $this->OrdenTrabajo_model->get_all_valorizacion_x_orden_trabajo($idOrdenTrabajo);
            //if (!empty($valorizaciones)) {
                $this->data['valorizacionesEdit'] = $this->OrdenTrabajo_model->get_all_valorizacion_x_orden_trabajo($idOrdenTrabajo);
                $this->data['accion'] = 'valorizar';
                $this->data['view'] = 'general/OrdenTrabajo_valorizar_view';
                $this->data['menu']['padre'] = $padre;
                $this->data['menu']['hijo'] = $hijo;
                $this->data['proceso'] = $des;
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Valorizar Orden de Trabajo N° ' . $this->data['Orden']['OrtNum'], ''));
                $this->load->view('template/Master', $this->data);
            /*} else {
                $this->data['accion'] = 'valorizar';
                $this->data['view'] = 'general/OrdenTrabajo_valorizar_view';
                $this->data['menu']['padre'] = $padre;
                $this->data['menu']['hijo'] = $hijo;
                $this->data['proceso'] = $des;
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', $padre.'/'.$hijo), array('Valorizar Orden de Trabajo N° ' . $this->data['OTTomaEstado']['OrtNum'], ''));
                $this->load->view('template/Master', $this->data);
            }*/
        }
    }

    public function orden_valorizar_guardar($Cod, $idOrdenTrabajo) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_orden');
        if (!isset($idOrdenTrabajo)) {
            show_404();
        }
        $this->load->library('form_validation');
        $OTTomaEstado = $this->OrdenTrabajo_model->get_one_orden_trabajo_all_datos($idOrdenTrabajo);
        $subactividades = $this->SubActividad_model->get_subactividad_x_actividad($OTTomaEstado['OrtActId']);
        /* VALIDAR LOS CAMPOS DE EJECUCIONES        */
        foreach ($subactividades as $subactividad) {
            $this->form_validation->set_rules('val_' . $subactividad['SacId'], 'Ejecuciones', 'required');
        }

        if ($this->form_validation->run() == TRUE) {
            foreach ($subactividades as $subactividad) {
                $ejecuciones = $this->input->post('val_' . $subactividad['SacId']);
                if ($ejecuciones != "") {
                    $ordenValorizaciones[$subactividad['SacId']]['idSubActividad'] = $subactividad['SacId'];
                    $ordenValorizaciones[$subactividad['SacId']]['ejecuciones'] = $ejecuciones;
                }
            }
            $OrtId = $this->input->post('OrtId');
            $FchVal = $this->input->post('FchVal');
            $LioUsrId = $this->data['userdata']['UsrId'];
            $LioCntId = $this->data['userdata']['UsrCntId'];

            $resp = $this->OrdenTrabajo_model->insert_valorizacion_orden_trabajo($LioFchVl, $LioOrtId, $LioUsrId, $LioCntId, $ordenValorizaciones);

            if ($resp) {
                $this->session->set_flashdata('mensaje', array('success', 'Se ha Valorizado las Ejecuciones de la Orden de Trabajo de Toma de Estado'));
                $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                $this->data['accion'] = 'valorizar';
                $this->data['view'] = 'general/OrdenTrabajo_valorizar_view';
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Valorizar Orden de Trabajo N° ' . $this->data['OTTomaEstado']['OrtNum'], ''));
                $this->data['menu']['hijo'] = 'ordenes';
                redirect(base_url() . 'toma_estado/orden/valorizar/' . $idOrdenTrabajo);
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al momento de Actualizar el registro.'));
                $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                $this->data['accion'] = 'valorizar';
                $this->data['view'] = 'general/OrdenTrabajo_valorizar_view';
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Valorizar Orden de Trabajo N° ' . $this->data['OTTomaEstado']['OrtNum'], ''));
                $this->data['menu']['hijo'] = 'ordenes';
                redirect(base_url() . 'toma_estado/orden/valorizar/' . $idOrdenTrabajo);
            }
        }
    }

}
