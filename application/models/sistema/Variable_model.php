<?php

class Variable_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function get_one_variable($idVariable) {
        $query = $this->db->query('SELECT * FROM "Variable"WHERE "VarId" = ?', array($idVariable));
        return $query->row_array();
    }
    public function get_one_variable_cod($codVariable) {
        $query = $this->db->query('SELECT * FROM "Variable" WHERE "VarCod" = ?', array($codVariable));
        return $query->row_array();
    }
    public function get_all_variable($idContratante) {
        $query = $this->db->query('SELECT * FROM "Variable" WHERE "VarEli" = FALSE AND "VarCntId"= ? ORDER BY "VarDes" ASC',array($idContratante));
        return $query->result_array();
    }
    
    public function insert_variable($codigo, $descripcion, $valor,  $idContratante , $VarPrdId ) {
        $query = $this->db->query('INSERT INTO "Variable" ("VarDes", "VarCod", "VarVal", "VarEli", "VarFchRg", "VarFchAc", "VarCntId" , "VarPrdId" , "VarUsrId" , "VarIpAdd" , "VarHosNm" ) VALUES (?,?,?,?,?,?,?,?,?,?,?)'
                , array($descripcion, $codigo, $valor, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idContratante , $VarPrdId  , $_SESSION['usuario_id'] , $_SERVER['REMOTE_ADDR'] , gethostbyaddr($_SERVER['REMOTE_ADDR']) ));
        return $query;
    }
    
    public function update_variable( $codigo , $descripcion , $valor , $VarPrdId , $idVariable ) {
        $query = $this->db->query('UPDATE "Variable" SET "VarCod" = ?, "VarDes" = ?, "VarVal" = ?, "VarFchAc" = ?, "VarPrdId" = ?, "VarUsrId" = ?, "VarIpAdd" = ?, "VarHosNm" = ? WHERE "VarId" = ?'
                , array( $codigo, $descripcion, $valor, date("Y-m-d H:i:s"), $VarPrdId, $_SESSION['usuario_id'] , $_SERVER['REMOTE_ADDR'] , gethostbyaddr($_SERVER['REMOTE_ADDR']) ,  $idVariable));
        return $query;
    }
    
    public function delete_variable($idVariable) {
        $query = $this->db->query('UPDATE "Variable" SET "VarEli" = TRUE, "VarFchAc" = ? WHERE "VarId" = ?', array(date("Y-m-d H:i:s"), $idVariable));
        return $query;
    }
}
