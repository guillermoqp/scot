<?php

class OTTomaEstado_cst_model extends CI_Model {

    function __construct() 
    {
        parent::__construct();
        $this->load->model('general/Periodo_model');
        $this->load->model('sistema/Parametro_model');
    }

    //OTTomaEstado_cst_ctrllr -> orden_ver()
    public function get_one_orden_trabajo_all_datos($idOrdenTrabajo) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" '
                . 'JOIN "Actividad" ON "OrtActId" = "ActId" '
                . 'JOIN "Contrato" ON "OrtConId" = "ConId" '
                . 'JOIN "Contratante" ON "ConCntId" = "CntId" '
                . 'WHERE "OrtId" = ? AND "OrtEli" = FALSE;', array($idOrdenTrabajo));
        $orden = $query->row_array();
        //AGREGAR LOS CICLOS
        $query2 = $this->db->query('SELECT DISTINCT "GprCod" FROM "Suborden_lectura" JOIN "Grupo_predio" ON "SolGprId" = "GprId" WHERE "SolOrtId" = ? ', array($orden['OrtId']));
        $ciclos = $query2->result_array();
        $orden['ciclos'] = implode(', ', array_column($ciclos, 'GprCod'));
        //agregar el periodo
        $orden['periodo'] = $this->Periodo_model->get_one_periodo($orden['OrtPrdId']);
        //AGREGAR LA CANTIDAD
        $cantidad = $this->Lectura_model->get_cant_lect_x_orden($orden['OrtId']);
        $ordenes['cantidad'] = $cantidad;
        //AGREGAR AVANCE
        $validos = $this->Lectura_model->get_cant_lect_ejec_x_orden($orden['OrtId']);
        $avance = 0;
        if ($cantidad != 0) 
            {
                $avance = round($validos * 100 / $cantidad, 2);
            }
        $ordenes['avance'] = $avance;
        
        return $orden;
    }
    public function get_one_orden_trabajo($idOrdenTrabajo) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" WHERE "OrtId" = ? AND "OrtEli" = FALSE;', array($idOrdenTrabajo));
        return $query->row_array();
    }
    //ConsultasMovil_cst_ctrllr -> obtener_lecturas_en_ejec_x_lecturista_json() 
    public function get_one_orden_trabajo_all_datos_movil($idOrdenTrabajo) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo"
        JOIN "Actividad" ON "OrtActId" = "ActId" 
        JOIN "Contrato" ON "OrtConId" = "ConId" 
        JOIN "Contratante" ON "ConCntId" = "CntId" 
        WHERE "OrtId" = ? AND "OrtEli" = FALSE', array($idOrdenTrabajo));
        return $query->row_array();
    }
    //ConsultasMovil_cst_ctrllr -> obtener_lecturas_en_ejec_x_lecturista_json() 
    public function get_one_suborden_trabajo_all_datos_movil($OrtFchEj, $LecLtaId) {
        $query = $this->db->query('SELECT * FROM "Suborden_lectura" 
        WHERE "SolFchEj" = ? AND "SolLtaId" = ? AND "SolEli" = FALSE', array($OrtFchEj, $LecLtaId));
        return $query->row_array();
    }
    public function get_one_orden_trabajo_x_num($numOrdenTrabajo) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" WHERE "OrtNum" = ? AND "OrtEli" = FALSE;', array($numOrdenTrabajo));
        return $query->row_array();
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
    public function get_ciclo_orden_trabajo($idOrdenTrabajo) {
        $query = $this->db->query('SELECT "Grupo_predio"."GprCod","SolGprId" FROM "Suborden_lectura"
        JOIN "Grupo_predio" ON "SolGprId" = "GprId"
        WHERE "SolOrtId" = ? GROUP By "SolGprId","Grupo_predio"."GprCod"', array($idOrdenTrabajo));
        return $query->row_array();
    }
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
    public function get_all_orden_trabajo_x_contratante($idContratante, $idActividad) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" JOIN "Detalle_parametro" ON "OrtEstId" = "DprId" WHERE "OrtActId" = ? AND "OrtCntId" = ? AND "OrtEli" = FALSE ORDER BY "OrtNum" ASC;', array($idActividad, $idContratante));
        return $query->result_array();
    }
    public function get_all_orden_trabajo_x_contratista($idContratista) {
        $query = $this->db->query('SELECT * FROM "Orden_trabajo" JOIN "Contrato" ON "OrtConId" = "ConId" '
                . 'JOIN "Contratista" ON "ConCstId" = "CstId" '
                . 'WHERE "CstId" = ? AND "OrtEli" = FALSE ORDER BY "OrtNum" ASC;', array($idContratista));
        return $query->result_array();
    }
    //OrdenTrabajo_ctrllr -> orden_listar()
    public function get_subactividad_x_orden($idActividad) {
        $query = $this->db->query('SELECT "SacDes", "OrtId" FROM "Subactividad" JOIN "Orden_trabajo_Subactividad" ON "SacId" = "OtsSacId" 
        JOIN "Orden_trabajo" ON "OtsOrtId" = "OrtId" 
        WHERE "OrtActId" = ? AND "SacEli" = FALSE ORDER BY "OrtNum" ASC;', array($idActividad));
        return $query->result_array();
    }
    // OTTomaEstado_cst_ctrllr -> orden_ver($idOrdenTrabajo)
    public function update_one_subordenlectura_id($SolLtaId,$SolId) {
        $query = $this->db->query('UPDATE "Suborden_lectura" SET "SolLtaId" = ? , "SolFchAc" = ? WHERE "SolId" = ?'
                , array($SolLtaId,date("Y-m-d H:i:s"),$SolId));
        return $query;
    }
    //OTTomaEstado_cst_ctrllr -> orden_digitar_suborden_guardar
    public function update_lectura_digitacion($lecturas, $idOrden, $idLecturista, $idDigitador) {
        foreach ($lecturas as $lectura) {
            $valido = true;
            $valor = $lectura['valor'] == '' ? null : $lectura['valor'];
            $observaciones = $lectura['observaciones'];
            $lectura = $lectura['data'];
            $valor1 = $lectura['LecVal1'];
            $valor2 = $lectura['LecVal2'];
            $valor3 = $lectura['LecVal3'];
            $nroIntento = $lectura['LecInt'];
            if (!is_null($valor) || count($observaciones) > 0) {
                if (!is_null($valor)) {
                    $consumo = $valor - $lectura['LecAnt'];
                    $maximo = $lectura['LecCsmPr'] * 1.80;
                    $minimo = $lectura['LecCsmPr'] * 0.25;
                    if ($lectura['LecVld'] == 'f' || $lectura['LecInt'] !== 3) {
                        if ($lectura['LecInt'] == 0) {
                            if ($consumo > $maximo || ($consumo <= $minimo && $consumo > 10)) {
                                $nroIntento = 1;
                                $valor1 = $valor;
                                $valido = false;
                                $valor = null;
                            }
                        } elseif ($lectura['LecInt'] == 1) {
                            if ($valor !== $valor1) {
                                if ($consumo > $maximo || ($consumo <= $minimo && $consumo > 10)) {
                                    $nroIntento = 2;
                                    $valor2 = $valor;
                                    $valido = false;
                                    $valor = null;
                                }
                            }
                        } elseif ($lectura['LecInt'] == 2) {
                            if (($valor !== $valor1) && ($valor !== $valor2)) {
                                if ($consumo > $maximo || ($consumo <= $minimo && $consumo > 10)) {
                                    $nroIntento = 3;
                                    $valor3 = $valor;
                                    $valido = false;
                                    $valor = null;
                                }
                            }
                        }
                    }
                }
                $query = $this->db->query('UPDATE "Lectura" SET "LecVal" = ?, "LecVal1" = ?, "LecVal2" = ?, "LecVal3" = ?, "LecVld" = ? , "LecInt" = ? ,  "LecFchAc" = ?  , "LecDig" = ?  , "LecDis" = ? WHERE "LecId" = ?'
                        , array($valor, $valor1, $valor2, $valor3, $valido, $nroIntento, date("Y-m-d H:i:s"), $idDigitador, FALSE , $lectura['LecId']));
                foreach ($observaciones as $observacion) {
                    $query = $this->db->query('INSERT INTO "Observaciones_Lectura" ("OleEli", "OleFchRg", "OleFchAc", "OleLecId", "OleObsId") VALUES (?,?,?,?,?); '
                            , array(FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $lectura['LecId'], $observacion));
                }
            }
        }
        return true;
    }
    function save_files_info($files, $idOrdenTrabajo) {
        $this->db->trans_start();
        /*      datos del archivo   */
        $file_data = array();
        foreach ($files as $file) {
            $file_data[] = array
                (
                'ArcNomEc' => $file['file_name'],
                'ArcNomOr' => $file['orig_name'],
                'ArcFchSu' => date('Y-m-d H:i:s'),
                'ArcRutUr' => 'assets/archivos/toma_estado/' . $file['file_name'],
                'ArcRutFi' => $file['full_path'],
                'ArcCom' => true,
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
    public function update_usr_rs_orden_trabajo($idOrdenTrabajo, $OrtUsrRs) {
        $query = $this->db->query('UPDATE "Orden_trabajo" SET "OrtFchAc" = ? , "OrtUsrRs" = ? WHERE "OrtId" = ?;'
                , array(date("Y-m-d H:i:s"), $OrtUsrRs, $idOrdenTrabajo));
        return $query;
    }
    public function update_estado_orden_trabajo($idOrdenTrabajo, $OrtEstId) {
        $query = $this->db->query('UPDATE "Orden_trabajo" SET "OrtFchRs" = ? , "OrtFchAc" = ? , "OrtEstId" = ? WHERE "OrtId" = ?;'
                , array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $OrtEstId, $idOrdenTrabajo));
        return $query;
    }
    public function update_orden_trabajo($archivos, $idOrdenTrabajo) {
        $this->db->trans_start();
        $query = $this->db->query('UPDATE "Orden_trabajo" SET "OrtFchAc" = ?, "OrtFchEr" = ? WHERE "OrtId" = ?;'
                , array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idOrdenTrabajo));
        $retorno = $this->save_files_info($archivos, $idOrdenTrabajo);
        if ($retorno) {
            $this->db->trans_complete();
        }
        return $query;
    }
    public function delete_orden($idOrdenTrabajo) {
        $query = $this->db->query('UPDATE "Orden_trabajo" SET "OrtEli" = TRUE, "OrtFchAc" = ? '
                . 'WHERE "OrtId" = ?', array(date("Y-m-d H:i:s"), $idOrdenTrabajo));
        return $query;
    }
    public function get_cantSubords_por_ordenTrabajo_porDia($idOrdenTrabajo) {
        $idSubCntrlRegular = $this->Parametro_model->get_one_detParam_x_codigo('sol_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT(*) AS cantidad ,"SolFchEj" FROM "Suborden_lectura"
        WHERE "SolEli" = FALSE AND "SolTipId" = ? AND "SolOrtId" = ? GROUP BY "SolFchEj" ORDER BY "SolFchEj" ;', array($idSubCntrlRegular,$idOrdenTrabajo));
        return $query->result_array();
    }
    //OTTomaEstado_cst_ctrllr->orden_ver($idOrdenTrabajo)
    public function get_cantLec_por_suborden_por_ordenTrabajo_porDia($idOrdenTrabajo) {
        $idSubCntrlRegular = $this->Parametro_model->get_one_detParam_x_codigo('sol_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT("Lectura".*) AS cantidad,"SolFchEj" FROM "Lectura"
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId"
        WHERE "SolEli" = FALSE AND "SolTipId" = ? AND "SolOrtId" = ? GROUP BY "SolFchEj" ORDER BY "SolFchEj"', array($idSubCntrlRegular,$idOrdenTrabajo));
        return $query->result_array();
    }
    
    //OTTomaEstado_cst_ctrllr->orden_nuevo_detalle_ciclo($idOrdenTrabajo,$fechaEj)
    public function get_cantLec_por_suborden_por_ordenTrbj_porDia($idOrdenTrabajo,$fecha) {
        $idSubCntrlRegular = $this->Parametro_model->get_one_detParam_x_codigo('sol_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT("Lectura".*) AS cantidad FROM "Lectura"
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId"
        WHERE "SolEli" = FALSE AND "SolTipId" = ? AND "SolOrtId" = ? AND "SolFchEj" = ?;', array($idSubCntrlRegular,$idOrdenTrabajo,$fecha));
        return $query->row_array()['cantidad'];
    }
    public function get_cantSubords_por_ordenTrbj_porDia($idOrdenTrabajo,$fecha) {
        $idSubCntrlRegular = $this->Parametro_model->get_one_detParam_x_codigo('sol_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT(*) AS cantidad FROM "Suborden_lectura"
        WHERE "SolEli" = FALSE AND "SolTipId" = ? AND "SolOrtId" = ? AND "SolFchEj" = ?', array($idSubCntrlRegular,$idOrdenTrabajo,$fecha));
        return $query->row_array()['cantidad'];
    }

    //OTTomaEstado_cst_ctrllr->orden_ver()
    public function get_cantidad_subordenes_x_orden($idOrdenTrabajo)
    {
        $idSubCntrlRegular = $this->Parametro_model->get_one_detParam_x_codigo('sol_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT(*) AS cantidad FROM "Suborden_lectura"
        WHERE "SolEli" = FALSE AND "SolTipId" = ? AND "SolOrtId" = ?', array($idSubCntrlRegular,$idOrdenTrabajo));
        return $query->row_array()['cantidad'];
    }
    
}
