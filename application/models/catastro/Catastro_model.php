<?php

class Catastro_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        
        $this->load->model('general/Actividad_model');
        $this->load->model('general/NivelGrupo_model');
        
        
    }

    // antes get_all_lecturas_x_ciclo
    public function get_all_conexiones_x_grupo($GprCod) {
        $query = $this->db->query('SELECT 
        "Propietario"."ProCodFc", 
        "Propietario"."ProNom", 
        "Urbanizacion"."UrbDes", 
        "Calle"."CalDes", 
        "Predio"."PreNumMc", 
        "Medidor"."MedNum", "GprId", "CxnId"
      FROM 
        public."Grupo_predio", 
        public."Predio", 
        public."Calle", 
        public."Unidad_uso", 
        public."Conexion", 
        public."Medidor", 
        public."Propietario", 
        public."Urbanizacion"
      WHERE 
        "Predio"."PreGprId" = "Grupo_predio"."GprId" AND
        "Predio"."PreCalId" = "Calle"."CalId" AND
        "Predio"."PreUrbId" = "Urbanizacion"."UrbId" AND
        "Unidad_uso"."UsoPreId" = "Predio"."PreId" AND
        "Unidad_uso"."UsoProId" = "Propietario"."ProId" AND
        "Conexion"."CxnUsoId" = "Unidad_uso"."UsoId" AND
        "Conexion"."CxnMedId" = "Medidor"."MedId" AND "GprCod" = ?', array($GprCod));
        return $query->result_array();
    }

    //NivelGrupo_model->get_all_grupo_x_nivel_cantidad
    public function get_cant_conexiones_x_grupo($idGrupo, $nroSubniveles) {
        $join = '';
        for($i = 1; $i < ($nroSubniveles+1); $i++){
            $join .= 'JOIN "Grupo_predio" as gp'.$i.' ON gp'.$i.'."GprId" = gp'.($i-1).'."GprGprId" ';
        }
        $query = $this->db->query('SELECT COUNT("Conexion".*) as "cantidad"
        FROM "Predio", "Calle", "Unidad_uso", "Conexion", "Medidor", "Propietario", "Urbanizacion", "Grupo_predio" as gp0 '.$join.
      ' WHERE 
        "Predio"."PreGprId" = gp0."GprId" AND
        "Predio"."PreCalId" = "Calle"."CalId" AND
        "Predio"."PreUrbId" = "Urbanizacion"."UrbId" AND
        "Unidad_uso"."UsoPreId" = "Predio"."PreId" AND
        "Unidad_uso"."UsoProId" = "Propietario"."ProId" AND
        "Conexion"."CxnUsoId" = "Unidad_uso"."UsoId" AND
        "Conexion"."CxnMedId" = "Medidor"."MedId" AND gp'.($i-1).'."GprId" = ?;', array($idGrupo));
        return $query->row_array()['cantidad'];
    }
    
    // OTTomaEstado_ctrllr -> get_detalle_x_suborden_json
    public function get_conexiones_x_suborden($idGrupoPredio, $cantidad, $anio, $mes, $inicio) {
        $grupoPredio = $this->NivelGrupo_model->get_one_grupoPredio($idGrupoPredio);
        $nroSubniveles = intval($this->NivelGrupo_model->get_cant_subniveles($grupoPredio['NgrId']));
        $join = '';
        for($i = 1; $i < ($nroSubniveles+1); $i++){
            $join .= 'JOIN "Grupo_predio" as gp'.$i.' ON gp'.$i.'."GprId" = gp'.($i-1).'."GprGprId" ';
        }
        $query = $this->db->query('SELECT "ProCodFc", "ProNom", "UrbDes", "CalDes", "PreNumMc", "MedNum", "CxnId"
        FROM "Predio", "Calle", "Unidad_uso", "Conexion", "Medidor", "Propietario", "Urbanizacion","Historico_lectura","Grupo_predio" as gp0 '.$join.'
        WHERE 
        "Predio"."PreGprId" = gp0."GprId" AND
        "Predio"."PreCalId" = "Calle"."CalId" AND
        "Predio"."PreUrbId" = "Urbanizacion"."UrbId" AND
        "Unidad_uso"."UsoPreId" = "Predio"."PreId" AND
        "Unidad_uso"."UsoProId" = "Propietario"."ProId" AND
        "Conexion"."CxnUsoId" = "Unidad_uso"."UsoId" AND
        "Conexion"."CxnMedId" = "Medidor"."MedId" AND 
        "HlcCxnId" = "CxnId" AND gp'.($i-1).'."GprId" = ? AND "HlcAni" = ? AND "HlcMes" = ? 
        ORDER BY gp0."GprCod" ASC,"UsoCar" ASC, "UsoOrd" ASC, "UrbDes" ASC, "CalDes" ASC, "PreNumMc" ASC, "MedNum" ASC LIMIT ? OFFSET ?', array($idGrupoPredio, $anio, $mes, $cantidad, $inicio));
        return $query->result_array();
    }
    
    // OTTomaEstado_ctrllr -> get_detalle_x_suborden_json
    public function get_conexiones_x_subciclo_x_suborden($idGrupoPredio, $cantidad, $anio, $mes, $inicio) {
        
        if(count($idGrupoPredio)==1)
        {
          $OR = 'gp0."GprId" ='.$idGrupoPredio[0];  
        }
        else
        {
            $OR = 'gp0."GprId" = '.$idGrupoPredio[0];
            for ($i = 1; $i < count($idGrupoPredio); $i++) {
              $OR.= ' OR gp0."GprId" = '.$idGrupoPredio[$i]; 
            } 
        }
        $query = $this->db->query('SELECT "ProCodFc", "ProNom", "UrbDes", "CalDes", "PreNumMc", "MedNum", "CxnId"
        FROM "Predio", "Calle", "Unidad_uso", "Conexion", "Medidor", "Propietario", "Urbanizacion","Historico_lectura","Grupo_predio" as gp0
        WHERE 
        "Predio"."PreGprId" = gp0."GprId" AND
        "Predio"."PreCalId" = "Calle"."CalId" AND
        "Predio"."PreUrbId" = "Urbanizacion"."UrbId" AND
        "Unidad_uso"."UsoPreId" = "Predio"."PreId" AND
        "Unidad_uso"."UsoProId" = "Propietario"."ProId" AND
        "Conexion"."CxnUsoId" = "Unidad_uso"."UsoId" AND
        "Conexion"."CxnMedId" = "Medidor"."MedId" AND 
        "HlcCxnId" = "CxnId" AND ( '.$OR.' ) AND "HlcAni" = ? AND "HlcMes" = ? 
        ORDER BY gp0."GprCod" ASC,"UsoCar" ASC, "UsoOrd" ASC, "UrbDes" ASC, "CalDes" ASC, "PreNumMc" ASC, "MedNum" ASC LIMIT ? OFFSET ?', array( $anio, $mes, $cantidad, $inicio));
        return $query->result_array();
    }
    
// <editor-fold defaultstate="collapsed" desc="OTTomaEstado_model - INSERTAR ORDEN">
    //OTTomaEstado_model    ->  insert_orden
    public function get_conexiones_x_ciclo_x_suborden_batch($idSuborden, $idCiclo, $cantidad, $nroSuborden, $anio, $mes, $idContratante, $LecTipId, $filaInicio) {
        $grupoPredio = $this->NivelGrupo_model->get_one_grupoPredio($idCiclo);
        $nroSubniveles = intval($this->NivelGrupo_model->get_cant_subniveles($grupoPredio['NgrId']));
        $join = '';
        for ($i = 1; $i < ($nroSubniveles + 1); $i++) {
            $join .= 'JOIN "Grupo_predio" as gp' . $i . ' ON gp' . $i . '."GprId" = gp' . ($i - 1) . '."GprGprId"';
        }
        $query = $this->db->query('SELECT "ProCodFc" as "LecCodFc", "ProNom" as "LecNom", "UrbDes" as "LecUrb", "CalDes" as "LecCal", "PreNumMc" as "LecMun", "MedNum" as "LecMed", 
        0 as "LecInt", "CxnId" as "LecCxnId", "HlcPrm" as "LecCsmPr","HlcLecAnt" as "LecAnt", 
        false as "LecEli", ? as "LecFchRg",? as "LecFchAc", ? as "LecSolId", gp0."GprId" as "LecGprId", ? as "LecCntId", ? as "LecTipId"
        FROM "Predio", "Calle", "Unidad_uso", "Conexion", "Medidor", "Propietario", "Urbanizacion","Historico_lectura","Grupo_predio" as gp0 ' . $join . '
        WHERE 
        "Predio"."PreGprId" = gp0."GprId" AND
        "Predio"."PreCalId" = "Calle"."CalId" AND
        "Predio"."PreUrbId" = "Urbanizacion"."UrbId" AND
        "Unidad_uso"."UsoPreId" = "Predio"."PreId" AND
        "Unidad_uso"."UsoProId" = "Propietario"."ProId" AND
        "Conexion"."CxnUsoId" = "Unidad_uso"."UsoId" AND
        "Conexion"."CxnMedId" = "Medidor"."MedId" AND 
        "HlcCxnId" = "CxnId" AND gp' . ($i - 1) . '."GprId" = ? AND "HlcAni" = ? AND "HlcMes" = ?
        ORDER BY gp0."GprCod" ASC,"UsoCar" ASC, "UsoOrd" ASC, "UrbDes" ASC, "CalDes" ASC, "PreNumMc" ASC, "MedNum" ASC LIMIT ? OFFSET ?', array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idSuborden, $idContratante, $LecTipId, $idCiclo, $anio, $mes, $cantidad, $filaInicio));
        return $query->result_array();
    }

    //OTTomaEstado_model    ->  insert_orden_nivel_inferior()
    public function get_conexiones_x_subciclos_x_suborden_batch($idSuborden, $idCiclo, $cantidad, $nroSuborden, $anio, $mes, $idContratante, $LecTipId, $filaInicio) {
        if (count($idCiclo) == 1)         {
            $OR = 'gp0."GprId" =' . $idCiclo[0];

        }         else         {
            $OR = 'gp0."GprId" =' . $idCiclo[0];
            for ($i = 1; $i < count($idCiclo); $i++) {
                $OR.= ' OR gp0."GprId" = ' . $idCiclo[$i];

            }

        }
        $query = $this->db->query('SELECT "ProCodFc" as "LecCodFc", "ProNom" as "LecNom", "UrbDes" as "LecUrb", "CalDes" as "LecCal", "PreNumMc" as "LecMun", "MedNum" as "LecMed", 
        0 as "LecInt", "CxnId" as "LecCxnId", "HlcPrm" as "LecCsmPr","HlcLecAnt" as "LecAnt", 
        false as "LecEli", ? as "LecFchRg",? as "LecFchAc", ? as "LecSolId", gp0."GprId" as "LecGprId", ? as "LecCntId", ? as "LecTipId"
        FROM "Predio", "Calle", "Unidad_uso", "Conexion", "Medidor", "Propietario", "Urbanizacion","Historico_lectura","Grupo_predio" as gp0
        WHERE 
        "Predio"."PreGprId" = gp0."GprId" AND
        "Predio"."PreCalId" = "Calle"."CalId" AND
        "Predio"."PreUrbId" = "Urbanizacion"."UrbId" AND
        "Unidad_uso"."UsoPreId" = "Predio"."PreId" AND
        "Unidad_uso"."UsoProId" = "Propietario"."ProId" AND
        "Conexion"."CxnUsoId" = "Unidad_uso"."UsoId" AND
        "Conexion"."CxnMedId" = "Medidor"."MedId" AND 
        "HlcCxnId" = "CxnId" AND ( ' . $OR . ' ) AND "HlcAni" = ? AND "HlcMes" = ?
        ORDER BY gp0."GprCod" ASC,"UsoCar" ASC, "UsoOrd" ASC, "UrbDes" ASC, "CalDes" ASC, "PreNumMc" ASC, "MedNum" ASC LIMIT ? OFFSET ?', array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idSuborden, $idContratante, $LecTipId, $anio, $mes, $cantidad, $filaInicio));
        return $query->result_array();
    }
// </editor-fold>
    
// <editor-fold defaultstate="collapsed" desc="OTDistribucion_model -> INSERTAR ORDEN DE TRABAJO POR CICLO Y SUBCICLOS">
    //OTDistribucion    ->  insert_orden
    public function get_conexiones_x_ciclo_x_suborden_batch_distribucion($idSuborden, $idCiclo, $cantidad, $nroSuborden, $anio, $mes, $idContratante, $DbnTipId , $filaInicio , $DbnSacId ) {
        $grupoPredio = $this->NivelGrupo_model->get_one_grupoPredio($idCiclo);
        $nroSubniveles = intval($this->NivelGrupo_model->get_cant_subniveles($grupoPredio['NgrId']));
        $join = '';
        for ($i = 1; $i < ($nroSubniveles + 1); $i++) {
            $join .= 'JOIN "Grupo_predio" as gp' . $i . ' ON gp' . $i . '."GprId" = gp' . ($i - 1) . '."GprGprId"';
        }
        
        $query = $this->db->query('SELECT "ProCodFc" as "DbnCodFc", "ProNom" as "DbnNom", "UrbDes" as "DbnUrb", "CalDes" as "DbnCal", "PreNumMc" as "DbnMun", "MedNum" as "DbnMed" 
        , "CxnId" as "DbnCxnId", false as "DbnEli", ? as "DbnFchRg", ? as "DbnFchAc", ? as "DbnSodId", gp0."GprId" as "DbnGprId", ? as "DbnCntId", ? as "DbnTipId", ? as "DbnSacId"
        FROM "Predio", "Calle", "Unidad_uso", "Conexion", "Medidor", "Propietario", "Urbanizacion", "Grupo_predio" as gp0 ' . $join . '
        WHERE 
        "Predio"."PreGprId" = gp0."GprId" AND
        "Predio"."PreCalId" = "Calle"."CalId" AND
        "Predio"."PreUrbId" = "Urbanizacion"."UrbId" AND
        "Unidad_uso"."UsoPreId" = "Predio"."PreId" AND
        "Unidad_uso"."UsoProId" = "Propietario"."ProId" AND
        "Conexion"."CxnUsoId" = "Unidad_uso"."UsoId" AND
        "Conexion"."CxnMedId" = "Medidor"."MedId"
        AND gp' . ($i - 1) . '."GprId" = ?
        ORDER BY gp0."GprCod" ASC,"UsoCar" ASC, "UsoOrd" ASC, "UrbDes" ASC, "CalDes" ASC, "PreNumMc" ASC, "MedNum" ASC LIMIT ? OFFSET ?', array( date("Y-m-d H:i:s"), date("Y-m-d H:i:s") , $idSuborden, $idContratante, $DbnTipId , $DbnSacId ,  $idCiclo , $cantidad, $filaInicio) );
        
        return $query->result_array();
    }


    //OTDistribucion_model    ->  insert_orden_nivel_inferior()
    public function get_conexiones_x_subciclos_x_suborden_batch_distribucion($idSuborden, $idCiclo, $cantidad, $nroSuborden, $anio, $mes, $idContratante, $DbnTipId , $filaInicio , $DbnSacId ) {
        if (count($idCiclo) == 1) {
            $OR = 'gp0."GprId" =' . $idCiclo[0];
        } else {
            $OR = 'gp0."GprId" =' . $idCiclo[0];
            for ($i = 1; $i < count($idCiclo); $i++) {
                $OR.= ' OR gp0."GprId" = ' . $idCiclo[$i];
            }
        }
        $query = $this->db->query('SELECT "ProCodFc" as "DbnCodFc", "ProNom" as "DbnNom", "UrbDes" as "DbnUrb", "CalDes" as "DbnCal", "PreNumMc" as "DbnMun", "MedNum" as "DbnMed", 
        "CxnId" as "DbnCxnId",false as "DbnEli", ? as "LecFchRg",? as "LecFchAc", ? as "DbnSodId", gp0."GprId" as "DbnGprId", ? as "DbnCntId", ? as "DbnTipId", ? as "DbnSacId"
        FROM "Predio", "Calle", "Unidad_uso", "Conexion", "Medidor", "Propietario", "Urbanizacion","Grupo_predio" as gp0
        WHERE 
        "Predio"."PreGprId" = gp0."GprId" AND
        "Predio"."PreCalId" = "Calle"."CalId" AND
        "Predio"."PreUrbId" = "Urbanizacion"."UrbId" AND
        "Unidad_uso"."UsoPreId" = "Predio"."PreId" AND
        "Unidad_uso"."UsoProId" = "Propietario"."ProId" AND
        "Conexion"."CxnUsoId" = "Unidad_uso"."UsoId" AND
        "Conexion"."CxnMedId" = "Medidor"."MedId" AND 
        "HlcCxnId" = "CxnId" AND ( ' . $OR . ' )
        ORDER BY gp0."GprCod" ASC,"UsoCar" ASC, "UsoOrd" ASC, "UrbDes" ASC, "CalDes" ASC, "PreNumMc" ASC, "MedNum" ASC LIMIT ? OFFSET ?', array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idSuborden, $idContratante, $DbnTipId, $DbnSacId , $cantidad, $filaInicio));
        return $query->result_array();
    }
// </editor-fold>
        
    // OTTomaEstado_ctrllr -> orden_nuevo_detalle_ciclo
    public function get_one_grupoPredio_x_mes($idgrupoPredio, $mes, $anio) {
        $grupoPredio = $this->NivelGrupo_model->get_one_grupoPredio($idgrupoPredio);
        $grupoPredio['cantidad'] = $this->get_cant_conexiones_x_grupoPredio_x_mes($idgrupoPredio, $mes, $anio);
        return $grupoPredio;
    }
    
    public function get_all_ciclo() {
        $query = $this->db->query('SELECT * FROM "Ciclo"');
        return $query->result_array();
    }
    
    public function get_all_ciclo_localidad() {
        $ciclos = $this->get_all_ciclo();
        foreach ($ciclos as $key => $ciclo) {
            $cantidad = $this->get_cantidad_suministros_x_ciclo($ciclo['CicId']);
            $query = $this->db->query('SELECT "SciDes" FROM public."Ciclo" 
            JOIN "Subciclo" ON "SciCicId" = "CicId" 
            WHERE "CicId" = ?', array($ciclo['CicId']));
            $localidades = array_column($query->result_array(), 'SciDes');
            $ciclos[$key]['cantidad'] = $cantidad;
            $ciclos[$key]['localidades'] = $localidades;
        }
        return $ciclos;
    }
    
    //this -> get_all_ciclo_localidad
    public function get_cantidad_suministros_x_grupoPredio($idGrupoPredio) {
        $query = $this->db->query('SELECT COUNT(*) AS cantidad FROM "Unidad_uso", "Predio", "Conexion", 
        "Grupo_predio" WHERE "UsoPreId" = "PreId" AND "PreGprId" = "GprId" AND 
        "CxnUsoId" = "UsoId" AND "GprId" = ?', array($idGrupoPredio));
        return $query->row_array()['cantidad'];
    }
    
    //OTTomaEstado_ctrllr -> insertar_ciclo
    public function get_cant_conexiones_x_grupoPredio_x_mes($idCiclo, $mes, $anio) {
        $grupoPredio = $this->NivelGrupo_model->get_one_grupoPredio($idCiclo);
        $nroSubniveles = intval($this->NivelGrupo_model->get_cant_subniveles($grupoPredio['NgrId']));
        $join = '';
        for($i = 1; $i < ($nroSubniveles+1); $i++){
            $join .= 'JOIN "Grupo_predio" as gp'.$i.' ON gp'.$i.'."GprId" = gp'.($i-1).'."GprGprId" ';
        }
        $query = $this->db->query('SELECT COUNT(*) AS cantidad FROM "Unidad_uso", "Predio", "Conexion", "Historico_lectura", "Grupo_predio" as gp0 '.$join.
        ' WHERE 
        "Predio"."PreGprId" = gp0."GprId" AND
        "UsoPreId" = "PreId" AND 
        "CxnUsoId" = "UsoId" AND 
        "HlcCxnId" = "CxnId" AND 
        "HlcMes"= ? AND "HlcAni" = ? AND gp'.($i-1).'."GprId" = ?', array( ($mes == 1) ? 12 : ($mes - 1), $anio , $idCiclo));
        return $query->row_array()['cantidad'];
    }
    
    //Lectura_model -> get_all_ciclo_localidad
    public function get_cantidad_suministros_x_ciclo_x_cod($cicloCod) {
        $query = $this->db->query('SELECT COUNT(*) AS cantidad FROM "Unidad_uso", "Predio", "Conexion", 
        "Grupo_predio" WHERE "UsoPreId" = "PreId" AND "PreGprId" = "GprId" AND 
        "CxnUsoId" = "UsoId" AND "GprCod" = ?', array($cicloCod));
        return $query->row_array()['cantidad'];
    }
    
    //NicelGrupo_Ctrllr -> nivelGrupo_subnivel($idSubNivel)
    public function get_all_predios_x_idGrupoPredio($GprId) {
        $query = $this->db->query('SELECT "ProCodFc", "ProNom", "UrbDes", "CalDes", "PreNumMc", "MedNum"
        FROM "Predio", "Calle", "Unidad_uso", "Conexion", "Medidor", "Propietario", "Urbanizacion","Grupo_predio" as gp0
        WHERE 
        "Predio"."PreGprId" = gp0."GprId" AND
        "Predio"."PreCalId" = "Calle"."CalId" AND
        "Predio"."PreUrbId" = "Urbanizacion"."UrbId" AND
        "Unidad_uso"."UsoPreId" = "Predio"."PreId" AND
        "Unidad_uso"."UsoProId" = "Propietario"."ProId" AND
        "Conexion"."CxnUsoId" = "Unidad_uso"."UsoId" AND
        "Conexion"."CxnMedId" = "Medidor"."MedId"
        AND gp0."GprId" = ? 
        ORDER BY gp0."GprCod" ASC,"UsoCar" ASC, "UsoOrd" ASC, "UrbDes" ASC, "CalDes" ASC, "PreNumMc" ASC, "MedNum" ASC', array($GprId));
        return $query->result_array();
    } 
    
    
    
}
