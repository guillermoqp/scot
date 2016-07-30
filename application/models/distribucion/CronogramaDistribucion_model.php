<?php

class CronogramaDistribucion_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('general/Actividad_model');
        $this->load->model('catastro/Catastro_model');
    }

    //CRONOGRAMA PERIODO
    
    //CronogramaDistribucion_ctrllr=>cronograma_periodo_ver() - cronograma_periodo_editar()
    public function get_one_cronograma_periodo($idCronograma) {
        $query = $this->db->query('SELECT * FROM "Cron_distribucion_periodo" '
                . 'WHERE "CdpId" = ? AND "CdpEli" = FALSE ', array($idCronograma));
        $cronograma = $query->row_array();
        $cronograma['detalle'] = $this->get_detalle_conograma_periodol_x_cronograma($cronograma['CdpId']);
        return $cronograma;
    }
    
    //CronogramaDistribucion_model=>get_one_cronograma_periodo
    public function get_detalle_conograma_periodol_x_cronograma($idCronogramaMensual) {
        $query = $this->db->query('SELECT * FROM "Detalle_cron_distribucion_periodo" 
        WHERE "DdpCdpId" = ? ORDER BY "DdpGprId" ASC, "DdpFchDis" ASC', array($idCronogramaMensual));
        $rows = $query->result_array();
        $ciclos = array();
        foreach ($rows as $key => $row) {
            $row['DdpFchCal'] = date('d/m/Y', strtotime($row['DdpFchCal']));
            $row['DdpFchImp'] = date('d/m/Y', strtotime($row['DdpFchImp']));
            $row['DdpFchDis'] = date('d/m/Y', strtotime($row['DdpFchDis']));
            $ciclos[$row['DdpGprId']] = $row;
        }
        return $ciclos;
    }
    
    /*CronogramaDistribucion_ctrllr -> cronograma_periodo_listar()*/
    public function get_all_cronograma_periodo($idContratante) {
        $query = $this->db->query('SELECT * FROM "Cron_distribucion_periodo" 
        JOIN "Periodo" ON "CdpPrdId" = "PrdId"
        JOIN "Nivel_grupo" ON "CdpNgrId" = "NgrId"
        WHERE "CdpEli" = FALSE AND "CdpCntId" = ? 
        ORDER BY "CdpAni" DESC, "PrdOrd" DESC', array($idContratante));
        $rows = $query->result_array();
        $anios = array();
        foreach ($rows as $key => $row) {
            $anios[$row['CdpAni']][$key] = $row;
        }
        return $anios;
    }
    
    /*CronogramaDistribucion_ctrllr -> cronograma_periodo_nuevo()*/
    public function insert_cronograma_periodo($anio, $ClpPrdId , $cronograma, $idContratante , $idNivelGrupo) {
        $this->db->trans_start();
        $query = $this->db->query('INSERT into "Cron_distribucion_periodo" ("CdpAni", "CdpPrdId", "CdpEli", "CdpFchRg", "CdpFchAc", "CdpCntId", "CdpNgrId") VALUES (?,?,?,?,?,?,?); '
                , array($anio, $ClpPrdId , FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idContratante, $idNivelGrupo ));
        $idCronogramaLectura = $this->db->insert_id();
        foreach ($cronograma as $codGrupoPredio => $filaCron) {
            $query = $this->db->query('INSERT into "Detalle_cron_distribucion_periodo" ("DdpDiaCg", "DdpFchCal", "DdpFchImp", "DdpDiaImp", "DdpFchDis", "DdpCdpId", "DdpGprId") VALUES (?,?,?,?,?,?,?); '
                    , array($filaCron['diasCrg'], $filaCron['fchCal'], $filaCron['fchImp'], $filaCron['diasImp'], $filaCron['fchDis'], $idCronogramaLectura, $codGrupoPredio));
        }
        $this->db->trans_complete();
        return $query;
    }
    
    /*CronogramaDistribucion_ctrllr -> cronograma_periodo_editar()*/
    public function update_cronograma_periodo($idCronograma, $cronograma) {
        $this->db->trans_start();
        $query = $this->db->query('DELETE FROM "Detalle_cron_distribucion_periodo" '
                . 'WHERE "DdpCdpId" = ?', array($idCronograma));
        foreach ($cronograma as $codCiclo => $filaCron) {
            $query = $this->db->query('INSERT into "Detalle_cron_distribucion_periodo" ("DdpDiaCg", "DdpFchCal", "DdpFchImp", "DdpDiaImp", "DdpFchDis", "DdpCdpId", "DdpGprId") VALUES (?,?,?,?,?,?,?); '
                    , array($filaCron['diasCrg'], $filaCron['fchCal'], $filaCron['fchImp'], $filaCron['diasImp'], $filaCron['fchDis'], $idCronograma, $codCiclo));
        }
        $this->db->trans_complete();
        return $query;
    }
}
