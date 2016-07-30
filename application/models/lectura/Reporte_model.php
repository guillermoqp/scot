<?php

class Reporte_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('lectura/OTTomaEstado_model');
        $this->load->model('lectura/Lectura_model');
        $this->load->model('general/SubActividad_model');
        $this->load->model('general/Contrato_model');
        $this->load->model('catastro/Catastro_model');
        $this->load->model('general/NivelGrupo_model');
    }
    
    public function get_ciclos_control($idNivel , $idPeriodo , $idContratante) 
    {
        $ciclos = $this->NivelGrupo_model->get_all_grupo_x_nivel_cantidad($idNivel, $idPeriodo);
        
        foreach ($ciclos as $key => $ciclo) {
            $fechas = $this->OTTomaEstado_model->get_fechas_ejecucion_x_ciclo_x_periodo($idPeriodo, $idContratante, $ciclo['GprId']);
            $ciclos[$key]['fecha'] = $fechas;
            $cantLecturasPr = $this->Lectura_model->get_cant_lectura_prog_x_ciclo_x_periodo($idPeriodo, $idContratante, $ciclo['GprId']);
            $ciclos[$key]['programadas'] = $cantLecturasPr;
            $cantLecturasEj = $this->Lectura_model->get_cant_lectura_ejec_x_ciclo_x_periodo($idPeriodo, $idContratante, $ciclo['GprId']);
            $ciclos[$key]['ejecutadas'] = $cantLecturasEj;
        }
        return $ciclos;
    }
}
