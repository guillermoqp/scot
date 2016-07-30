<?php
class OTDistribucion_cst_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('contratista/distribucion/OTDistribucion_cst_model');
        $this->load->model('distribucion/OTDistribucion_model');
        $this->load->model('general/Actividad_model');
        $this->load->model('sistema/Parametro_model');
        $this->load->model('general/Observacion_model');
        $this->load->model('contratista/acceso/Usuario_cst_model');
        $this->load->model('general/Contrato_model');
        $this->load->model('distribucion/Distribuidor_model');
        $this->load->model('distribucion/Distribucion_model');
        $this->load->model('general/Contratista_model');
        $this->load->library('utilitario_cls');
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->load->library('utilitario_cls');
        $this->acceso_cls->isLogin();

        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->idContratante = $this->data['userdata']['contratante']['CntId'];
        $this->idActividad = $this->Actividad_model->get_id_actividad_x_codigo('distribucion');

        $this->data['proceso'] = 'Distribucion de Recibos y Comunicaciones';
        $this->data['menu']['padre'] = 'distribucion';
        $this->data['menu']['hijo'] = 'ordenes_distribucion';
    }

    public function orden_listar()
    {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermisoCst('ver_orden_distribucion');

        $this->data['ordenesTrabajo'] = $this->OTDistribucion_model->get_all_orden_trabajo_x_contratante($this->idContratante, $this->idActividad);

        $this->data['view'] = 'contratista/distribucion/OTsDistribucion_cst_view';

        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', ''));

        $this->load->view('contratista/template/Master_cst_view', $this->data);
    }

    public function orden_ver($idOrdenTrabajo) {

        $OrdenTrabajo = $this->OTDistribucion_cst_model->get_one_orden_trabajo($idOrdenTrabajo);
        if (!isset($OrdenTrabajo)) {
            show_404();
        }
        else    {
        $this->acceso_cls->verificarPermisoCst('ver_orden_distribucion');
        $idParametro = $this->Parametro_model->get_one_detParam_x_codigo('no_rec')['DprId'];
        //SI ESTA EN NO RECIBIDO
        $OrtUsrRs = $_SESSION['usuario_id'];
        $this->OTDistribucion_cst_model->update_usr_rs_orden_trabajo($idOrdenTrabajo, $OrtUsrRs);
        $orden = $this->OTDistribucion_cst_model->get_one_orden_trabajo_all_datos($idOrdenTrabajo);
        if ($orden['OrtEstId'] == $idParametro) {
            $idParametro = $this->Parametro_model->get_one_detParam_x_codigo('rec')['DprId'];
            $this->OTDistribucion_cst_model->update_estado_orden_trabajo($idOrdenTrabajo, $idParametro);
        }
        
        $ordenTrabajoEdit = $this->OTDistribucion_cst_model->get_one_orden_trabajo($idOrdenTrabajo);
       
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
        $this->data['totalLecturistas'] = $this->Distribuidor_model->get_cant_distribuidores_x_contrato($idContrato);
        $subOr = $this->OTDistribucion_cst_model->get_cantSubords_por_ordenTrabajo_porDia($idOrdenTrabajo);
        $this->data['totalSubordenes'] = array_sum(array_column($subOr, 'cantidad'));
        
        
        $SubOrdenes = $this->OTDistribucion_cst_model->get_cantLec_por_suborden_por_ordenTrabajo_porDia($idOrdenTrabajo);
        foreach ($SubOrdenes as $nroSuborden => $suborden) {
            $SubOrdenes[$nroSuborden]['nroLecturistas'] = $this->OTDistribucion_cst_model->get_cantSubords_por_ordenTrbj_porDia($idOrdenTrabajo,$SubOrdenes[$nroSuborden]['SodFchEj']);
            $subordenesSinAsignar = $this->Distribucion_model->get_detalle_suministros_sin_asignar_distribuidor($idOrdenTrabajo,$SubOrdenes[$nroSuborden]['SodFchEj']);  
            if(!is_null($subordenesSinAsignar[0]['SodDbrId'])) { 
                $SubOrdenes[$nroSuborden]['asignadas'] = TRUE ;
            }
            else {$SubOrdenes[$nroSuborden]['asignadas'] = FALSE ;}
        }
        /*
        $SubOrdenes = $this->OTDistribucion_cst_model->get_cantLec_por_suborden_por_ordenTrabajo_porDia($idOrdenTrabajo);
        foreach ($SubOrdenes as $nroSuborden => $suborden) {
            $SubOrdenes[$nroSuborden]['nroLecturistas'] = $this->OTDistribucion_cst_model->get_cantSubords_por_ordenTrbj_porDia($idOrdenTrabajo,$SubOrdenes[$nroSuborden]['SodFchEj']);
            $subordenesSinAsignar = $this->Distribucion_model->get_detalle_suministros_sin_asignar_distribuidor($idOrdenTrabajo,$SubOrdenes[$nroSuborden]['SodFchEj']);  
            $contadorAsignadas = 0;
            foreach ($subordenesSinAsignar as $subordenSinAsignar) {
                if(!is_null($subordenSinAsignar['SodDbrId'])) { 
                    $contadorAsignadas++;
                }
            }
            if($contadorAsignadas == count($subordenesSinAsignar)) { 
                $SubOrdenes[$nroSuborden]['asignadas'] = TRUE ;
            }
            else {$SubOrdenes[$nroSuborden]['asignadas'] = FALSE ;}
        }
        */
        $this->data['SubOrdenesDia'] = $SubOrdenes;
        
        
        //AGREGAR LA CANTIDAD
        $this->data['cantidad'] = $this->Distribucion_model->get_cant_dist_x_orden($idOrdenTrabajo);
        
        $subordenes = $this->Distribucion_model->get_detalle_suministros_lecturista($idOrdenTrabajo);
        $subordenesTotales = $this->OTDistribucion_cst_model->get_cantidad_subordenes_x_orden($idOrdenTrabajo);
        
        if(empty($subordenes)||count($subordenes)<$subordenesTotales)
        {
            $this->session->set_flashdata('mensaje', array('error', 'Faltan asignar Distribuidores a las Sub ordenes de Distribucion de Recibos.'));
            $this->data['Subciclos'] = $this->Distribucion_model->get_desc_ciclo_x_orden($idOrdenTrabajo);
            $this->data['cicloOrden'] = $this->OTDistribucion_cst_model->get_ciclo_orden_trabajo($idOrdenTrabajo);
            $this->data['LecSubCiclos'] = $this->Distribucion_model->get_lecturistas_x_ciclo_x_orden($idOrdenTrabajo);
        }
        else
        {    
            $this->data['subordenes'] = $subordenes;
            $this->data['LecSubCiclos'] = $this->Distribucion_model->get_lecturistas_x_ciclo_x_orden($idOrdenTrabajo);
            $this->data['cicloOrden'] = $this->OTDistribucion_cst_model->get_ciclo_orden_trabajo($idOrdenTrabajo);
           
            $this->data['distribuidores'] = $this->Usuario_cst_model->get_all_usuario_distribuidor();
            
            $this->data['Subciclos'] = $this->Distribucion_model->get_desc_ciclo_x_orden($idOrdenTrabajo);
        }
        $this->data['view'] = 'contratista/distribucion/OTDistribucion_ver_ciclos_cst_view';
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'cst/distribucion/ordenes'), array('Ver Orden de Trabajo de Distribucion de Recibos', ''));
        $this->load->view('contratista/template/Master_cst_view', $this->data);
        }
    }
    
    
    public function orden_ver_detalle_ciclo(    $idOrdenTrabajo ,    $fechaEj ) {
        
        $this->acceso_cls->verificarPermisoCst('ver_orden_distribucion');
        $subordenesSinAsignar = $this->Distribucion_model->get_detalle_distribuciones_sin_asignar_distribuidores($idOrdenTrabajo,$fechaEj);
        
        $this->data['subordenesSinAsignar'] = $subordenesSinAsignar;
        $this->data['error'] = FALSE;
        
        $idContrato = $this->validar_contrato_actividad($this->idActividad, $this->idContratante);
        $distribuidores = $this->Distribuidor_model->get_distribuidores_disponibles($this->idContratante, $idContrato, $fechaEj);
        
        if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['asignar_distribuidores'])) {

            $aleatorio = $this->input->post('asignacion') == 0;
            $subordenes = $subordenesSinAsignar;

            $isOk = TRUE;
            if (!$aleatorio) {
                //CUANDO ES MANUAL
                foreach ($subordenes as $nroSuborden => $suborden) {
                    if (isset($_POST['distribuidor_' . $nroSuborden])) {
                        $idDistribuidor = $this->input->post('distribuidor_' . $nroSuborden);
                        if ($idDistribuidor != 0) {
                            //SELECCIONO UN LECTURISTA DEL COMBO
                            if (isset($distribuidores[$idDistribuidor])) {
                                //AUN NO ESTA ASIGNADO
                                $subordenes[$nroSuborden]['idDistribuidor'] = $idDistribuidor;
                                unset($distribuidores[$idDistribuidor]);
                            } else {
                                //YA ESTA ASIGNADO Y SE REPITE
                                $distribuidores[$idDistribuidor] = 0;
                                $isOk = FALSE;
                            }
                        } else {
                            //NO SELECCIONO A UN LECTURISTA
                            $distribuidores[$idDistribuidor] = 0;
                            $isOk = FALSE;
                        }
                    }
                }
                if ($isOk) {
                    $exito = 0;
                    foreach ($subordenes as $nroSuborden => $suborden) {
                        $resultado = $this->OTDistribucion_cst_model->update_one_subordenDistribucion_id($suborden["idDistribuidor"], $suborden["SodId"]);
                        if ($resultado == TRUE) {
                            $exito++;
                        }
                    }
                    if ($exito == count($subordenes))
                    {$this->session->set_flashdata('mensaje', array('success', 'Sin errores en Asignación, Subordenes almacenadas con sus respectivos Distribuidores ( Asignación Manual)'));
                    $this->data['cerrar'] = TRUE;
                    }
                    else
                        $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al almacenar la Asignación Manual'));
                }
                else {
                    
                    $errores = "";
                    foreach ($subordenes as $suborden) {
                        if (!isset($suborden['idDistribuidor'])) {
                            $errores .= $suborden['SolCod'] . ", ";
                        }
                    }
                    $this->data['error'] = TRUE;
                    $this->session->set_flashdata('mensaje', array('error', 'Error en Asignacion de las Subordenes con Código: ' . $errores));
                }
            } else {
                
                foreach ($subordenes as $nroSuborden => $suborden) {
                    if (isset($_POST['distribuidor_' . $nroSuborden])) {
                        $idDistribuidor = $this->input->post('distribuidor_' . $nroSuborden);
                        $subordenes[$nroSuborden]['idDistribuidor'] = $idDistribuidor;
                    }
                }
                $exito = 0;
                foreach ($subordenes as $nroSuborden => $suborden) {
                    $resultado = $this->OTDistribucion_cst_model->update_one_subordenDistribucion_id($subordenes[$nroSuborden]['idDistribuidor'], $suborden["SodId"]);
                    if ($resultado == TRUE) {
                        $exito++;
                    }
                }
                if ($exito == count($subordenes))
                { $this->session->set_flashdata('mensaje', array('success', 'Sin errores en Asignación, Subordenes almacenadas con sus respectivos Distribuidores( Asignación Aleatoria).'));
                $this->data['cerrar'] = TRUE;
                }
                else
                    $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al almacenar la Asignación Aleatoria'));
            }
        }
        
        $this->data['fechaEjecucionSubordenes'] = $fechaEj;
        $this->data['distribuidores'] = $distribuidores;
        $this->data['cantidad'] = $this->OTDistribucion_cst_model->get_cantLec_por_suborden_por_ordenTrbj_porDia($idOrdenTrabajo,$fechaEj);
        
        $this->data['nroDistribuidores'] = $this->OTDistribucion_cst_model->get_cantSubords_por_ordenTrbj_porDia($idOrdenTrabajo,$fechaEj);
        
        $this->data['view'] = 'contratista/distribucion/OTDistribucion_ciclos_detalle_cst_view';
        
        $this->load->view('template/Reporte', $this->data);
    }
    
    
    public function get_detalle_x_suborden_json(){
        $idSubOrden = $this->input->post('idSubOrden'); 
        
        $detalleSuborden = $this->Distribucion_model->get_distribuciones_x_idsuborden($idSubOrden);
        
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
}
