<?php

class Periodo_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Reporte_ctrllr -> control_lectura_redirigir
    //Periodo_ctrllr -> periodo_editar
    public function get_one_periodo($idPeriodo) {
        $query = $this->db->query('SELECT * FROM "Periodo" '
                . 'WHERE "PrdId" = ?', array($idPeriodo));
        return $query->row_array();
    }
    
    // CronogramaDistribucion_ctrllr -> cronograma_periodo_redirigir
    public function get_one_periodo_PrdOrd($PrdOrd) {
        $query = $this->db->query('SELECT * FROM "Periodo" '
                . 'WHERE "PrdOrd" = ?', array($PrdOrd));
        return $query->row_array();
    }
    
    // CronogramaLectura_ctrllr -> cronograma_mensual_nuevo
    public function get_one_periodo_x_orden($orden, $anio) {
        $query = $this->db->query('SELECT * FROM "Periodo" '
                . 'WHERE "PrdOrd" = ? AND "PrdAni" = ? ORDER BY "PrdOrd" DESC LIMIT 1', array($orden, $anio));
        return $query->row_array();
    }

    // Reporte_ctrllr -> control_lectura
    public function get_last_periodo_x_orden($orden, $anio) {
        $query = $this->db->query('SELECT * FROM "Periodo" '
                . 'WHERE "PrdOrd" <= ? AND "PrdAni" = ? ORDER BY "PrdOrd" DESC LIMIT 1', array($orden, $anio));
        return $query->row_array();
    }
    
    public function get_all_periodo($idActividad, $anio, $idContratante) {
        $query = $this->db->query('SELECT * FROM "Periodo" '
                . 'WHERE "PrdEli" = FALSE AND "PrdActId" = ? AND "PrdAni" = ? AND "PrdCntId" = ? ORDER BY "PrdOrd" ASC', array($idActividad, $anio,$idContratante));
        return $query->result_array();
    }
    
    public function get_all_periodo_por_rangoFechas($idActividad, $anio, $idContratante,$fechaIn,$FechaFn) {
        $query = $this->db->query('SELECT * FROM "Periodo" 
        WHERE "PrdEli" = FALSE AND "PrdActId" = ? AND "PrdAni" = ?
        AND "PrdCntId" = ? AND "PrdFchIn" = ? AND "PrdFchFn" = ?
        ORDER BY "PrdOrd" ASC', array($idActividad, $anio, $idContratante, $fechaIn, $FechaFn));
        return $query->row_array();
    }

    public function get_all_periodo_listar($idActividad, $idContratante) {
        $query = $this->db->query('SELECT * FROM "Periodo" '
                . 'WHERE "PrdEli" = FALSE AND "PrdActId" = ? AND "PrdCntId" = ? ORDER BY "PrdOrd" ASC', array($idActividad, $idContratante));
        return $query->result_array();
    }
    
    // CronogramaLEctura_ctrllr -> cronograma_anual_nuevo
    public function get_ultimo_periodo($idActividad, $anio, $idContratante) {
        $query = $this->db->query('SELECT * FROM "Periodo" '
                . 'WHERE "PrdEli" = FALSE AND "PrdActId" = ? AND "PrdAni" = ? ORDER BY "PrdOrd" DESC LIMIT 1', array($idActividad, ($anio-1)));
        return $query->row_array();
    }
    
    //Reporte_ctrllr -> control
    public function get_anios($idContratante, $idActividad) {
        $query = $this->db->query('SELECT DISTINCT "PrdAni" FROM "Orden_trabajo"
        JOIN  "Periodo"  ON "OrtPrdId" = "PrdId"
        WHERE "OrtCntId" = ?', array($idContratante));
        return array_column($query->result_array(), 'PrdAni');
    }
    
    //Reporte_ctrllr -> control
    public function get_ordenes_x_anio($idContratante, $idActividad, $anio) {
        $query = $this->db->query('SELECT DISTINCT "PrdOrd", "PrdCod" FROM "Periodo" 
        WHERE "PrdCntId" = ? AND "PrdActId" = ? AND "PrdAni" = ? ORDER BY "PrdOrd" ASC', array($idContratante, $idActividad, $anio));
        return array_column($query->result_array(),'PrdCod', 'PrdOrd');
    }
    
    
    //Periodo_ctrllr -> periodo_nuevo
    public function insert_periodo( $PrdOrd , $PrdAni , $PrdCod , $PrdFchIn  , $PrdFchFn , $idActividad , $idContratante  ) {
        $query = $this->db->query('INSERT INTO "Periodo" ("PrdOrd", "PrdAni", "PrdCod", "PrdFchIn" , "PrdFchFn" , "PrdEli", "PrdFchRg", "PrdFchAc", "PrdActId" , "PrdCntId") VALUES (?,?,?,?,?,?,?,?,?,?)'
                    , array( $PrdOrd , $PrdAni , $PrdCod , $PrdFchIn  , $PrdFchFn , FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idActividad , $idContratante) );
        return $query;
    }
    
    //Periodo_ctrllr -> periodo_editar
    public function update_periodo( $PrdCod , $PrdFchIn  , $PrdFchFn , $PrdId ) {
        $query = $this->db->query('UPDATE "Periodo" SET "PrdCod" = ? , "PrdFchIn" = ? , "PrdFchFn" = ? , "PrdFchAc" = ? WHERE "PrdId" = ?'
                , array( $PrdCod , $PrdFchIn  , $PrdFchFn , date("Y-m-d H:i:s") ,  $PrdId ));
        return $query;
    }
    
    
    //Periodo_ctrllr -> periodo_eliminar
    public function delete_periodo($PrdId) {
        $query = $this->db->query('UPDATE "Periodo" SET "PrdEli" = TRUE, "PrdFchAc" = ? WHERE "PrdId" = ?', array(date("Y-m-d H:i:s"), $PrdId ));
        return $query;
    }

    
}
