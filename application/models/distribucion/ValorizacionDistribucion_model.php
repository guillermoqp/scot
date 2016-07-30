<?php

class ValorizacionDistribucion_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('distribucion/OTDistribucion_model');
        $this->load->model('distribucion/Distribucion_model');
        $this->load->model('general/SubActividad_model');
        $this->load->model('general/Contrato_model');
        $this->load->model('sistema/Variable_model');
    }

    // valorizacion_ctrllr -> valorizacion_editar
    // valorizacion_ctrllr -> valorizacion_eliminar
    // valorizacion_ctrllr -> valorizacion_detalle
    // valorizacion_cst_ctrllr -> valorizacion_detalle
    public function get_one_valorizacion($idValorizacion) {
        $query = $this->db->query('SELECT * FROM "Valorizacion" '
                . 'WHERE "VlrId" = ?', array($idValorizacion));
        $valorizacion = $query->row_array();
        $valorizacion['VlrFchIn'] = date('d/m/Y', strtotime($valorizacion['VlrFchIn']));
        $valorizacion['VlrFchFn'] = date('d/m/Y', strtotime($valorizacion['VlrFchFn']));
        return $valorizacion;
    }
    
    // valorizacion_ctrllr -> valorizacion_listar
    public function get_all_valorizaciones_x_act($idActividad) {
        $query = $this->db->query('SELECT "Valorizacion".*, "CstRaz" FROM "Valorizacion" JOIN "Contrato" ON "VlrConId" = "ConId" '
                . 'JOIN "Contratista" ON "ConCstId" = "CstId" WHERE "VlrEli" = FALSE AND "VlrActId" = ? ORDER BY "VlrId" DESC', array($idActividad));
        return $query->result_array();
    }
    
    // valorizacion_ctrllr -> valorizacion_nuevo
    public function get_sgte_num($idActividad, $idContrato) {
        $query = $this->db->query('SELECT COUNT("Valorizacion".*) as "Cantidad" FROM "Valorizacion" '
                . 'WHERE "VlrEli" = FALSE AND "VlrActId" = ? AND "VlrConId" = ?', array($idActividad, $idContrato));
        return $query->row_array()['Cantidad'] + 1;
    }

    // valorizacion_ctrllr -> valorizacion_detalle
    public function get_orden_valorizacion($idSubactividad) {
        $query = $this->db->query('SELECT "Orden_trabajo"."OrtId" FROM "Orden_trabajo"
        JOIN "Orden_trabajo_Subactividad" ON "OrtId" = "OtsOrtId"
        WHERE "OtsSacId" = ? ', array($idSubactividad));
        return $query->row_array();
    }
    
    // valorizacion_ctrllr -> valorizacion_detalle
    public function get_cantidad_orden_valorizacion($idOrden) {
        $query = $this->db->query('SELECT DISTINCT "VlrMetPl","VlrMetEj" FROM "Valorizacion" 
        JOIN "Valorizacion_Orden_Subactividad" ON "VloVlrId" = "VlrId"
        JOIN "Orden_trabajo_Subactividad" ON "OtsVloId" = "VloId"
        JOIN "Orden_trabajo" ON "OtsOrtId" = "OrtId"
        WHERE "OrtId" = ? ', array($idOrden));
        return $query->row_array();
    }
    
    // valorizacion_ctrllr -> valorizacion_nuevo
    public function insert_valorizacion($numero, $anio, $mes, $fechaIn, $fechaFn, $idActividad, $idContrato, $idContratante) {
        $this->db->trans_start();
        $query = $this->db->query('INSERT into "Valorizacion" ("VlrNum", "VlrAni", "VlrMes", "VlrFchIn", "VlrFchFn", "VlrEli", '
                . '"VlrFchRg", "VlrFchAc", "VlrActId", "VlrConId", "VlrCntId") VALUES (?,?,?,?,?,?,?,?,?,?,?); '
                , array($numero, $anio, $mes, $fechaIn, $fechaFn, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idActividad, $idContrato, $idContratante));
        $idValorizacion = $this->db->insert_id();
        //ACUMULADOS POR ORDEN
        //VlrMetPl => Metrado planificado
        $totalMetradoPl = 0;
        $totalDescuento = 0;
        //VlrMetEj => Metrado Ejecutado
        $totalMetradoEj = 0;
        $totalMontoEj = 0;
        // OBTENER TODAS LAS ORDENES DEL PERIODO DE VALORACION
        $ordenes = $this->OTDistribucion_model->get_all_orden_x_periodo_facturacion($idActividad, $fechaIn, $fechaFn, $idContratante);
        // OBTENER LAS SUBACTIVIDADES DE LA ACTIVIDAD: DISTRIBUCION CONTINUA, DISPERSA, CON CEDULA, SIN CEDULA
        $subActividades = $this->SubActividad_model->get_subactividad_x_actividad($idActividad);
        foreach ($subActividades as $subActividad) {
            $precioUnitario = $this->Contrato_model->get_precio_x_subactividad($idContrato, $subActividad['SacId']);
            // INSERTAR LA VALORIZACION POR SUBACTIVIDAD
            $idValOrdenSub = $this->insert_valorizacion_orden_subactividad($idValorizacion, $subActividad['SacId'], $precioUnitario, $idContratante);
            $acumMetradoEj = 0;
            $acumMontoEj = 0;
            $tipo;
            
            if ($subActividad['SacCod'] == 'distribucion_continua') {
                //CON DISTRIBUCION CONTINUA
                $tipo = 1;
            } elseif ($subActividad['SacCod'] == 'distribucion_dispersa') {
                //CON DISTRIBUCION DISPERSA
                $tipo = 2;
            } elseif ($subActividad['SacCod'] == 'distribucion_con_cedula') {
                //CON DISTRIBUCION CON CEDULA
                $tipo = 3;
            } elseif ($subActividad['SacCod'] == 'distribucion_sin_cedula') {
                //CON DISTRIBUCION SIN CEDULA
                $tipo = 4;
            }

            foreach ($ordenes as $orden) {

                //Obtención mediante el tipo
                $distribucionRegular = $this->Distribucion_model->get_distribucion_regular_x_orden_x_tipo($orden['OrtId'], $tipo);
                $distribucionControl = $this->Distribucion_model->get_distribucion_control_x_orden_x_tipo($orden['OrtId'], $tipo);
                //$distribucionDispersa = $this->Distribucion_model->get_distribucion_dispersa_x_orden_x_tipo($orden['OrtId'], $tipo);

                $cantDistribucion = count($distribucionRegular) + count($distribucionControl) + count($distribucionDispersa);
                $montoEjecOrden = $cantDistribucion * $precioUnitario;
                $idOrdenSubActividad = $this->OTTomaEstado_model->insert_orden_subactividad($cantDistribucion, $precioUnitario, $montoEjecOrden, $orden['OrtId'], $subActividad['SacId'], $idValOrdenSub);
                $acumMetradoEj += $cantDistribucion;
                $acumMontoEj += $montoEjecOrden;
                $totalMetradoEj += $cantDistribucion;
                $totalMontoEj += $montoEjecOrden;
            }

            $actualizado = $this->update_valorizacion_orden_subactividad($acumMetradoEj, $acumMontoEj, $idValOrdenSub); 
        }
        foreach ($ordenes as $orden) {
            $totalMetradoPl += $this->Distribucion_model->get_cant_distrib_x_orden($orden['OrtId']);
            $totalDescuento += $this->OTTomaEstado_model->get_descuento_x_idOrden($orden['OrtId']);
        }
        //VlrIgvVl => valor del igv
        $varIgv = $this->Variable_model->get_one_variable_cod('igv');
        $valorIgv = $varIgv['VarVal'];
        //VlrIgvNo => monto a restar

        $montoBruto = $totalMontoEj;
        $montoIgv = $montoBruto * $valorIgv;
        $montoNeto = $montoBruto + $montoIgv - $totalDescuento;
        $query = $this->db->query('UPDATE "Valorizacion" SET "VlrMetPl" = ?, "VlrMetEj" = ?, "VlrMonBr" = ?, '
                . '"VlrIgvVl" = ?, "VlrDsc" = ?, "VlrIgvMo" = ?, "VlrMonNt" = ?, "VlrFchAc" = ? WHERE "VlrId" = ? ;'
                , array($totalMetradoPl, $totalMetradoEj, $montoBruto, $valorIgv, $totalDescuento, $montoIgv, $montoNeto, date("Y-m-d H:i:s"), $idValorizacion));
        $this->db->trans_complete();
        return $query;
    }

    // Valorizacion_ctrllr -> valorizacion_editar
    public function update_valorizacion($idValorizacion) {
        $query = $this->db->query('UPDATE "Valorizacion" SET "VloMetEj" = ?, "VloMonEj" = ?, "VloFchAc" = ? WHERE "VloId" = ?'
                , array($metradoEj, $montoEj, date("Y-m-d H:i:s"), $idValOrdenSub));
        return $query;
    }

    // Valorizacion_ctrllr -> valorizacion_eliminar
    public function delete_valorizacion($idValorizacion) {
        $this->db->trans_start();
        $query = $this->db->query('UPDATE "Valorizacion" SET "VlrEli" = TRUE, "VlrFchAc" = ? '
                . 'WHERE "VlrId" = ?', array(date("Y-m-d H:i:s"), $idValorizacion));
        $rows = $this->get_valorizacion_orden_subactividad_x_valorizacion($idValorizacion);
        foreach ($rows as $row) {
            $this->delete_valorizacion_orden_subactividad($row['VloId']);
            $this->OTDistribucion_model->delete_orden_subactividad_x_valorizacion($row['VloId']);
        }
        $this->db->trans_complete();
    }

    // $this -> delete_valorizacion
    // Valorizacion_ctrllr -> valorizacion_detalle
    public function get_valorizacion_orden_subactividad_x_valorizacion($idValorizacion) {
        $query = $this->db->query('SELECT * FROM "Valorizacion_Orden_Subactividad" '
                . 'WHERE "VloEli" = FALSE AND "VloVlrId" = ? ', array($idValorizacion));
        return $query->result_array();
    }
    
    // $this -> insert_valorizacion
    public function insert_valorizacion_orden_subactividad($idValorizacion, $idSubactividad, $precioUnitario, $idContratante) {
        $query = $this->db->query('INSERT into "Valorizacion_Orden_Subactividad" ("VloPreUn", "VloEli", "VloFchRg", "VloFchAc", "VloVlrId", "VloSacId", "VloCntId") VALUES (?,?,?,?,?,?,?); '
                , array($precioUnitario, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idValorizacion, $idSubactividad, $idContratante));
        return $this->db->insert_id();
    }

    // $this -> update_valorizacion
    public function update_valorizacion_orden_subactividad($metradoEj, $montoEj, $idValOrdenSub) {
        $query = $this->db->query('UPDATE "Valorizacion_Orden_Subactividad" SET "VloMetEj" = ?, "VloMonEj" = ?, "VloFchAc" = ? WHERE "VloId" = ? ;'
                , array($metradoEj, $montoEj, date("Y-m-d H:i:s"), $idValOrdenSub));
        return $query;
    }

    // $this -> delete_valorizacion
    public function delete_valorizacion_orden_subactividad($idValSub) {
        $query = $this->db->query('UPDATE "Valorizacion_Orden_Subactividad" SET "VloEli" = TRUE, "VloFchAc" = ? '
                . 'WHERE "VloId" = ?', array(date("Y-m-d H:i:s"), $idValSub));
        return $query;
    }
}
