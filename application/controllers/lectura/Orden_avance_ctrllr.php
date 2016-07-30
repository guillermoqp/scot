<?php

class Orden_avance_ctrllr extends CI_Controller {
    var $idContratante;
    var $idActividad;

    public function __construct() {
        parent::__construct();
        $this->load->model('lectura/OTTomaEstado_model');
        $this->load->model('general/Actividad_model');
        $this->load->model('general/Contratista_model');
        $this->load->model('lectura/Lecturista_model');
        $this->load->model('lectura/Lectura_model');
        $this->load->model('general/NivelGrupo_model');

        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->load->library('utilitario_cls');
        $this->acceso_cls->isLogin();
        $this->data['menu']['padre'] = 'toma_estado';
        $this->data['menu']['hijo'] = 'ordenes';
        $this->data['proceso'] = 'Toma de Estado';
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->idContratante = $this->data['userdata']['contratante']['CntId'];
        $this->idActividad = $this->Actividad_model->get_id_actividad_x_codigo('toma_estado');
    }

    // toma_estado/orden/avance/(:num)
    public function avance_orden($idOrden) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_orden_lectura');
        $orden = $this->OTTomaEstado_model->get_one_orden_trabajo_all_datos($idOrden);
        if (!isset($orden)) {
            show_404();
        }
        $ciclos = $this->OTTomaEstado_model->get_avance_x_orden($idOrden);
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
        $this->data['GprNivel'] = $this->OTTomaEstado_model->get_ciclo_orden_trabajo($idOrden);
        $this->data['ciclos'] = $ciclos;
        $this->data['OTTomaEstado'] = $orden;
        $periodoFact = $this->Lectura_model->get_periodo_fact_x_orden($idOrden);
        $this->data['anioFact'] = $periodoFact['PrdAni'];
        $this->data['mesFact'] = $this->utilitario_cls->nombreMes($periodoFact['PrdOrd']);
        $this->data['contratista'] = $this->Contratista_model->get_one_contratista($orden['ConCstId']);
        $this->data['view'] = 'lectura/OTLectura_avance_view';
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Orden de trabajo ' . $orden['OrtNum'], ''));
        $this->load->view('template/Master', $this->data);
    }

    // toma_estado/orden/avance/(:num)/suborden/(:num)
    public function avance_suborden($idOrden, $codSubOrden) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_orden_lectura');
        if (!isset($idOrden)) {
            show_404();
        }
        
        $IdsubOrden = $this->Lectura_model->get_one_suborden_x_orden($idOrden, $codSubOrden)['SolId'];
        $subOrden = $this->Lectura_model->get_one_suborden($IdsubOrden);
        
        $lecturas = $this->OTTomaEstado_model->get_avance_x_suborden($idOrden, $codSubOrden);
        
        $lecturista = $this->Lecturista_model->get_one_lecturista($lecturas[array_rand($lecturas)]['SolLtaId']);
        
        $observaciones = $this->Lectura_model->get_obs_x_suborden($IdsubOrden);
        
        $idContratante = $this->data['userdata']['contratante']['CntId'];
        $this->data['lecturas'] = $lecturas;
        $this->data['observaciones'] = $observaciones;
        $this->data['lecturista'] = $lecturista;
        $orden = $this->OTTomaEstado_model->get_one_orden_trabajo_all_datos($idOrden);
        
        $this->data['OTTomaEstado'] = $orden;
        $this->data['subOrden'] = $subOrden;
        
        $periodoFact = $this->Lectura_model->get_periodo_fact_x_orden($idOrden);
        $this->data['anioFact'] = $periodoFact['PrdAni'];
        $this->data['mesFact'] = $this->utilitario_cls->nombreMes($periodoFact['PrdOrd']);
        $this->data['contratista'] = $this->Contratista_model->get_one_contratista($this->data['OTTomaEstado']['ConCstId']);
        $this->data['view'] = 'lectura/Lecturista_avance_view';
        $this->data['breadcrumbs'] = array(array('Ordenes de Trabajo', 'toma_estado/ordenes'), array('Orden de trabajo ' . $orden['OrtNum'], 'toma_estado/orden/avance/' . $orden['OrtId']), array('SubOrden ' . $codSubOrden, ''));
        $this->load->view('template/Master', $this->data);
    }

}
