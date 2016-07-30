<?php

class Orden_avance_cst_ctrllr extends CI_Controller {
    var $idContratante;
    var $idActividad;

    public function __construct() {
        parent::__construct();
        
        $this->load->model('distribucion/OTDistribucion_model');
        
        $this->load->model('general/Actividad_model');
        $this->load->model('general/Contratista_model');
        
        $this->load->model('distribucion/Distribuidor_model');
        $this->load->model('distribucion/Distribucion_model');
        
        $this->load->model('general/NivelGrupo_model');

        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->load->library('utilitario_cls');
        
        $this->acceso_cls->isLogin();
        $this->data['menu']['padre'] = 'distribucion';
        $this->data['menu']['hijo'] = 'ordenes_distribucion';
        $this->data['proceso'] = 'Distribucion de Recibos y Comunicaciones';
        
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->idContratante = $this->data['userdata']['contratante']['CntId'];
        $this->idActividad = $this->Actividad_model->get_id_actividad_x_codigo('toma_estado');
    }

    // cst/distribucion/orden/avance/(:num)
    public function avance_orden($idOrden) {
        
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermisoCst('ver_orden_distribucion');
        
        $orden = $this->OTDistribucion_model->get_one_orden_trabajo_all_datos($idOrden);
        
        if (!isset($orden)) {
            show_404();
        }
        
        $ciclos = $this->OTDistribucion_model->get_avance_x_orden($idOrden);
        $cantidadSubniveles = $this->NivelGrupo_model->get_cant_subniveles($orden['OrtNgrId']);
        
        //ES EL ULTIMO NIVEL Y NO TIENE MAS SUBNIVELES
        if($cantidadSubniveles==0)
        {
            $this->data['Nivel'] = $this->NivelGrupo_model->get_nivel_superior($orden['OrtNgrId']);
            $this->data['SubNivel'] = $this->NivelGrupo_model->get_one_nivelGrupo($orden['OrtNgrId']);
        }
        else if ($cantidadSubniveles>0)
        {
            $this->data['Nivel'] = $this->NivelGrupo_model->get_one_nivelGrupo($orden['OrtNgrId']);
            $this->data['SubNivel'] = $this->NivelGrupo_model->get_subnivel_inferior($orden['OrtNgrId']);
        }
        
        $this->data['NivelOrden'] = $this->NivelGrupo_model->get_one_nivelGrupo($orden['OrtNgrId']);
        $this->data['GprNivel'] = $this->OTDistribucion_model->get_ciclo_orden_trabajo($idOrden);
        $this->data['ciclos'] = $ciclos;
        
        $this->data['OTTomaEstado'] = $orden;
        
        $periodoFact = $this->Distribucion_model->get_periodo_fact_x_orden($idOrden);
        
        $this->data['anioFact'] = $periodoFact['PrdAni'];
        $this->data['mesFact'] = $this->utilitario_cls->nombreMes($periodoFact['PrdOrd']);
        $this->data['contratista'] = $this->Contratista_model->get_one_contratista($orden['ConCstId']);
       
        $this->data['view'] = 'contratista/distribucion/OTDistribucion_avance_cst_view';
        
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'cst/distribucion/ordenes'), array('Orden de trabajo ' . $orden['OrtNum'], ''));
        $this->load->view('contratista/template/Master_cst_view', $this->data);
    }

    // toma_estado/orden/avance/(:num)/suborden/(:num)
    public function avance_suborden($idOrden, $CodSubOrden) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermisoCst('ver_orden_distribucion');
        if (!isset($idOrden)) {
            show_404();
        }
        $idSubOrden = $this->Distribucion_model->get_one_suborden_x_orden($idOrden, $CodSubOrden)['SodId'];
        $subOrden = $this->Distribucion_model->get_one_suborden($idSubOrden);
        
        $distribuciones = $this->OTDistribucion_model->get_avance_x_suborden($idOrden, $CodSubOrden );
        
        $distribuidor = $this->Distribuidor_model->get_one_distribuidor($subOrden['SodDbrId']);
        $observaciones = $this->Distribucion_model->get_obs_x_suborden($idSubOrden);
        $idContratante = $this->data['userdata']['contratante']['CntId'];
        $this->data['distribuciones'] = $distribuciones;
        $this->data['observaciones'] = $observaciones;
        $this->data['distribuidor'] = $distribuidor;
        
        $orden = $this->OTDistribucion_model->get_one_orden_trabajo_all_datos($idOrden);
        
        $this->data['OTDistribucion'] = $orden;
        
        $periodoFact = $this->Distribucion_model->get_periodo_fact_x_orden($idOrden);
        
        $this->data['anioFact'] = $periodoFact['PrdAni'];
        $this->data['mesFact'] = $this->utilitario_cls->nombreMes($periodoFact['PrdOrd']);
        $this->data['contratista'] = $this->Contratista_model->get_one_contratista($this->data['OTDistribucion']['ConCstId']);
       
        $this->data['subOrden'] = $subOrden;
        
        $this->data['view'] = 'contratista/distribucion/Distribuidor_avance_cst_view';
        
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'cst/toma_estado/ordenes'), array('Orden de trabajo ' . $orden['OrtNum'], 'cst/toma_estado/orden/avance/' . $orden['OrtId']), array('SubOrden ' . $idSubOrden, ''));
        $this->load->view('contratista/template/Master_cst_view', $this->data);
    }
    
    // cst/toma_estado/orden/avance/(:num)/suborden/(:num)/json
    public function orden_avance_suborden_json() {
       
        $idOrden = $this->input->post('idOrden');
        $subOrden = $this->input->post('subOrden');
        
        $distribuciones = $this->Distribucion_model->get_ruta_x_suborden($idOrden, $subOrden);
        if(empty($distribuciones))
        {
            $json = array('result' => false, 'mensaje' => 'Sin Distribuciones.');
        }
        else
        {
            $LecPosLt = array_column($distribuciones, 'LecPosLt');
            $LecPosLg = array_column($distribuciones, 'LecPosLg');
            $json = array('result' => true, 'LecPosLt' => $LecPosLt, 'LecPosLg' => $LecPosLg );
        }
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($json));
    }

}
