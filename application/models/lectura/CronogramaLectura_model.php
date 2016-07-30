<?php

class CronogramaLectura_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('general/Actividad_model');
        $this->load->model('catastro/Catastro_model');
    }

    //CRONOGRAMA ANUAL

    public function get_one_cronograma_anual($idCronograma) {
        $query = $this->db->query('SELECT * FROM "Cron_lectura_anual" JOIN "Nivel_grupo" ON "ClaNgrId" = "NgrId" WHERE "ClaId" = ? AND "ClaEli" = FALSE', array($idCronograma));
        $cronograma = $query->row_array();
        $cronograma['detalle'] = $this->get_detalle_cronograma_anual_x_cronograma($cronograma['ClaId']);
        return $cronograma;
    }

    public function get_cronograma_anual_actual($idContratante) {
        $anio=  date('Y');
        $query = $this->db->query('SELECT * FROM "Cron_lectura_anual" '
                . 'WHERE "ClaEli" = FALSE AND "ClaAni" = ? AND "ClaVig" = TRUE AND "ClaCntId" = ? ORDER BY "ClaVer" DESC', array($anio,$idContratante));
        return $query->row_array();
    }

    public function get_detalle_cronograma_anual_x_cronograma($idCronogramaAnual) 
    {        
        $query = $this->db->query('SELECT "Detalle_cron_lectura_anual".*,"Periodo"."PrdOrd","Periodo"."PrdAni","Periodo"."PrdCod" '
                . 'FROM "Detalle_cron_lectura_anual" JOIN "Periodo" ON "DlaPrdId" = "PrdId" '
                . 'WHERE "DlaEli" = FALSE AND "DlaClaId" = ?', array($idCronogramaAnual));
        $rows = $query->result_array();
        $ciclos = array();
        foreach ($rows as $key => $row) 
        {
            $ciclos[$row['DlaGprId']][$row['PrdOrd']] = $row['DlaDia'];
        }
        return $ciclos;
    }

    public function get_all_cronograma_anual($idContratante) {
        $query = $this->db->query('SELECT * FROM "Cron_lectura_anual" JOIN "Nivel_grupo" ON "ClaNgrId" = "NgrId" '
                . 'WHERE "ClaEli" = FALSE AND "ClaCntId" = ? ORDER BY "ClaAni" DESC, "ClaVer" DESC', array($idContratante));
        return $query->result_array();
    }
    
    public function get_all_cronograma_anual_x_anio($idContratante,$anio) {
        $query = $this->db->query('SELECT * FROM "Cron_lectura_anual" JOIN "Nivel_grupo" ON "ClaNgrId" = "NgrId" 
        WHERE "ClaEli" = FALSE AND "ClaCntId" = ? AND "ClaAni" = ? ORDER BY "ClaAni" DESC, "ClaVer" DESC', array($idContratante,$anio));
        return $query->result_array();
    }

    public function get_anios($idContratante) {
        $query = $this->db->query('SELECT DISTINCT "ClaAni" FROM "Cron_lectura_anual" '
                . 'WHERE "ClaCntId" = ? AND "ClaEli" = FALSE ORDER BY "ClaAni" DESC', array($idContratante));
        $anios = $query->result_array();
        return array_column($anios, 'ClaAni');
    }
    
    // CronogramaLectura_ctrllr -> cronograma_anual_editar
    public function get_periodos_x_cronograma_anual($idCronogramaAnual) {
        $query = $this->db->query('SELECT DISTINCT "PrdOrd","PrdCod" FROM "Cron_lectura_anual"
        JOIN "Detalle_cron_lectura_anual" ON "DlaClaId" = "ClaId"
        JOIN "Periodo" ON "DlaPrdId" = "PrdId"
        WHERE "ClaId" = ? AND "ClaEli" = FALSE ORDER BY "PrdOrd"', array($idCronogramaAnual));
        return $query->result_array();
    }

    // CronogramaLectura_ctrllr -> cronograma_anual_nuevo
    public function get_ultima_lectura($periodo) {
        if (!is_null($periodo)) {
            $query = $this->db->query('SELECT * FROM "Detalle_cron_lectura_anual" '
                    . 'JOIN "Cron_lectura_anual" ON "DlaClaId" = "ClaId" JOIN "Periodo" ON "DlaPrdId" = "PrdId" '
                    . 'WHERE "ClaEli" = FALSE AND "PrdId" = ? ORDER BY "ClaVer" ASC', array($periodo['PrdId']));
            $rows = $query->result_array();
            foreach ($rows as $key => $row) {
                $rows[$key]['DlaFchTm'] = date('d/m/Y', strtotime($row['DlaFchTm']));
            }

            return array_column($rows, 'DlaFchTm', 'DlaGprId');
        } else {
            return NULL;
        }
    }

    //CronogramaLectura_ctrllr=>cronograma_mensual_editar
    public function get_lectura_anual_ciclos($anio, $mes, $idContratante) {
        $query = $this->db->query('SELECT * FROM "Detalle_cron_lectura_anual" 
            JOIN "Cron_lectura_anual" ON "DlaClaId" = "ClaId" 
            JOIN "Periodo" ON "DlaPrdId" = "PrdId"
            WHERE "ClaEli" = FALSE AND "ClaCntId"= ? AND "ClaAni" = ? AND "PrdOrd" = ?;', array($idContratante, $anio, $mes));
        $rows = $query->result_array();
        foreach ($rows as $key => $row) {
            $fechaToma = date('d/m/Y', strtotime($row['DlaFchTm']));
            $fechaAnt = date('d/m/Y', strtotime('- ' . $row['DlaDia'] . ' day', strtotime($row['DlaFchTm'])));
            $fechaEntrega = date('d/m/Y', strtotime('- 1 day', strtotime($row['DlaFchTm'])));
            $fechaConsistencia = date('d/m/Y', strtotime('+ 1 day', strtotime($row['DlaFchTm'])));
            $rows[$key]['valores'] = array('DlaFchTm' => $fechaToma, 'DlaDia' => $row['DlaDia'], 'AuxFchAn' => $fechaAnt,
                'AuxFchEn' => $fechaEntrega, 'AuxFchCo' => $fechaConsistencia);
        }

        return array_column($rows, 'valores', 'DlaGprId');
    }

    public function get_ultima_lectura_x_cronograma($idCronograma) {
//        $query = $this->db->query('SELECT ("DlaFchTm" - interval \'1\' day * "DlaDia") as "DlaFchTm", "DlaGprId" FROM "Detalle_cronograma_lectura_anual" '
//                . 'JOIN "Cronograma_lectura_anual" ON "DlaClaId" = "ClaId" '
//                . 'WHERE "ClaEli" = FALSE AND "ClaCroId" = ? AND "DlaMes" = 1', array($idCronograma));
        $query = $this->db->query('SELECT * FROM "Detalle_cron_lectura_anual" 
                    JOIN "Cron_lectura_anual" ON "DlaClaId" = "ClaId" 
                    JOIN "Periodo" ON "DlaPrdId" = "PrdId"
                    WHERE "ClaEli" = FALSE AND "ClaId" = ? AND "PrdOrd" = 1', array($idCronograma));
        $rows = $query->result_array();
        foreach ($rows as $key => $row) {
            $rows[$key]['AuxFchAn'] = date('d/m/Y', strtotime('- ' . $row['DlaDia'] . ' day', strtotime($row['DlaFchTm'])));
        }
        return array_column($rows, 'AuxFchAn', 'DlaGprId');
    }

    // CronogramaLectura_ctrllr -> cronograma_anual_nuevo
    public function insert_cronograma_anual($anio, $cronograma, $periodos, $aprobado, $rutaDoc, $idNivel, $idContratante, $idActividad) {
        
        $version = $this->get_cronAnual_version($anio, $idContratante);
        
        $vigente = ($aprobado && $anio == date('Y'));
        $this->db->trans_start();
        $periodosTemp = array();
        /* CAMBIO AQUI  : POR ENVIO DE ARRAY DE ID DE PERIODOS */
        // periodos
        /*
        foreach ($periodos as $key => $periodo) {
            $query = $this->db->query('INSERT into "Periodo" ("PrdOrd", "PrdAni", "PrdCod", "PrdEli", "PrdFchRg", "PrdFchAc", "PrdActId" , "PrdCntId") VALUES (?,?,?,?,?,?,?,?); '
                    , array($key, $anio, $periodo, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idActividad , $idContratante));
            $periodosTemp[$key] = $this->db->insert_id();
        }
        */
        $periodosTemp = $periodos;
        $query = $this->db->query('INSERT into "Cron_lectura_anual" ("ClaAni", "ClaVer", "ClaVig", "ClaApr", "ClaDocAp", "ClaEli", "ClaFchRg", "ClaFchAc", "ClaNgrId", "ClaCntId") VALUES (?,?,?,?,?,?,?,?,?,?); '
                , array($anio, $version+1, $vigente, $aprobado, $rutaDoc, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idNivel, $idContratante));
        $idCronogramaLectura = $this->db->insert_id();
        foreach ($cronograma as $idGrupo => $filaCron) {
            for ($i = 1; $i <= count($periodos); $i++) {
                $query = $this->db->query('INSERT into "Detalle_cron_lectura_anual" ("DlaPrdId", "DlaFchTm", "DlaDia", "DlaEli", "DlaFchRg", "DlaFchAc", "DlaGprId", "DlaClaId") VALUES (?,?,?,?,?,?,?,?); '
                        , array($periodosTemp[$i], $filaCron[$i]['fecha'], $filaCron[$i]['dias'], FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idGrupo, $idCronogramaLectura));
            }
        }
        $this->db->trans_complete();

        return $query;
    }

    public function get_all_detalle_cronograma_anual_x_cronograma($idCronogramaAnual) 
    {        
        $query = $this->db->query('SELECT "Detalle_cron_lectura_anual".*,"Periodo"."PrdOrd","Periodo"."PrdAni","Periodo"."PrdCod" '
                . 'FROM "Detalle_cron_lectura_anual" JOIN "Periodo" ON "DlaPrdId" = "PrdId" '
                . 'WHERE "DlaEli" = FALSE AND "DlaClaId" = ?', array($idCronogramaAnual));
        $rows = $query->result_array();
        $ciclos = array();
        foreach ($rows as $key => $row) 
        {
            $ciclos[$row['DlaGprId']][$row['PrdOrd']]['Id'] = $row['DlaId'];
            $ciclos[$row['DlaGprId']][$row['PrdOrd']]['dias'] = $row['DlaDia'];
            $ciclos[$row['DlaGprId']][$row['PrdOrd']]['fecha'] = $row['DlaFchTm'];
        }
        return $ciclos;
    }
   
    // CronogramaLectura_ctrllr -> cronograma_anual_editar
    public function update_cronograma_anual(  $detalleCronograma , $cronogramaEdit , $aprobado, $anio, $idCronograma , $idContratante) 
    {
        $vigente = ($aprobado && $anio == date('Y'));
        $this->db->trans_start();
        $version = $this->get_cronAnual_version($anio, $idContratante);
        $query = $this->db->query('UPDATE "Cron_lectura_anual" SET "ClaVer" = ?, "ClaVig" = ?, "ClaFchAc" = ? WHERE "ClaId" = ?'
                , array($version+1, $vigente, date("Y-m-d H:i:s"), $idCronograma ));
        $periodos = $this->get_periodos_x_cronograma_anual($idCronograma);
        foreach ($cronogramaEdit as $idGrupo => $filaCron) {
            for ($i = 1; $i <= count($periodos); $i++) {
                $query = $this->db->query('UPDATE "Detalle_cron_lectura_anual" SET "DlaFchTm" = ?, "DlaDia" = ? , "DlaFchAc" = ? WHERE "DlaId" = ?'
                        , array( $filaCron[$i]['fecha'], $filaCron[$i]['dias'], date("Y-m-d H:i:s"), $detalleCronograma[$idGrupo][$i]['Id'] ));
            }
        }
        $this->db->trans_complete();
        return $query;
    }
    

    public function get_cronAnual_version($anio, $idContratante) {
        $query = $this->db->query('SELECT "ClaVer" FROM "Cron_lectura_anual" WHERE "ClaEli" = FALSE AND "ClaAni" = ? AND "ClaCntId" = ? ORDER BY "ClaVer" DESC', array($anio, $idContratante));
        $version = $query->row_array();
        $version = is_null($version) ? 1.00 : ($version['ClaVer'] + 1);
        return $version;
    }

    
    //CRONOGRAMA PERIODO
    //CronogramaLectura_ctrllr=>cronograma_periodo_editar
    public function get_one_cronograma_periodo($idCronograma) {
        $query = $this->db->query('SELECT * FROM "Cron_lectura_periodo" '
                . 'WHERE "ClpId" = ? AND "ClpEli" = FALSE ', array($idCronograma));
        $cronograma = $query->row_array();
        $cronograma['detalle'] = $this->get_detalle_conograma_periodol_x_cronograma($cronograma['ClpId']);
        return $cronograma;
    }
    
    public function get_cronograma_periodo_actual($idContratante,$periodo) {
        $anio=  date('Y');
        $query = $this->db->query('SELECT * FROM "Cron_lectura_periodo" 
        WHERE "ClpEli" = FALSE AND "ClpAni" = ? AND "ClpCntId" = ? AND "ClpPrdId" = ?', array($anio,$idContratante,$periodo));
        return $query->row_array();
    }
    
    public function get_one_detalle_cronograma_periodo($idDetalle) {
        $query = $this->db->query('SELECT * FROM "Detalle_cron_lectura_periodo" '
                . 'JOIN "Cron_lectura_periodo" ON "DlpClpId" = "ClpId" '
                . 'JOIN "Grupo_predio" ON "DlpGprId" = "GprId" '
                . 'JOIN "Nivel_grupo" ON "ClpNgrId" = "NgrId" '
                . 'WHERE "DlpId" = ?', array($idDetalle));
        $row = $query->row_array();
        $this->load->library('utilitario_cls');
        if ($row) {
//            $row['mes'] = $this->utilitario_cls->nombreMes(intval($row['PrdOrd']));
//            $row['DlpFchEn'] = date('d/m/Y', strtotime($row['DlpFchEn']));
//            $row['DlpFchTm'] = date('d/m/Y', strtotime($row['DlpFchTm']));
//            $row['DlpFchCo'] = date('d/m/Y', strtotime($row['DlpFchCo']));
//            $ciclos[$row['DlpCicId']] = $row;
        }
        return $row;
    }

    //CronogramaLectura_model=>get_one_cronograma_periodo
    public function get_detalle_conograma_periodol_x_cronograma($idCronogramaMensual) {
        $query = $this->db->query('SELECT * FROM "Detalle_cron_lectura_periodo" 
        WHERE "DlpClpId" = ? ORDER BY "DlpGprId" ASC, "DlpFchTm" ASC', array($idCronogramaMensual));
        $rows = $query->result_array();
        $ciclos = array();
        foreach ($rows as $key => $row) {
            $row['DlpFchEn'] = date('d/m/Y', strtotime($row['DlpFchEn']));
            $row['DlpFchTm'] = date('d/m/Y', strtotime($row['DlpFchTm']));
            $row['DlpFchCo'] = date('d/m/Y', strtotime($row['DlpFchCo']));
            $ciclos[$row['DlpGprId']] = $row;
        }
        return $ciclos;
    }

    public function get_all_cronograma_periodo($idContratante) {
        $query = $this->db->query('SELECT * FROM "Cron_lectura_periodo" 
        JOIN "Periodo" ON "ClpPrdId" = "PrdId"
        JOIN "Nivel_grupo" ON "ClpNgrId" = "NgrId"
        WHERE "ClpEli" = FALSE AND "ClpCntId" = ? 
        ORDER BY "ClpAni" DESC, "PrdOrd" DESC', array($idContratante));
        $rows = $query->result_array();
        $anios = array();
        foreach ($rows as $key => $row) {
            $anios[$row['ClpAni']][$key] = $row;
        }
        return $anios;
    }

    public function insert_cronograma_periodo($anio, $ClpPrdId , $cronograma, $idContratante , $idNivelGrupo) {
        $this->db->trans_start();
        $query = $this->db->query('INSERT into "Cron_lectura_periodo" ("ClpAni", "ClpPrdId", "ClpEli", "ClpFchRg", "ClpFchAc", "ClpCntId", "ClpNgrId") VALUES (?,?,?,?,?,?,?); '
                , array($anio, $ClpPrdId , FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idContratante, $idNivelGrupo ));
        $idCronogramaLectura = $this->db->insert_id();
        foreach ($cronograma as $codGrupoPredio => $filaCron) {
            $query = $this->db->query('INSERT into "Detalle_cron_lectura_periodo" ("DlpFchEn", "DlpFchTm", "DlpDiaTm", "DlpFchCo", "DlpDiaCo", "DlpClpId", "DlpGprId") VALUES (?,?,?,?,?,?,?); '
                    , array($filaCron['fechaEntrega'], $filaCron['fechaToma'], $filaCron['diasToma'], $filaCron['fechaCons'], $filaCron['diasCons'], $idCronogramaLectura, $codGrupoPredio));
        }
        $this->db->trans_complete();
        return $query;
    }

    public function update_cronograma_periodo($idCronograma, $cronograma) {
        $this->db->trans_start();
        $query = $this->db->query('DELETE FROM "Detalle_cron_lectura_periodo" '
                . 'WHERE "DlpClpId" = ?', array($idCronograma));
        foreach ($cronograma as $codCiclo => $filaCron) {
            $query = $this->db->query('INSERT into "Detalle_cron_lectura_periodo" ("DlpFchEn", "DlpFchTm", "DlpDiaTm", "DlpFchCo", "DlpDiaCo", "DlpClpId", "DlpGprId") VALUES (?,?,?,?,?,?,?); '
                    , array($filaCron['fechaEntrega'], $filaCron['fechaToma'], $filaCron['diasToma'], $filaCron['fechaCons'], $filaCron['diasCons'], $idCronograma, $codCiclo));
        }
        $this->db->trans_complete();
        return $query;
    }

}
