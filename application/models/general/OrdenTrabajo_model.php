<?php

class OrdenTrabajo_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('lectura/Lectura_model');
        $this->load->model('general/Periodo_model');
    }

    public function get_one_orden_trabajo($idOrdenTrabajo) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" WHERE "OrtId" = ? AND "OrtEli" = FALSE;', array($idOrdenTrabajo));
        return $query->row_array();
    }

    public function get_one_orden_trabajo_all_datos($idOrdenTrabajo) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" '
                . 'JOIN "Actividad" ON "OrtActId" = "ActId" '
                . 'JOIN "Contrato" ON "OrtConId" = "ConId" '
                . 'JOIN "Contratante" ON "ConCntId" = "CntId" '
                . 'WHERE "OrtId" = ? AND "OrtEli" = FALSE;', array($idOrdenTrabajo));
        return $query->row_array();
    }

    public function get_one_orden_trabajo_x_num($numOrdenTrabajo) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" WHERE "OrtNum" = ? AND "OrtEli" = FALSE;', array($numOrdenTrabajo));
        return $query->row_array();
    }

    public function get_archivos_x_orden_trabajo($idOrdenTrabajo) {
        $query = $this->db->query('SELECT * FROM "Archivo" JOIN "Orden_trabajo" ON "ArcOrtId" = "OrtId"  '
                . 'WHERE "OrtId" = ? AND "ArcEli" = FALSE;', array($idOrdenTrabajo));
        return $query->result_array();
    }

    public function get_archivos_enviados_x_orden_trabajo($idOrdenTrabajo) {
        $query = $this->db->query('SELECT * FROM "Archivo" JOIN "Orden_trabajo" ON "ArcOrtId" = "OrtId"  '
                . 'WHERE "OrtId" = ? AND "ArcEli" = FALSE AND "ArcCom" = FALSE;', array($idOrdenTrabajo));
        return $query->result_array();
    }

    public function get_archivos_recepcionados_x_orden_trabajo($idOrdenTrabajo) {
        $query = $this->db->query('SELECT * FROM "Archivo" JOIN "Orden_trabajo" ON "ArcOrtId" = "OrtId"  '
                . 'WHERE "OrtId" = ? AND "ArcEli" = FALSE AND "ArcCom" = TRUE;', array($idOrdenTrabajo));
        return $query->result_array();
    }

    public function get_all_orden_trabajo() {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" '
                . 'JOIN "Detalle_parametro" ON "OrtEstId" = "DprId" '
                . 'WHERE "OrtEli" = FALSE ORDER BY "OrtNum" ASC;');
        return $query->result_array();
    }

    public function get_all_orden_trabajo_archivos() {
        $ordenesTrabajo = $this->get_all_orden_trabajo();

        foreach ($ordenesTrabajo as $key => $ordenTrabajo) {
            $archivos = $this->get_archivos_x_ordenTrabajo($ordenTrabajo['OrtId']);

            $ordenesTrabajo[$key]['archivos'] = $archivos;
        }
        return $ordenesTrabajo;
    }

    /* OBTIENE LAS ORDENES DE TRABAJO POR ID DE USUARIO Y EL ID DEL ESTADO DE LA ORDEN  */

    public function get_all_orden_trabajo_x_usuario($idUsuario, $OrtEstId) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" '
                . 'JOIN "Actividad" ON "OrtActId" = "ActId"'
                . 'JOIN "Contrato" ON "OrtConId" = "ConId" '
                . 'JOIN "Contratante" ON "ConCntId" = "CntId" '
                . 'JOIN "Usuario" ON "UsrCntId" = "CntId" '
                . 'JOIN "Grupo_permiso" ON "GpeGpeId" = (SELECT "GpeId" FROM "Grupo_permiso" WHERE "GpeVal" = "ActCod") AND "GpeDes" = \'Ordenes de Trabajo\' '
                . 'WHERE "UsrId" = ? AND "OrtEstId" = ? AND "OrtEli" = FALSE ORDER BY "OrtNum" ASC;', array($idUsuario, $OrtEstId));
        return $query->result_array();
    }
    
    public function get_all_orden_trabajo_ejec($idUsuario, $OrtEstId) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" '
                . 'JOIN "Actividad" ON "OrtActId" = "ActId"'
                . 'JOIN "Contrato" ON "OrtConId" = "ConId" '
                . 'JOIN "Contratante" ON "ConCntId" = "CntId" '
                . 'JOIN "Usuario" ON "UsrCntId" = "CntId" '
                . 'JOIN "Grupo_permiso" ON "GpeGpeId" = (SELECT "GpeId" FROM "Grupo_permiso" WHERE "GpeVal" = "ActCod") AND "GpeDes" = \'Ordenes de Trabajo\' '
                . 'WHERE "UsrId" = ? AND "OrtEstId" = ? AND "OrtEli" = FALSE ORDER BY "OrtNum" ASC;', array($idUsuario, $OrtEstId));
        $ordenes = $query->result_array();
        foreach ($ordenes as $key => $orden) {
            //AGREGAR LA CANTIDAD
            $idTipoRegular = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
            $ordenes[$key]['cantidad']['lec_reg'] = $this->Lectura_model->get_cant_lect_x_orden_x_tipo($orden['OrtId'] , $idTipoRegular);
            //AGREGAR AVANCE
            $validos = $this->Lectura_model->get_cant_lect_ejec_x_orden($orden['OrtId']);
            $avance = 0;
            if ($ordenes[$key]['cantidad']['lec_reg'] != 0) {
                $avance = round($validos * 100 / $ordenes[$key]['cantidad']['lec_reg'], 2);
            }
            $ordenes[$key]['avance'] = $avance;
        }
        return $ordenes;
    }

    //OrdenTrabajo_ctrllr -> orden_listar()
    public function get_all_orden_trabajo_x_contratante($idContratante, $idActividad) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" JOIN "Detalle_parametro" ON "OrtEstId" = "DprId" WHERE "OrtActId" = ? AND "OrtCntId" = ? AND "OrtEli" = FALSE ORDER BY "OrtNum" ASC;', array($idActividad, $idContratante));
        return $query->result_array();
    }

    //OrdenTrabajo_ctrllr -> orden_listar()
    public function get_subactividad_x_orden($idActividad) {
        $query = $this->db->query('SELECT "SacDes", "OrtId" FROM "Subactividad" JOIN "Orden_trabajo_Subactividad" ON "SacId" = "OtsSacId" 
        JOIN "Orden_trabajo" ON "OtsOrtId" = "OrtId" 
        WHERE "OrtActId" = ? AND "SacEli" = FALSE ORDER BY "OrtNum" ASC;', array($idActividad));
        return $query->result_array();
    }
    
    // OrdenTrabajo_ctrllr -> orden_listar
    public function get_all_orden_trabajo_temp($idContratante) {
        $query = $this->db->query('SELECT * FROM "Detalle_cron_lectura_periodo" '
                . 'JOIN "Cron_lectura_periodo" ON "DlpClpId" = "ClpId" '
                . 'JOIN "Grupo_predio" ON "DlpGprId" = "GprId" '
                . 'WHERE ("DlpFchEn" = CURRENT_DATE OR "DlpFchEn" = (CURRENT_DATE - 1) OR "DlpFchEn" = (CURRENT_DATE + 1)) AND "ClpCntId" = ? ORDER BY "DlpFchEn" ASC;', array($idContratante));
        $rows = $query->result_array();
        $ordenes = array();
        foreach ($rows as $key => $row) {
            $fechaEntrega = date('-m-Y', strtotime($row['DlmFchEn']));
            $this->load->library('utilitario_cls');
            $parts = explode('-', $fechaEntrega);
            $nombreMes = $this->utilitario_cls->nombreMes(intval($parts[1]) + 1);
            $fechaToma = date('d-m-Y', strtotime($row['DlmFchTm']));
            //$ordenes[$key] = array('OrtNum'=>$fechaEntrega,'OrtDes'=>$nombreMes.' / '.$parts[2], 'OrtFchEj'=>$fechaToma);
            $ordenes[$row['DlmFchTm']][$row['GprId']] = array('OrtNum' => $fechaEntrega, 'OrtDes' => $nombreMes . ' / ' . $parts[2], 'OrtFchEj' => $fechaToma, 'DlmId' => $row['DlmId']);
        }
        return $ordenes;
    }

    public function get_all_orden_trabajo_x_contratista($idContratista) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" JOIN "Contrato" ON "OrtConId" = "ConId" '
                . 'JOIN "Contratista" ON "ConCstId" = "CstId" '
                . 'WHERE "CstId" = ? AND "OrtEli" = FALSE ORDER BY "OrtNum" ASC;', array($idContratista));
        return $query->result_array();
    }

    function save_files_info($files, $idOrdenTrabajo) {
        $this->db->trans_start();
        /*      datos del archivo   */
        $file_data = array();
        foreach ($files as $file) {
            $file_data[] = array
                (
                'ArcNomOr' => $file['orig_name'],
                'ArcNomEc' => $file['file_name'],
                'ArcFchSu' => date('Y-m-d H:i:s'),
                'ArcRutUr' => 'assets/archivos/toma_estado/' . $file['file_name'],
                'ArcRutFi' => $file['full_path'],
                'ArcCom' => false,
                'ArcEli' => false,
                'ArcFchRg' => date('Y-m-d H:i:s'),
                'ArcFchAc' => date('Y-m-d H:i:s'),
                'ArcOrtId' => $idOrdenTrabajo
            );
        }
        /*      Insertar los datos del Archivo      */
        $this->db->insert_batch('Archivo', $file_data);
        /*  Completar la Transaccion    */
        $this->db->trans_complete();
        /*      Chekcear el Estado de la Transaccion        */
        if ($this->db->trans_status() === FALSE) {
            foreach ($files as $file) {
                $file_path = $file['full_path'];
                /*      Eliminar el Archivo del destino      */
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            /*      rollback transaction    */
            $this->db->trans_rollback();
            return FALSE;
        } else {
            /*      commit the transaction      */
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function insert_orden_trabajo($OrtNum, $OrtDes, $OrtObs, $OrtFchEj, $OrtFchEm, $OrtConId, $OrtEstId, $OrtActId, $OrtUsrEn, $OrtCntId, $archivos, $ordenMetradoPl) {
        $idOrdenTrabajo = false;
        $this->db->trans_start();
        $query = $this->db->query('INSERT INTO "Orden_trabajo"("OrtNum","OrtDes","OrtObs","OrtFchEj","OrtFchEn","OrtFchEm","OrtEli","OrtFchRg","OrtFchAc","OrtEstId","OrtConId","OrtActId","OrtUsrEn","OrtCntId") '
                . 'VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?);', array(strval($OrtNum), strval($OrtDes), strval($OrtObs), $OrtFchEj, date("Y-m-d H:i:s"), $OrtFchEm, false, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), intval($OrtEstId), intval($OrtConId), intval($OrtActId), intval($OrtUsrEn), intval($OrtCntId)));
        $idOrdenTrabajo = $this->db->insert_id();
        $retorno = $this->save_files_info($archivos, $idOrdenTrabajo);
        if ($retorno) {
            foreach ($ordenMetradoPl as $metrado) {
                $idSubActividad = $metrado['idSubActividad'];
                $metrado = $metrado['metradoPl'];
                $query2 = $this->db->query('INSERT INTO "Orden_trabajo_Subactividad" ("OtsMetPl", "OtsEli" , "OtsFchRg" , "OtsFchAc" , "OtsOrtId" , "OtsSacId" )'
                        . ' VALUES (?,?,?,?,?,?);'
                        , array($metrado, false, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idOrdenTrabajo, $idSubActividad));
                $id = $this->db->insert_id();
            }
            $this->db->trans_complete();
        }
        return $query2;
    }

    public function get_detalle_orden_trabajo($idOrden)
    {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo_Subactividad" 
            JOIN "Subactividad" on "OtsSacId"="SacId"
            WHERE "OtsOrtId" = ?', array($idOrden));
        return $query->result_array();
    }
    
    public function update_orden_trabajo($OrtNum, $OrtDes, $OrtFchEm, $OrtUsrEn, $archivos, $idOrdenTrabajo, $ordenMetradoPl) {
        $this->db->trans_start();
        $query = $this->db->query('UPDATE "Orden_trabajo" SET "OrtNum" = ?, "OrtDes" = ?, "OrtFchEm" = ?, "OrtFchAc" = ?, "OrtUsrEn" = ? WHERE "OrtId" = ?;'
                , array(strval($OrtNum), strval($OrtDes), $OrtFchEm, date("Y-m-d H:i:s"), intval($OrtUsrEn), intval($idOrdenTrabajo)));
        if (empty($archivos)) {
            foreach ($ordenMetradoPl as $metrado) {
                $idSubActividad = $metrado['idSubActividad'];
                $metrado = $metrado['metradoPl'];
                $query2 = $this->db->query('UPDATE "Orden_trabajo_Subactividad" SET "OtsMetPl" = ?, "OtsFchAc" = ? WHERE "OtsOrtId" = ? AND "OtsSacId" = ? ;'
                        , array($metrado, date("Y-m-d H:i:s"), $idOrdenTrabajo, $idSubActividad));
                }
            $this->db->trans_complete();
        } else {
            $retorno = $this->save_files_info($archivos, intval($idOrdenTrabajo));
            if ($retorno) {
                foreach ($ordenMetradoPl as $metrado) {
                $idSubActividad = $metrado['idSubActividad'];
                $metrado = $metrado['metradoPl'];
                $query2 = $this->db->query('UPDATE "Orden_trabajo_Subactividad" SET "OtsMetPl" = ?, "OtsFchAc" = ? WHERE "OtsOrtId" = ? AND "OtsSacId" = ? ;'
                        , array($metrado, date("Y-m-d H:i:s"), $idOrdenTrabajo, $idSubActividad));
                }
                $this->db->trans_complete();
             }
            }
        return $query2;
    }

    public function delete_orden($idOrdenTrabajo) {
        $query = $this->db->query('UPDATE "Orden_trabajo" SET "OrtEli" = TRUE, "OrtFchAc" = ? '
                . 'WHERE "OrtId" = ?', array(date("Y-m-d H:i:s"), $idOrdenTrabajo));
        return $query;
    }

    public function delete_archivo_orden($idOrdenTrabajo, $idArchivo) {
        $query = $this->db->query('UPDATE "Archivo" SET "ArcEli" = TRUE, "ArcFchAc" = ? '
                . 'WHERE "ArcId" = ? AND "ArcOrtId" = ?;', array(date("Y-m-d H:i:s"), $idArchivo, $idOrdenTrabajo));
        return $query;
    }
    
// <editor-fold defaultstate="collapsed" desc="VALORIZACION">
    //OrdenValorizacion_model -> insert_valorizacion
    public function insert_Orden_trabajo_Subactividad($idOrt, $ordenMetradoPl) {
        foreach ($ordenMetradoPl as $metrado) {
            $idSubActividad = $metrado['idSubActividad'];
            $metrado = $metrado['metradoPl'];
            $query = $this->db->query('INSERT INTO "Orden_trabajo_Subactividad" ("OtsMetPl", "OtsEli" , "OtsFchRg" , "OtsFchAc" , "OtsOrtId" , "OtsSacId" )'
                    . ' VALUES (?,?,?,?,?,?);'
                    , array($metrado, false, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idOrt, $idSubActividad));
            $idDetLiqu = $this->db->insert_id();
        }
        return $query;
    }
    
    public function insert_orden_subactividad($cantEj, $precioUni, $montoEj, $idOrden, $idSubActividad, $idValOrdenSub) {
        $query = $this->db->query('INSERT INTO "Orden_trabajo_Subactividad" ("OtsMetEj", "OtsPreUn", "OtsMonEj", "OtsEli", "OtsFchRg" , "OtsFchAc", "OtsOrtId" , "OtsSacId", "OtsVloId" )'
                . ' VALUES (?,?,?,?,?,?,?,?,?);'
                , array($cantEj, $precioUni, $montoEj, false, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idOrden, $idSubActividad, $idValOrdenSub));
        return $this->db->insert_id();
    }

    //OrdenValorizacion_model -> delete_valorizacion
    public function delete_orden_subactividad_x_valorizacion($valSub) {
        $query = $this->db->query('UPDATE "Orden_trabajo_Subactividad" SET "OtsEli" = TRUE, "OtsFchAc" = ? '
                . 'WHERE "OtsVloId" = ?', array(date("Y-m-d H:i:s"), $valSub));
        return $query;
    }

    //OrdenValorizacion_model -> insert_valorizacion
    //OrdenValorizacion_model -> valorizacion_orden
    public function get_descuento_x_idOrden($idOrdenTrabajo) {
        $query = $this->db->query('SELECT SUM("PnzMon") as "Descuento" '
                . 'FROM "Penalizacion" WHERE "PnzOrtId" = ?;', array($idOrdenTrabajo));
        return $query->row_array()['Descuento'];
    }

    //OrdenValorizacion_model -> insert_valorizacion
    public function get_all_orden_x_periodo_facturacion($idActividad, $fechaIn, $fechaFn, $idContratante) {
        $query = $this->db->query('SELECT DISTINCT or1.* FROM "Orden_trabajo" AS or1 WHERE or1."OrtActId" = ? '
                . 'AND or1."OrtFchEj" BETWEEN ? AND ? AND or1."OrtId" NOT IN (SELECT DISTINCT or2."OrtId" '
                . 'FROM "Orden_trabajo_Subactividad" JOIN "Valorizacion_Orden_Subactividad" ON "OtsVloId" ="VloId" '
                . 'JOIN "Valorizacion" ON "VloVlrId" = "VlrId" JOIN "Orden_trabajo" AS or2 ON or2."OrtId" = "OtsOrtId" '
                . 'WHERE or2."OrtActId" = ? AND or2."OrtFchEj" BETWEEN ? AND ? AND "VlrEli" = FALSE) ', array($idActividad, $fechaIn, $fechaFn,
            $idActividad, $fechaIn, $fechaFn));
        return $query->result_array();
    }

    //OrdenValorizacion_ctrllr -> valorizacion_detalle
    public function get_all_orden_x_valorizacion($idValorizacion) {
        $query = $this->db->query('SELECT DISTINCT "Orden_trabajo".* FROM "Orden_trabajo", "Orden_trabajo_Subactividad",' .
                '"Valorizacion", "Valorizacion_Orden_Subactividad" WHERE "OtsOrtId" = "OrtId" AND "OtsVloId" = "VloId"' .
                ' AND "VloVlrId" = "VlrId" AND "VlrId" = ? AND "VlrEli" = false', array($idValorizacion));
        $ordenes = $query->result_array();
        foreach ($ordenes as $key => $orden) {
            $ordenes[$key]['cantPl'] = $this->Lectura_model->get_cant_lect_x_orden($orden['OrtId']);
            $ordenes[$key]['cantEj'] = $this->Lectura_model->get_cant_lect_ejec_x_orden($orden['OrtId']);
            $ordenes[$key]['subTotal'] = $this->get_monto_ejec_x_orden($orden['OrtId']);
            $ordenes[$key]['descuento'] = $this->get_descuento_x_idOrden($orden['OrtId']);
            $ordenes[$key]['total'] = $ordenes[$key]['subTotal'] - $ordenes[$key]['descuento'];
        }
        return $ordenes;
    }

    //$this -> get_all_orden_x_valorizacion
    public function get_monto_ejec_x_orden($idOrden) {
        $query = $this->db->query('SELECT SUM("OtsMonEj") as "monto" '
                . 'FROM "Orden_trabajo_Subactividad" WHERE "OtsOrtId" = ? AND "OtsEli" = FALSE;', array($idOrden));
        return $query->row_array()['monto'];
    }
    
    //OrdenValorizacion_ctrllr -> valorizacion_ctrllr 
    public function get_orden_subactividad_x_orden($idOrden) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo_Subactividad" JOIN "Subactividad" ON "OtsSacId" = "SacId"'
                . ' WHERE "OtsOrtId" = ? AND "OtsEli" = FALSE;', array($idOrden));
        return $query->result_array();
    }
    
// </editor-fold>
}
