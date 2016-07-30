<?php

class NivelGrupo_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //NivelGrupo_ctrllr -> nivelGrupo_listar() 
    //CronogramaLectura_ctrllr -> cronograma_anual_nuevo()
    public function get_all_nivelGrupo($idContratante) {
        $query = $this->db->query('SELECT * FROM "Nivel_grupo" WHERE "NgrCntId" = ? ORDER BY "NgrOrd" DESC',array($idContratante));
        return $query->result_array();
    }
    
    //NivelGrupo_ctrllr -> nivelGrupo_detalle() 
    public function get_one_nivelGrupo($idNivelComercial) {
        $query = $this->db->query('SELECT * FROM "Nivel_grupo" '
                . 'WHERE "NgrId" = ?', array($idNivelComercial));
        return $query->row_array();
    }
    
    //NivelGrupo_ctrllr -> nivelesGrupo_listar() 
    public function get_last_nivelGrupo($idContratante) {
        $query = $this->db->query('SELECT * FROM "Nivel_grupo" WHERE "NgrCntId" = ? ORDER BY "NgrOrd" DESC LIMIT 1', array($idContratante));
        return $query->row_array();
    }
    
    //NivelGrupo_ctrllr -> nivelesGrupo_listar()
    public function insert_nivelGrupo($NgrDes,$ultimoNivel,$idContratante) {
        $query = $this->db->query('INSERT INTO "Nivel_grupo" ("NgrDes", "NgrOrd", "NgrEli", "NgrFchRg", "NgrFchAc", "NgrCntId" , "NgrUsrId" , "NgrIpAdd" , "NgrHosNm"  ) VALUES (?,?,?,?,?,?,?,?,?)'
                , array($NgrDes, $ultimoNivel+1, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s") , $idContratante , $_SESSION['usuario_id'] , $_SERVER['REMOTE_ADDR'] , gethostbyaddr($_SERVER['REMOTE_ADDR']) ));
        return $query;
    }

    //NivelGrupo_ctrllr -> nivelGrupo_detalle() 
    public function update_nivelGrupo($NgrDes , $idNivelGrupo) {
        $query = $this->db->query('UPDATE "Nivel_grupo" SET "NgrDes" = ? , "NgrUsrId" = ?, "NgrIpAdd" = ?, "NgrHosNm" = ? WHERE "NgrId" = ?'
                , array($NgrDes , $_SESSION['usuario_id'] , $_SERVER['REMOTE_ADDR'] , gethostbyaddr($_SERVER['REMOTE_ADDR']) , $idNivelGrupo  ));
        return $query;
    }
   
    //NivelGrupo_ctrllr -> nivelGrupo_detalle()
    public function insert_grupoPredio($GprCod , $GprDes , $idNivelGrupo)
    {
        $query = $this->db->query('INSERT INTO "Grupo_predio" ("GprCod", "GprDes", "GprNgrId" , "GprEli", "GprFchRg", "GprFchAc" , "GprUsrId" , "GprIpAdd" , "GprHosNm" ) VALUES (?,?,?,?,?,?)'
                , array($GprCod  , $GprDes , $idNivelGrupo , FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $_SESSION['usuario_id'] , $_SERVER['REMOTE_ADDR'] , gethostbyaddr($_SERVER['REMOTE_ADDR'])));
        return $query;
    }  
    
    //NivelGrupo_ctrllr -> nivelGrupo_subnivel()
    public function update_grupoPredio($NgrCod , $NgrDes , $idSubNivel)
    {
        $query = $this->db->query('UPDATE "Grupo_predio" SET "GprCod" = ?, "GprDes" = ? , "GprUsrId" = ? , "GprIpAdd" = ? , "GprHosNm" = ? WHERE "GprId" = ?'
                , array($NgrCod , $NgrDes , $_SESSION['usuario_id'] , $_SERVER['REMOTE_ADDR'] , gethostbyaddr($_SERVER['REMOTE_ADDR']) , $idSubNivel  ));
        return $query;
    }
    
    //NivelGrupo_ctrllr ->  nivelGrupo_subnivel_agrupar()
    public function agrupar_grupoPredio($idNivel,$idSubNivel)
    {
        $query = $this->db->query('UPDATE "Grupo_predio" SET "GprGprId" = ? , "GprUsrId" = ? , "GprIpAdd" = ? , "GprHosNm" = ? WHERE "GprId" = ?'
                , array($idNivel , $_SESSION['usuario_id'] , $_SERVER['REMOTE_ADDR'] , gethostbyaddr($_SERVER['REMOTE_ADDR']) ,$idSubNivel ));
        return $query;
    }

    //NivelGrupo_ctrllr ->  nivelGrupo_subnivel_desagrupar()
    public function desagrupar_grupoPredio($idSubNivel)
    {
        $query = $this->db->query('UPDATE "Grupo_predio" SET "GprGprId" = NULL , "GprUsrId" = ? , "GprIpAdd" = ? , "GprHosNm" = ? WHERE "GprId" = ?'
                , array( $_SESSION['usuario_id']  , $_SERVER['REMOTE_ADDR'] , gethostbyaddr($_SERVER['REMOTE_ADDR']) , $idSubNivel ));
        return $query;
    }
    
    //NivelGrupo_ctrllr -> nivelGrupo_subnivel()
    public function get_all_subnivel_asignado($idNivelSuperior) {
        $query = $this->db->query('SELECT * FROM "Grupo_predio" JOIN "Nivel_grupo" ON "GprNgrId" = "NgrId" WHERE "GprGprId" = ?', array($idNivelSuperior));
        return $query->result_array();
    }
 
    //NivelGrupo_ctrllr -> nivelGrupo_subnivel()
    public function get_all_subnivel_noasignado($idNivelSuperior) {
        $grupoPredio = $this->get_one_grupoPredio($idNivelSuperior);
        $NgrOrd = $this->get_subnivel_inferior($grupoPredio['GprNgrId']);
        $query = $this->db->query('SELECT * FROM "Grupo_predio" JOIN "Nivel_grupo" ON "GprNgrId" = "NgrId" WHERE "GprGprId" IS NULL AND "NgrOrd" = ?', array($NgrOrd['NgrOrd']));
        return $query->result_array();
    }

    //NivelGrupo_ctrllr -> nivelGrupo_subnivel() 
    public function get_one_grupoPredio($idgrupoPredio) {
        $query = $this->db->query('SELECT * FROM "Grupo_predio" JOIN "Nivel_grupo" ON "GprNgrId" = "NgrId" WHERE "GprId" = ?', array(intval($idgrupoPredio)));
        return $query->row_array();
    }
    
    //OOTomaEstado_ctrllr -> orden_nuevo()
    public function get_one_gprPredio($idgrupoPredio) {
        $query = $this->db->query('SELECT * FROM "Grupo_predio" WHERE "GprId" = ?', array(intval($idgrupoPredio)));
        return $query->row_array();
    }
   //NivelGrupo_ctrllr -> nivelGrupo_detalle() 
    public function get_all_grupoPredio_x_nivelGrupo($idNivelGrupo) {
        $query = $this->db->query('SELECT "Grupo_predio".* FROM "Nivel_grupo" 
        JOIN "Grupo_predio" ON "NgrId" = "GprNgrId"
        WHERE "NgrId" = ? ORDER BY cast("GprCod" as int)', array($idNivelGrupo));
        return $query->result_array();
    }
    
    // $this->get_all_grupo_x_nivel_cantidad() 
    public function get_all_grupoPredio_x_idGrupoPredio($idGrupoPredio) {
        $query = $this->db->query('SELECT * FROM "Grupo_predio" WHERE "GprGprId" = ?', array($idGrupoPredio));
        return $query->result_array();
    }
    
    
    public function get_all_grupoPredio_x_periodo_x_nivelGrupo($idGrupoPredio,$idNivelGrupo,$idPeriodo) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('SELECT "GprId" FROM "Grupo_predio" WHERE "GprGprId" = ? EXCEPT
        SELECT DISTINCT "Lectura"."LecGprId" FROM "Orden_trabajo"
        INNER JOIN "Nivel_grupo" ON "NgrId"="OrtNgrId"
        INNER JOIN "Periodo" ON "OrtPrdId" = "PrdId"
        INNER JOIN "Suborden_lectura" ON "SolOrtId"="OrtId"
        INNER JOIN "Lectura" ON "LecSolId"="SolId"
        WHERE "NgrId" = ? AND "PrdId" = ? AND "SolGprId" = ? AND "LecTipId" = ? ORDER BY "GprId" ', array($idGrupoPredio,$idNivelGrupo,$idPeriodo,$idGrupoPredio,$LecTipId));
        return $query->result_array();
    }
    
    public function get_all_grupoPredio_x_periodo_x_nivelGrupo_Planificados($idGrupoPredio,$idNivelGrupo,$idPeriodo,$fechaToma) {
        $LecTipId = $this->Parametro_model->get_one_detParam_x_codigo('lec_reg')['DprId'];
        $query = $this->db->query('(SELECT "Grupo_predio"."GprId" FROM "Grupo_predio"
        INNER JOIN "Detalle_cron_lectura_periodo" ON "DlpGprId"="GprId" WHERE "GprGprId" = ? AND "DlpFchTm" = ?)
        EXCEPT
        (SELECT DISTINCT "Lectura"."LecGprId" FROM "Orden_trabajo"
        INNER JOIN "Nivel_grupo" ON "NgrId"="OrtNgrId"
        INNER JOIN "Periodo" ON "OrtPrdId" = "PrdId"
        INNER JOIN "Suborden_lectura" ON "SolOrtId"="OrtId"
        INNER JOIN "Lectura" ON "LecSolId"="SolId"
        WHERE "NgrId" = ? AND "PrdId" = ? AND "SolGprId" = ? AND "LecTipId" = ? ) ORDER BY "GprId" ', array($idGrupoPredio,$fechaToma,$idNivelGrupo,$idPeriodo,$idGrupoPredio,$LecTipId));
        return $query->result_array();
    }
    
    public function get_all_grupoPredio_x_periodo_x_nivelGrupo_distribucion($idGrupoPredio,$idNivelGrupo,$idPeriodo) {
        $DbnTipId = $this->Parametro_model->get_one_detParam_x_codigo('dis_reg')['DprId'];
        $query = $this->db->query('SELECT "GprId" FROM "Grupo_predio" WHERE "GprGprId" = ? EXCEPT
        SELECT DISTINCT "Distribucion"."DbnGprId" FROM "Orden_trabajo"
        INNER JOIN "Nivel_grupo" ON "NgrId"="OrtNgrId"
        INNER JOIN "Periodo" ON "OrtPrdId" = "PrdId"
        INNER JOIN "Suborden_distribucion" ON "SodOrtId"="OrtId"
        INNER JOIN "Distribucion" ON "DbnSodId"="SodId"
        WHERE "NgrId" = ? AND "PrdId" = ? AND "SodGprId" = ? AND "DbnTipId" = ?', array($idGrupoPredio,$idNivelGrupo,$idPeriodo,$idGrupoPredio,$DbnTipId));
        return $query->result_array();
    }
    
    //CronogramaLectura_ctrllr -> cronograma_anual_nuevo()
    public function get_all_grupo_x_nivel_cantidad($idNivelGrupo, $periodo) {
        $query = $this->db->query('SELECT "Grupo_predio".* FROM "Nivel_grupo" 
        JOIN "Grupo_predio" ON "NgrId" = "GprNgrId"
        WHERE "NgrId" = ? ORDER BY cast("GprCod" as int)', array($idNivelGrupo));
        $grupos = $query->result_array();
        $nroSubniveles = $this->get_cant_subniveles($idNivelGrupo);
        foreach ($grupos as $key => $grupo) {
            if(is_array($periodo)){
                $grupos[$key]['cantidad']=$this->Catastro_model->get_cant_conexiones_x_grupo($grupo['GprId'], $nroSubniveles);
                $grupos[$key]['subniveles']=$this->get_all_grupoPredio_x_idGrupoPredio($grupo['GprId']);
            }
            else
            {
                $grupos[$key]['cantidad']=$this->Catastro_model->get_cant_conexiones_x_grupo($grupo['GprId'], $nroSubniveles);
                $grupos[$key]['subniveles']=$this->get_all_grupoPredio_x_idGrupoPredio($grupo['GprId']);
            }
        }
        return $grupos;
    }
    
    // $this->get_all_grupo_x_nivel_cantidad
    public function get_cant_subniveles($idNivel){
        $nivel = $this->get_one_nivelGrupo($idNivel);
        $query = $this->db->query('SELECT count(*) as "cantidad" FROM "Nivel_grupo" '
                . 'WHERE "NgrOrd" < ? AND "NgrCntId" = ?', array($nivel['NgrOrd'], $nivel['NgrCntId']));
        return $query->row_array()['cantidad'];
    }

    // NivelGrupo_Ctrll->nivelGrupo_subnivel($idSubNivel)
    public function get_subnivel_inferior($idNivel){
        $nivel = $this->get_one_nivelGrupo($idNivel);
        $query = $this->db->query('SELECT * FROM "Nivel_grupo" WHERE "NgrOrd" < ? AND "NgrCntId" = ? ORDER BY "NgrOrd" DESC LIMIT 1', array($nivel['NgrOrd'], $nivel['NgrCntId']));
        return $query->row_array();
    }
    
    // OTTomaEstado_Ctrll->orden_nuevo()
    public function get_nivel_superior($idNivel){
        $nivel = $this->get_one_nivelGrupo($idNivel);
        $query = $this->db->query('SELECT * FROM "Nivel_grupo" WHERE "NgrOrd" > ? AND "NgrCntId" = ? ORDER BY "NgrOrd" ASC LIMIT 1', array($nivel['NgrOrd'], $nivel['NgrCntId']));
        return $query->row_array();
    }
    
    // OTTomaEstado_Ctrll->orden_nuevo()
    public function get_nivel_inferior($idNivel){
        $nivel = $this->get_one_nivelGrupo($idNivel);
        $query = $this->db->query('SELECT * FROM "Nivel_grupo" WHERE "NgrOrd" < ? AND "NgrCntId" = ? ORDER BY "NgrOrd" ASC LIMIT 1', array($nivel['NgrOrd'], $nivel['NgrCntId']));
        return $query->row_array();
    }
    
    // NivelGrupo_Ctrll-> subciclos_sin_agrupar_notificaciones_json()
    public function get_subciclos_sin_agrupar_notificaciones($idContratante){
        $ciclo = $this->get_last_nivelGrupo($idContratante);
        $SubciclosSinAsignar = $this->get_all_subnivel_noasignado($ciclo['NgrId']);
        return $SubciclosSinAsignar;
    }
    
    //OTTomaEstado_ctrllr->orden_listar
    public function get_niveles_cron($idContratante){
        $query = $this->db->query('SELECT DISTINCT("Grupo_predio"."GprNgrId") FROM "Detalle_cron_lectura_periodo" 
        JOIN "Cron_lectura_periodo" ON "DlpClpId" = "ClpId"
        JOIN "Grupo_predio" ON "DlpGprId" = "GprId"
        JOIN "Nivel_grupo" ON "GprNgrId" = "NgrId"
        WHERE ("DlpFchEn" = CURRENT_DATE OR "DlpFchEn" = (CURRENT_DATE - 1) OR "DlpFchEn" = (CURRENT_DATE + 1)) AND "ClpCntId" = ? ORDER BY "GprNgrId" ', array($idContratante));
        return $query->result_array();
    }
    
}
