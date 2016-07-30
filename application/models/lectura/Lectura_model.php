<?php

class Lectura_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('catastro/Catastro_model');
        $this->load->model('lectura/Lecturista_model');
        $this->load->model('sistema/Parametro_model');
        $this->load->library('utilitario_cls');
    }
    
    public function get_one_lectura($LecId) {
        $query = $this->db->query('SELECT "Lectura".*,"SolFchEj" FROM "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId"
        WHERE "LecEli" = FALSE AND "LecId" = ?', array($LecId));
        return $query->row_array();
    }
    
    //lectura/Orden_avance_ctrllr -> avance_suborden($idOrden, $codSubOrden)
    public function get_one_suborden_x_orden($idOrden, $codSubOrden) {
        $query = $this->db->query('SELECT * FROM "Suborden_lectura" WHERE "SolEli" = FALSE AND "SolCod" = ? AND "SolOrtId" = ?', array($codSubOrden , $idOrden ));
        return $query->row_array();
    }

    // toma_estado/orden/avance/(:num)/suborden/(:num)
    //lectura/Orden_avance_ctrllr -> avance_suborden($idOrden, $codSubOrden)
    public function get_one_suborden($SolId) {
        $query = $this->db->query('SELECT * FROM "Suborden_lectura" WHERE "SolEli" = FALSE AND "SolId" = ?', array($SolId));
        return $query->row_array();
    }
    
    public function get_all_lecturas_x_ciclo($ciclo) {
        $query = $this->db->query('SELECT 
        "Propietario"."ProCodFc", 
        "Propietario"."ProNom", 
        "Urbanizacion"."UrbDes", 
        "Calle"."CalDes", 
        "Predio"."PreNumMc", 
        "Medidor"."MedNum"
      FROM 
        "Grupo_predio", 
        "Predio", 
        "Calle", 
        "Unidad_uso", 
        "Conexion", 
        "Medidor", 
        "Propietario", 
        "Urbanizacion"
      WHERE 
        "Predio"."PreGprId" = "Grupo_predio"."GprId" AND
        "Predio"."PreCalId" = "Calle"."CalId" AND
        "Predio"."PreUrbId" = "Urbanizacion"."UrbId" AND
        "Unidad_uso"."UsoPreId" = "Predio"."PreId" AND
        "Unidad_uso"."UsoProId" = "Propietario"."ProId" AND
        "Conexion"."CxnUsoId" = "Unidad_uso"."UsoId" AND
        "Conexion"."CxnMedId" = "Medidor"."MedId" AND "GprCod" = ?', array($ciclo));
        return $query->result_array();
    }

    // valorizacion_model -> insertar_valorizacion
    public function get_lecturas_capturador_x_orden_x_tipo($idOrden, $tipo) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT * FROM "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId"
        WHERE "SolOrtId" = ? AND "LecDis" = ? AND "LecEli" = FALSE AND "LecVld" = TRUE AND "LecTipId" = ?', array($idOrden, $tipo, $LecTipId));
        return $query->result_array();
    }
    public function get_lecturas_control_capturador_x_orden_x_tipo($idOrden, $tipo) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_sup')['DprId'];
        $query = $this->db->query('SELECT * FROM "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId"
        WHERE "SolOrtId" = ? AND "LecDis" = ? AND "LecEli" = FALSE AND "LecVld" = TRUE AND "LecTipId" = ?', array($idOrden, $tipo, $LecTipId));
        return $query->result_array();
    }
    public function get_lecturas_atipico_capturador_x_orden_x_tipo($idOrden, $tipo) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_ati')['DprId'];
        $query = $this->db->query('SELECT * FROM "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId"
        WHERE "SolOrtId" = ? AND "LecDis" = ? AND "LecEli" = FALSE AND "LecVld" = TRUE AND "LecTipId" = ?', array($idOrden, $tipo, $LecTipId));
        return $query->result_array();
    }
    //OTTomaEstado_cst_model -> orden_imprimir_suborden
    //OTTomaEstado_cst_model -> orden_digitar_suborden
    public function get_lecturas_x_lecturista_x_pagina($idOrden, $idLecturista, $limit, $offset) {
        $parametro = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg');
        $LecTipId = $parametro['DprId'];
        $query = $this->db->query('SELECT * FROM "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId"
        JOIN "Lecturista" ON "SolLtaId" = "LtaId"
        WHERE "SolOrtId" = ? AND "SolLtaId" = ? 
        AND "LecTipId" = ? AND "LecEli" = FALSE AND "LecDis" = FALSE ORDER BY "LecId" ASC LIMIT ? OFFSET ?', array($idOrden, $idLecturista, $LecTipId, $limit, $offset));
        $lecturas = $query->result_array();
        foreach ($lecturas as $key => $lectura) {
            if ($lectura['LecVld'] == 't') {
                $lecturas[$key]['color'] = 'success';
                $lecturas[$key]['bloqueado'] = TRUE;
            } elseif ($lectura['LecInt'] == 1) {
                $lecturas[$key]['color'] = 'warning';
                $lecturas[$key]['mensaje'] = 'Ingrese nuevamente.';
            } elseif ($lectura['LecInt'] == 2) {
                $lecturas[$key]['color'] = 'danger';
                $lecturas[$key]['mensaje'] = 'Último intento. Ingrese nuevamente.';
            } elseif ($lectura['LecInt'] == 3) {
                $lecturas[$key]['color'] = 'bg-gray';
                $lecturas[$key]['mensaje'] = 'Intentos agotados.';
                $lecturas[$key]['bloqueado'] = TRUE;
            }
            $observaciones = $this->get_obs_x_lectura($lectura['LecId']);
            $lecturas[$key]['observaciones'] = array_column($observaciones, 'OleObsId');
        }

        return $lecturas;
    }
    //OTTomaEstado_cst_model -> orden_imprimir_suborden
    //OTTomaEstado_cst_model -> orden_digitar_suborden
    public function get_lecturas_x_suborden_x_pagina($idOrden, $idSubOrden , $limit, $offset) {
        $parametro = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg');
        $LecTipId = $parametro['DprId'];
        $query = $this->db->query('SELECT * FROM "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId"
        JOIN "Lecturista" ON "SolLtaId" = "LtaId"
        WHERE "SolOrtId" = ? AND "SolId" = ? 
        AND "LecTipId" = ? AND "LecEli" = FALSE ORDER BY "LecId" ASC LIMIT ? OFFSET ?', array($idOrden, $idSubOrden , $LecTipId , $limit , $offset));
        $lecturas = $query->result_array();
        foreach ($lecturas as $key => $lectura) {
            if ($lectura['LecVld'] == 't') {
                $lecturas[$key]['color'] = 'success';
                $lecturas[$key]['bloqueado'] = TRUE;
            } elseif ($lectura['LecInt'] == 1) {
                $lecturas[$key]['color'] = 'warning';
                $lecturas[$key]['mensaje'] = 'Ingrese nuevamente.';
            } elseif ($lectura['LecInt'] == 2) {
                $lecturas[$key]['color'] = 'danger';
                $lecturas[$key]['mensaje'] = 'Último intento. Ingrese nuevamente.';
            } elseif ($lectura['LecInt'] == 3) {
                $lecturas[$key]['color'] = 'bg-gray';
                $lecturas[$key]['mensaje'] = 'Intentos agotados.';
                $lecturas[$key]['bloqueado'] = TRUE;
            }
            $observaciones = $this->get_obs_x_lectura($lectura['LecId']);
            $lecturas[$key]['observaciones'] = array_column($observaciones, 'OleObsId');
        }
        return $lecturas;
    }
    public function get_desc_ciclo_x_orden($idOrden) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT DISTINCT "GprCod","GprDes"
        FROM "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId"
        JOIN "Grupo_predio" ON "LecGprId" = "GprId" 
        WHERE "SolOrtId" = ? AND "SolEli" = FALSE AND "LecTipId" = ? GROUP BY "GprCod","GprDes" ORDER BY "GprDes" ASC', array($idOrden , $LecTipId ));
        return $query->result_array();
    }
    
    public function get_cant_suborden_lextura($idOrden) {
        $query = $this->db->query('SELECT COUNT(DISTINCT "Suborden_lectura"."SolFchEj") AS cantidad FROM "Suborden_lectura" WHERE "SolOrtId" = ?', array($idOrden));
        return $query->row_array()['cantidad'];
    }
    
    
    //OTTomaEstado_ctrllr -> insertar_ciclo()
    public function get_cant_lecturas_restantes_x_grupoPredio($idPeriodo, $idgrupoPredio, $conexionesTotales, $idContratante) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT("LecId") AS cantidad FROM "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId" 
        JOIN "Orden_trabajo" ON "SolOrtId" = "OrtId" 
        WHERE "OrtPrdId" = ? AND "SolGprId" = ? AND "LecTipId" = ?', array($idPeriodo, $idgrupoPredio, $LecTipId));
        $lecturasHechas = $query->row_array()['cantidad'];
        return $conexionesTotales - $lecturasHechas;
    }
    
    
    //OTTomaEstado_model -> orden_ver()
    public function get_lecturistas_x_ciclo_x_orden($idOrden) {
        $query = $this->db->query('SELECT "GprCod","GprDes",COUNT(DISTINCT "SolLtaId") AS "Lecturistas" 
        FROM "Suborden_lectura"
        JOIN "Lectura" ON "LecSolId" = "SolId"
        JOIN "Grupo_predio" ON "LecGprId" = "GprId" 
        WHERE "SolOrtId" = ? AND "SolEli" = FALSE 
        GROUP BY "GprCod","GprDes" ORDER BY "GprDes" ASC', array($idOrden));
        return $query->result_array();
    }
    
    //OTTomaEstado_model -> orden_ver()
    public function get_lecturas_x_suborden($idOrden, $subOrden) {
        $query = $this->db->query('SELECT * FROM "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId" 
        WHERE "SolOrtId" = ? AND "SolCod" = ? AND "LecEli" = FALSE ORDER BY "LecId"', array($idOrden, $subOrden));
        return $query->result_array();
    }
    
    //OTTomaEstado_cst_ctrllr -> get_detalle_x_suborden_json()
    public function get_lecturas_x_idsuborden($idSubOrden) {
        $query = $this->db->query('SELECT "LecCodFc","LecNom","LecUrb","LecCal","LecMun","LecMed" FROM "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId" 
        WHERE "SolId" = ? AND "LecEli" = FALSE ORDER BY "LecId"', array($idSubOrden));
        return $query->result_array();
    }
    
    //Orden_avance_cst_ctrllr -> orden_avance_suborden_json() 
    public function get_ruta_x_suborden($idOrden, $subOrden) {
        $query = $this->db->query('SELECT * FROM "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId" 
        WHERE "SolOrtId" = ? AND "SolCod" = ? AND "LecEli" = FALSE AND "LecPosLt" IS NOT NULL AND "LecPosLt" IS NOT NULL ORDER BY "LecId"', array($idOrden, $subOrden ));
        return $query->result_array();
    }
    //OTTomaEstado_model -> get_avance_x_orden
    public function get_ciclos_x_orden($idOrden) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT DISTINCT "GprCod","GprDes"
        FROM "Lectura" 
        JOIN "Grupo_predio" ON "LecGprId" = "GprId"
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId"
        WHERE "SolOrtId" = ? AND "LecTipId" = ? ORDER BY "GprCod" ASC', array(intval($idOrden), $LecTipId));
        return $query->result_array();
    }
    //OTTomaEstado_ctrllr -> avance_orden
    public function get_periodo_fact_x_orden($idOrden) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT "PrdAni", "PrdOrd" FROM "Periodo" 
        JOIN "Orden_trabajo" ON "OrtPrdId" = "PrdId"
        JOIN "Suborden_lectura" ON "SolOrtId" = "OrtId"
        JOIN "Lectura" ON "LecSolId" = "SolId"
        WHERE "SolOrtId" = ? AND "LecTipId" = ? LIMIT 1;', array(intval($idOrden), $LecTipId));
        return $query->row_array();
    }
    //ConsultasMovil_cst_ctrllr -> obtener_lecturas_en_ejec_x_lecturista_json() 
    public function get_desc_ciclos_x_orden_lecturista($idOrden, $idLecturista) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT DISTINCT "Grupo_predio"."GprCod","Grupo_predio"."GprDes" 
        FROM "Lectura" 
        JOIN "Grupo_predio" ON "LecGprId" = "GprId" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId"
        JOIN "Lecturista" ON "SolLtaId" = "LtaId"
        WHERE "SolOrtId" = ? AND "SolLtaId" = ? AND "LecTipId" = ?', array($idOrden, $idLecturista, $LecTipId));
        return $query->result_array();
    }
    //Reporte_model -> get_ciclos_control
    public function get_cant_lectura_prog_x_ciclo_x_periodo($idPeriodo, $idContratante, $idgrupoPredio) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT("Lectura".*) as "Cantidad" FROM "Lectura" 
        JOIN "Suborden_lectura" ON "SolId" = "LecSolId" 
        JOIN "Orden_trabajo" ON "SolOrtId" = "OrtId" 
        WHERE "OrtPrdId" = ? AND "SolGprId" = ? AND "OrtCntId" = ? AND "LecTipId" = ?', array($idPeriodo, $idgrupoPredio, $idContratante, $LecTipId));
        $row = $query->row_array();
        return $row['Cantidad'];
    }
    //OTTomaEstado_model -> insert_orden()
    public function get_lecturas_x_orden($idOrden) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT "Lectura".* FROM "Lectura" JOIN "Suborden_lectura" '
                . 'ON "LecSolId" = "SolId" WHERE "SolOrtId" = ? AND "LecTipId" = ?', array($idOrden, $LecTipId));
        return $query->result_array();
    }
    //OTTomaEstado_Ctrllr -> orden_atipicos()
    public function get_lecturas_x_orden_x_tipo($idOrden,$idTipo) {
        $query = $this->db->query(' SELECT "Lectura".* FROM "Lectura" '
                . 'JOIN "Suborden_lectura" ON "LecSolId" = "SolId"  WHERE "SolOrtId" = ? AND "LecTipId" = ?', array($idOrden, $idTipo) );
        return $query->result_array();
    }
    //OTTomaEstado_Ctrllr -> orden_atipicos()
    public function get_last_n_lecturas_x_orden_x_tipo($idOrden,$idTipo,$numeroUltimasFilas) {
        $query = $this->db->query('SELECT * FROM (SELECT  "Lectura".* FROM "Lectura" JOIN "Suborden_lectura" ON "LecSolId" = "SolId"  WHERE "SolOrtId" = ? AND "LecTipId" = ? '
                . 'ORDER BY "LecId" DESC LIMIT ?) as l1  ORDER BY "LecId" ASC', array($idOrden,$idTipo,$numeroUltimasFilas) );
        return $query->result_array();
    }
    //OTTomaEstado_model -> get_all_orden_trabajo_x_contratante
    //OTTomaEstado_model -> get_all_orden_x_valorizacion
    public function get_cant_lect_x_orden($idOrden) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT("LecId") as "cantidad" FROM "Lectura" JOIN "Suborden_lectura" '
                . 'ON "LecSolId" = "SolId"  WHERE "SolOrtId" = ? AND "LecTipId" = ?', array($idOrden, $LecTipId));
        return $query->row_array()['cantidad'];
    }
    //OTTomaEstado_model -> get_all_orden_trabajo_x_contratante($idContratante, $idActividad)
    public function get_cant_lect_x_orden_x_tipo($idOrden,$idTipo) {
        $query = $this->db->query('SELECT COUNT("LecId") as "cantidad" FROM "Lectura" JOIN "Suborden_lectura" '
                . 'ON "LecSolId" = "SolId" WHERE "SolOrtId" = ? AND "LecTipId" = ?', array($idOrden,$idTipo )  );
        return $query->row_array()['cantidad'];
    }
    //OTTomaEstado_model -> get_all_orden_trabajo_x_contratante
    //OTTomaEstado_model -> get_all_orden_x_valorizacion
    public function get_cant_lect_ejec_x_orden($idOrden) {
        $query = $this->db->query('SELECT COUNT("LecId") as "cantidad" FROM "Lectura" JOIN "Suborden_lectura" '
                . 'ON "LecSolId" = "SolId" WHERE "SolOrtId" = ? AND '
                . '"LecVld" = TRUE', array($idOrden));
        return $query->row_array()['cantidad'];
    }
    //OTTomaEstado_model -> get_all_orden_trabajo_x_contratante
    public function get_cant_lect_ejec_x_orden_x_tipo($idOrden,$idTipo) {
        $query = $this->db->query('SELECT COUNT("LecId") as "cantidad" FROM "Lectura" JOIN "Suborden_lectura" '
                . 'ON "LecSolId" = "SolId" WHERE "SolOrtId" = ? AND '
                . '"LecVld" = TRUE AND "LecTipId" = ?', array($idOrden , $idTipo));
        return $query->row_array()['cantidad'];
    }
    //Reporte_model -> get_ciclos_control
    public function get_cant_lectura_ejec_x_ciclo_x_periodo($idPeriodo, $idContratante, $idgrupoPredio) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT("Lectura".*) as "Cantidad" FROM "Lectura" '
                .'JOIN "Suborden_lectura" ON "SolId" = "LecSolId" JOIN "Orden_trabajo" ON "SolOrtId" = "OrtId" '
                .'WHERE "OrtPrdId" = ? AND "SolGprId" = ? AND "OrtCntId" = ? AND "LecVld" = TRUE AND "LecTipId" = ? ;'
                , array($idPeriodo, $idgrupoPredio, $idContratante, $LecTipId));
        $row = $query->row_array();
        return $row['Cantidad'];
    }
    //OTTomaEstado_model -> get_avance_x_orden
    public function get_cant_lectura_total_x_ciclo_x_orden($idOrden, $cicloCod) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT ("Lectura".*) as "cantidad" FROM "Orden_trabajo", "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId" 
        JOIN "Grupo_predio" ON "LecGprId" = "GprId"
        WHERE "SolOrtId" = "OrtId" AND "OrtId" = ? AND "GprCod" = ? AND "LecTipId" = ?', array($idOrden, $cicloCod, $LecTipId));
        $row = $query->row_array();
        return $row['cantidad'];
    }
    //OTTomaEstado_model -> get_avance_x_orden
    public function get_cant_lectura_ejec_x_ciclo_x_orden($idOrden, $cicloCod) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT ("Lectura".*) as "cantidad" 
        FROM "Orden_trabajo", "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId" 
        JOIN "Grupo_predio" ON "LecGprId" = "GprId"
        WHERE "SolOrtId" = "OrtId" AND "OrtId" = ? AND "GprCod" = ? AND "LecVld" = TRUE AND "LecTipId" = ?', array($idOrden, $cicloCod , $LecTipId));
        $row = $query->row_array();
        return $row['cantidad'];
    }
    //OTTomaEstado_model -> get_avance_x_orden
    public function get_cant_lectura_total_x_lecturista_x_ciclo_x_orden($idLecturista, $idOrden, $cicloCod, $fchEj) {
        //$LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT ("Lectura".*) as "cantidad" FROM "Orden_trabajo", "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId"
        JOIN "Grupo_predio" ON "LecGprId" = "GprId" 
        JOIN "Lecturista" ON "SolLtaId" = "LtaId"
        WHERE "SolOrtId" = "OrtId" AND "OrtId" = ? AND "GprCod" = ? AND "SolLtaId" = ? AND "SolFchEj" = ?', array($idOrden, $cicloCod, $idLecturista , $fchEj));
        $row = $query->row_array();
        return $row['cantidad'];
    }
    //OTTomaEstado_cst_model -> orden_imprimir_suborden
    public function get_cant_lectura_total_x_suborden_x_ciclo_x_orden($idOrden , $idSubOrden) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT ("Lectura".*) as "cantidad" FROM "Orden_trabajo", "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId"
        WHERE "SolOrtId" = "OrtId" AND "OrtId" = ? AND "SolId" = ? AND "LecTipId" = ?', array($idOrden , $idSubOrden, $LecTipId));
        $row = $query->row_array();
        return $row['cantidad'];
    }
    //OTTomaEstado_model -> get_avance_x_orden
    public function get_cant_lectura_ejec_x_lecturista_x_ciclo_x_orden($idLecturista, $idOrden, $cicloCod, $fchEj) {
        //$LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT ("Lectura".*) as "cantidad" FROM "Orden_trabajo"
        JOIN "Suborden_lectura" ON "SolOrtId" = "OrtId" 
        JOIN "Lectura" ON "LecSolId" = "SolId" 
        JOIN "Grupo_predio" ON "LecGprId" = "GprId"
        WHERE "SolOrtId" = ? AND "GprCod" = ? AND "SolLtaId" = ? AND "LecVld" = TRUE AND "SolFchEj" = ?', array($idOrden, $cicloCod, $idLecturista, $fchEj));
        $row = $query->row_array();
        return $row['cantidad'];
    }
    //OTTomaEstado_model -> get_avance_x_orden
    public function get_cant_obs_x_ciclo_x_orden($idOrden, $cicloCod) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT ("Observaciones_Lectura".*) as "cantidad" FROM "Orden_trabajo" 
        JOIN "Suborden_lectura" ON "SolOrtId" = "OrtId" 
        JOIN "Lectura" ON "LecSolId" = "SolId" 
        JOIN "Grupo_predio" ON "LecGprId" = "GprId"
        JOIN "Observaciones_Lectura" ON "OleLecId" = "LecId" AND "OrtId" = ? AND "GprCod" = ? AND "LecTipId" = ?', array($idOrden, $cicloCod, $LecTipId));
        $row = $query->row_array();
        return $row['cantidad'];
    }
    //OTTomaEstado_model -> get_avance_x_ciclo
    public function get_cant_obs_x_lecturista_x_ciclo_x_orden($idLecturista, $idOrden, $cicloCod) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT COUNT("Observaciones_Lectura".*) as "cantidad" FROM "Orden_trabajo" 
        JOIN "Suborden_lectura" ON "SolOrtId" = "OrtId" 
        JOIN "Lectura" ON "LecSolId" = "SolId" 
        JOIN "Grupo_predio" ON "LecGprId" = "GprId"
        JOIN "Observaciones_Lectura" ON "OleLecId" = "LecId"
        WHERE "SolOrtId" = ? AND "GprCod" = ? AND "SolLtaId" = ? AND "LecTipId" = ?', array($idOrden, $cicloCod, $idLecturista, $LecTipId));
        $row = $query->row_array();
        return $row['cantidad'];
    }
    
    //OTTomaEstado_ctrllr -> avance_suborden
    public function get_obs_x_suborden($idSuborden) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT "Observacion".*, COUNT("ObsId") as "cantidad" FROM "Observaciones_Lectura" 
        JOIN "Observacion" ON "OleObsId" = "ObsId"
        JOIN "Lectura" ON "OleLecId" = "LecId" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId"
        AND "SolId" = ? AND "LecTipId" = ? GROUP BY "ObsId";', array($idSuborden, $LecTipId));
        return $query->result_array();
    }
    
    
    //OTTomaEstado_model -> get_avance_x_suborden
    public function get_obs_x_lectura($idLectura) {
        $query = $this->db->query('SELECT * FROM "Observaciones_Lectura" JOIN "Observacion" ON "OleObsId" = "ObsId" '
                . 'AND "OleLecId" = ?', array($idLectura));
        return $query->result_array();
    }
    
    //OTTomaEstado_cst_ctrllr -> orden_ver()
    public function get_detalle_suministros_lecturista($idOrden) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT "Suborden_lectura".*,COUNT(*) AS "suministros","GprCod" 
        FROM "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId"
        JOIN "Lecturista" ON "SolLtaId" = "LtaId"
        JOIN "Grupo_predio" ON "LecGprId" = "GprId"
        WHERE "SolOrtId" = ? AND "LecEli" = FALSE AND "LecTipId" = ?
        GROUP BY "SolId","SolLtaId","GprCod","SolCod" ORDER BY "GprCod"::integer,"SolCod"::integer ASC', array($idOrden, $LecTipId));
        return $query->result_array();
    }
    
    //OTTomaEstado_cst_ctrllr -> orden_ver()
    public function get_detalle_suministros_sin_asignar_lecturista($idOrden,$SolFchEj) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT "Suborden_lectura".*,COUNT(*) AS "suministros"
        FROM "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId"
        JOIN "Grupo_predio" ON "LecGprId" = "GprId"
        WHERE "SolOrtId" = ? AND "LecEli" = FALSE AND "LecTipId" = ? AND "SolFchEj" = ?
        GROUP BY "SolId","SolLtaId","SolCod" ORDER BY "SolCod"::integer ASC', array($idOrden, $LecTipId , $SolFchEj));
        return $query->result_array();
    }
    
    //Inicio_cst_ctrllr -> inicio() 
    public function get_subordenes_x_fechaEj_x_lecturista($SolFchEj, $SolLtaId) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT "Suborden_lectura".*,COUNT(*) AS "suministros","GprCod","OrtId" 
        FROM "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId"
        JOIN "Orden_trabajo" ON "SolOrtId" = "OrtId"
        JOIN "Lecturista" ON "SolLtaId" = "LtaId"
        JOIN "Grupo_predio" ON "LecGprId" = "GprId"
        WHERE "LecEli" = FALSE AND "LecTipId" = ? AND "SolFchEj"= ? AND "SolLtaId" = ?
	GROUP BY "SolId","SolLtaId","GprCod","SolCod","OrtId"  ORDER BY "GprCod"::integer,"SolCod"::integer ASC,"OrtId"', array($LecTipId, $SolFchEj, $SolLtaId));
        return $query->result_array();
    }
    
    
    /*   DATOS PARA LA APLICACION MOVIL : TOMA DE ESTADO    */
    //ConsultasMovil_cst_ctrllr -> obtener_lecturas_en_ejec_x_lecturista_json
    public function get_lecturas_x_lecturista($OrtFchEj, $LecLtaId) {
        $query = $this->db->query('SELECT "SolOrtId","Lectura".* FROM "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId" 
        WHERE "SolFchEj" = ? AND "SolLtaId" = ? AND "LecEli" = FALSE AND "LecVld" IS NOT TRUE ORDER BY "LecId"', array($OrtFchEj, $LecLtaId ));
        return $query->result_array();
    }
    //ConsultasMovil_cst_ctrllr -> sincronizar_lecturas_json
    public function insert_lectura_sincronizacion_movil($LecVal, $LecValInt1, $LecValInt2, $LecValInt3, $LecInt, $LecLat, $LecLong, $LecImg, $LecCom, $LecFchRg, $LecId) {
        $query = $this->db->query('UPDATE "Lectura" SET "LecVal" = ?, "LecVal1" = ?, "LecVal2" = ?, "LecVal3" = ?, "LecInt" = ?, "LecPosLt" = ?, "LecPosLg" = ?, "LecImg" = ?, "LecCom" = ?, "LecFchRgMov" = ?, "LecDis" = ?, "LecVld" = ?, "LecFchAc" = ? WHERE "LecId" = ?'
                , array($LecVal, $LecValInt1, $LecValInt2, $LecValInt3, $LecInt, $LecLat, $LecLong, $LecImg, $LecCom, $LecFchRg , TRUE, TRUE, date("Y-m-d H:i:s"), $LecId));
        return $query;
    }
    //ConsultasMovil_cst_ctrllr -> sincronizar_observaciones_lecturas_json
    public function insert_observaciones_lectura_sincronizacion_movil($OleLecId, $OleObsId, $OleImg) {
        $query = $this->db->query('INSERT into "Observaciones_Lectura" ("OleImg", "OleEli" , "OleFchRg", "OleFchAc" , "OleLecId" , "OleObsId" ) '
                . ' VALUES (?,?,?,?,?,?);', array($OleImg, false, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $OleLecId, $OleObsId));
        return $query;
    }
    public function insert_imagen_lectura_sincronizacion_movil($LecImg, $LecId) {
        $query = $this->db->query('UPDATE "Lectura" SET "LecImg" = ? WHERE "LecId" = ?'
                , array( $LecImg , $LecId));
        return $query;
    }
    
    
    
    /*   PARA ATIPICOS DE TOMA DE ESTADO POR GRUPO PREDIO  */
    // OTTomaEstado_ctrllr -> orden_atipicos($idOrdenTrabajo)
    public function update_one_lectura_id($LecVal, $LecInt, $LecDig, $idLectura) {
        $query = $this->db->query('UPDATE "Lectura" SET "LecVal" = ?, "LecVld" = ? , LecInt" = ? ,  "LecFchAc" = ?  , "LecDig" = ?  WHERE "LecId" = ?'
                , array($LecVal, TRUE, $LecInt, date("Y-m-d H:i:s"), $LecDig, $idLectura));
        return $query;
    }
    //OTTomaEstado_ctrllr -> orden_atipicos($idOrdenTrabajo)
    public function get_lectura_x_codFac( $idOrdenTrabajo, $CodFac ) 
    {
        $query = $this->db->query('SELECT "Lectura".* FROM "Lectura" 
        JOIN "Suborden_lectura" ON "LecSolId" = "SolId"
        WHERE "SolOrtId" = ? AND "LecCodFc" = ?', array($idOrdenTrabajo , strval($CodFac)));
        return $query->result_array();
    }
    //OTTomaEstado_ctrllr -> orden_atipicos($idOrdenTrabajo)
    public function get_lectura_x_nombres( $idOrdenTrabajo , $nombres ) 
    {
        $consulta = sprintf("SELECT \"Lectura\".* FROM \"Lectura\" 
        JOIN \"Suborden_lectura\" ON \"LecSolId\" = \"SolId\"
        WHERE \"SolOrtId\" = $idOrdenTrabajo AND \"LecNom\" ILIKE '%%$nombres%%'");
        $query = $this->db->query($consulta);
        return $query->result_array();
    }
}