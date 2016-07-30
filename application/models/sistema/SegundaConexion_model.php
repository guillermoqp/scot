<?php

class SegundaConexion_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    public function get_all_ciclos() {
        $otherdb = $this->load->database('bd2', TRUE);
        $query = $otherdb->query('SELECT * FROM ciclo LIMIT 50');
        return $query->result_array();
    }
    
    public function get_one_usuario_cod_fact($codFacturacion) {
        $otherdb = $this->load->database('bd2', TRUE);
        $query = $otherdb->query('SELECT * FROM ciclo WHERE ciclo."CliCodFac" = ?', array($codFacturacion));
        return $query->row_array();
    }
    
    public function get_all_usuarios() {
        $otherdb = $this->load->database('XE', TRUE);
        $query = $otherdb->query('SELECT FUSUAPEMA,FUSUAPEPA,FUSUNOMBR,CLICODFAC,CLINOMBR FROM "ADMWEB"."CLIENTE" WHERE ROWNUM <= 100');
        return $query->result_array();
    }
}
