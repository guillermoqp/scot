<?php

class OrdenTrabajo_cst_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('contratista/lectura/OTTomaEstado_cst_model');
        $this->load->model('general/Actividad_model');
        $this->load->model('sistema/Parametro_model');
        $this->load->model('general/Observacion_model');
        $this->load->model('contratista/acceso/Usuario_cst_model');
        $this->load->model('lectura/Lectura_model');
        $this->load->model('lectura/OTTomaEstado_model');
        $this->load->library('utilitario_cls');
        $this->load->helper(array('download', 'file', 'url', 'html', 'form'));
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();
    }

    public function obtener_ordenes_no_rec_x_usuario_json() {
        $idUsuario = $this->input->post('idUsuario');
        $parametro = $this->Parametro_model->get_one_detParam_x_codigo('no_rec');
        $OrtEstId = $parametro['DprId'];
        $Ordenes = $this->OTTomaEstado_cst_model->get_all_orden_trabajo_x_usuario($idUsuario, $OrtEstId);
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($Ordenes));
    }

    public function obtener_ordenes_ejecucion_x_usuario_json() {
        $idUsuario = $this->input->post('idUsuario');
        $parametro = $this->Parametro_model->get_one_detParam_x_codigo('eje');
        $OrtEstId = $parametro['DprId'];
        $Ordenes = $this->OTTomaEstado_cst_model->get_all_orden_trabajo_x_usuario($idUsuario, $OrtEstId);
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($Ordenes));
    }

    public function obtener_ordenes_liq_x_usuario_json() {
        $idUsuario = $this->input->post('idUsuario');
        $parametro = $this->Parametro_model->get_one_detParam_x_codigo('liq');
        $OrtEstId = $parametro['DprId'];
        $Ordenes = $this->OTTomaEstado_cst_model->get_all_orden_trabajo_x_usuario($idUsuario, $OrtEstId);
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($Ordenes));
    }

    public function orden_listar($Cod,$PerVal) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_orden');
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
        if($hijo==$PerVal)
        {
            $this->data['ordenesTrabajo'] = $this->OTTomaEstado_cst_model->get_all_orden_trabajo_x_contratante($this->data['userdata']['contratante']['CntId'],$idActividad);
            $this->data['subactividades'] = $this->OTTomaEstado_cst_model->get_subactividad_x_orden($idActividad);
            $this->data['view'] = 'contratista/OTGeneral/OrdenesTrabajo_cst_view';
            $this->data['menu']['padre'] = $padre;
            $this->data['menu']['hijo'] = $hijo;
            $this->data['padre'] = $padre;
            $this->data['hijo'] = $hijo;
            $this->data['proceso'] = $des;
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', ''));
            $this->load->view('contratista/template/Master_cst_view', $this->data);
        }
        else {
            show_404();
        }
    }

    public function orden_ver($Cod,$PerVal,$idOrdenTrabajo) {
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
        if($hijo==$PerVal)
        {
            $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
            $this->acceso_cls->verificarPermiso('ver_orden');
            $parametro = $this->Parametro_model->get_one_detParam_x_codigo('no_rec');
            $idParametro = $parametro['DprId'];
            $archivosEnv = $this->OTTomaEstado_cst_model->get_archivos_enviados_x_orden_trabajo($idOrdenTrabajo);
            $archivosRec = $this->OTTomaEstado_cst_model->get_archivos_recepcionados_x_orden_trabajo($idOrdenTrabajo);
            //SI ESTA EN NO RECIBIDO
            $OrtUsrRs = $this->data['userdata']['UsrId'];
            $this->OTTomaEstado_cst_model->update_usr_rs_orden_trabajo($idOrdenTrabajo, $OrtUsrRs);
            $orden = $this->OTTomaEstado_cst_model->get_one_orden_trabajo_all_datos($idOrdenTrabajo);
            if ($orden['OrtEstId'] == $idParametro) {
                $parametro = $this->Parametro_model->get_one_detParam_x_codigo('rec');
                $idParametro = $parametro['DprId'];
                $this->OTTomaEstado_cst_model->update_estado_orden_trabajo($idOrdenTrabajo, $idParametro);
            }
            if (!empty($archivosRec)) {
                $parametro = $this->Parametro_model->get_one_detParam_x_codigo('cer');
                $idParametro = $parametro['DprId'];
                $this->OTTomaEstado_cst_model->update_estado_orden_trabajo($idOrdenTrabajo, $idParametro);
            }
            $ordenTrabajoEdit = $this->OTTomaEstado_cst_model->get_one_orden_trabajo($idOrdenTrabajo);
            if (!isset($ordenTrabajoEdit)) {
                show_404();
            }
            $this->data['ordenTrabajoEdit'] = $ordenTrabajoEdit;
            $this->data['OrdenTrabajo'] = $orden;
            $this->data['subactividades'] = $this->OTTomaEstado_cst_model->get_subactividad_x_orden($idActividad);
            $idUsrEn = $this->data['OrdenTrabajo']['OrtUsrEn'];
            $idUsrRs = $this->data['OrdenTrabajo']['OrtUsrRs'];
            $this->data['UsrEn'] = $this->Usuario_cst_model->get_one_usuario_de_contratante($idUsrEn);
            $this->data['UsrRs'] = $this->Usuario_cst_model->get_one_usuario_de_contratista($idUsrRs);
            $this->data['archivosEnv'] = $archivosEnv;
            $this->data['archivosRec'] = $archivosRec;
            $this->data['view'] = 'contratista/OTGeneral/OrdenTrabajo_ver_cst_view';
            $this->data['accion'] = 'ver';
            $this->data['menu']['padre'] = $padre;
            $this->data['menu']['hijo'] = $hijo;
            $this->data['padre'] = $padre;
            $this->data['hijo'] = $hijo;
            $this->data['proceso'] = $des;
            $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'cst/'.$padre.'/'.$hijo ), array('Ver Orden de Trabajo', ''));
            $this->load->view('contratista/template/Master_cst_view', $this->data);
        }
        else {
            show_404();
        }
    }

    public function orden_ver_guardar($Cod,$PerVal,$idOrdenTrabajo) {
        /*   Destino de la Carpeta de los archivos  */
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $archivosEnv = $this->OTTomaEstado_cst_model->get_archivos_enviados_x_orden_trabajo($idOrdenTrabajo);
        $dir_path = FCPATH . 'assets/archivos/toma_estado';
        if (!file_exists($dir_path)) {
            mkdir($dir_path, DIR_WRITE_MODE, true);
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
        if($hijo==$PerVal)
        {
            $config['upload_path'] = $dir_path;
            $config['allowed_types'] = '*';
            $config['max_filename'] = '255';
            $config['encrypt_name'] = TRUE;
            $config['remove_spaces'] = TRUE;
            $config['max_size'] = '0';
            /*      Subida de Archivos al Servidor  */
            $i = 0;
            $files = array();
            $is_file_error = FALSE;
            if ($_FILES['upload_file1']['size'] <= 0) {
                //VALIDAR QUE EXISTAN ARCHIVOS DE RECEPCION DE LA ORDEN
                $this->session->set_flashdata('mensaje', array('error', 'Selecciona por lo menos un Archivo.'));
                $this->data['accion'] = 'enviar';
                $this->data['view'] = 'contratista/OTGeneral/OrdenTrabajo_ver_cst_view';
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'cst/'.$padre.'/'.$hijo), array('Ver Orden de Trabajo', ''));
                $this->load->view('contratista/template/Master_cst_view', $this->data);
                redirect(base_url() . 'cst/'.$padre.'/'.$hijo.'/ver/' . $idOrdenTrabajo);
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
            $cant = 0;
            if (!$is_file_error && $files) {
                foreach ($archivos as $archivo):
                    foreach ($archivosEnv as $archivoEnv):
                        if ($archivo['orig_name'] == $archivoEnv['ArcNomOr']) {
                            $cant = $cant + 1;
                        }
                    endforeach;
                endforeach;
                if ($cant != count($archivosEnv)) {
                    $this->session->set_flashdata('mensaje', array('success', 'Los Archivos no Coinciden.'));
                    $this->data['accion'] = 'enviar';
                    $this->data['view'] = 'contratista/OTGeneral/OrdenTrabajo_ver_cst_view';
                    $this->load->view('contratista/template/Master_cst_view', $this->data);
                    redirect(base_url() . 'cst/'.$padre.'/'.$hijo.'/ver/' . $idOrdenTrabajo);
                } else {
                    $parametro = $this->Parametro_model->get_one_detParam_x_codigo('cer');
                    $OrtEstId = $parametro['DprId'];
                    $resp = $this->OTTomaEstado_cst_model->update_orden_trabajo($archivos, $idOrdenTrabajo);
                    $resp2 = $this->OTTomaEstado_cst_model->update_estado_orden_trabajo($idOrdenTrabajo, $OrtEstId);

                    if ($resp === TRUE && $resp2 === TRUE) {

                        $this->session->set_flashdata('mensaje', array('success', 'Los Archivos se han enviado Satisfactoriamente.'));
                        $this->data['accion'] = 'enviar';
                        $this->data['view'] = 'contratista/OTGeneral/OrdenTrabajo_ver_cst_view';
                        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'cst/'.$padre.'/'.$hijo), array('Ver Orden de Trabajo', ''));
                        $this->load->view('contratista/template/Master_cst_view', $this->data);
                        redirect(base_url() . 'cst/'.$padre.'/'.$hijo.'/ver/' . $idOrdenTrabajo);
                    } else {
                        for ($i = 0; $i < count($files); $i++) {
                            $file = $dir_path . $files[$i]['file_name'];
                            if (file_exists($file)) {
                                unlink($file);
                            }
                        }
                        $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al momento de guardar.'));
                        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                        $this->data['accion'] = 'enviar';
                        $this->data['view'] = 'contratista/OTGeneral/OrdenTrabajo_ver_cst_view';
                        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'cst/'.$padre.'/'.$hijo), array('Ver Orden de Trabajo', ''));
                        $this->load->view('contratista/template/Master_cst_view', $this->data);
                        redirect(base_url() . 'cst/'.$padre.'/'.$hijo.'/ver/' . $idOrdenTrabajo);
                    }
                }
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'El/los Archivos Ingresados no son DBFs.'));
                $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                $this->data['accion'] = 'enviar';
                $this->data['view'] = 'contratista/OTGeneral/OrdenTrabajo_ver_cst_view';
                $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'cst/'.$padre.'/'.$hijo), array('Ver Orden de Trabajo', ''));
                $this->load->view('contratista/template/Master_cst_view', $this->data);
                redirect(base_url() . 'cst/'.$padre.'/'.$hijo.'/ver/' . $idOrdenTrabajo);
            }
        }
        else {
            show_404();
        }
    }

}
