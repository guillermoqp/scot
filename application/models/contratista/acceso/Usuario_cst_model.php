<?php

class Usuario_cst_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('acceso/Perfil_model');
    }

    public function validarUsuario($nombreUsuario, $password) {
        $query = $this->db->query('SELECT * FROM "Usuario" '
                . 'WHERE "UsrNomCt" = ? and "UsrPsw" = ?', array($nombreUsuario, sha1($password)));
        return $query->row_array();
    }
    
    public function validarMiUsuario($idUsuario, $password) {
        $query = $this->db->query('SELECT * FROM "Usuario" '
                . 'WHERE "UsrId" = ? and "UsrPsw" = ?', array($idUsuario, $password));
        return $query->row_array();
    }
    
    public function get_one_usuario($idUsuario) {
        $query = $this->db->query('SELECT * FROM "Usuario" '
                . 'WHERE "UsrId" = ?', array($idUsuario));
        return $query->row_array();
    }

    public function get_all_usuario($idContratante) {
        $query = $this->db->query('SELECT * FROM "Usuario" '
                . 'WHERE "UsrEli" = FALSE AND "UsrCntId" = ?',array($idContratante));
        $usuarios = $query->result_array();
        for ($i = 0; $i < count($usuarios); ++$i) {
            $rolesDes = array();
            $roles = $this->Perfil_model->get_roles_x_usuario($usuarios[$i]['UsrId']);
            $usuarios[$i]['roles'] = array_column($roles, 'RolDes');
        }
        return $usuarios;
    }
    
    //OTTomaEstado_cst_ctrllr -> orden_ver($idOrdenTrabajo)
    public function get_all_usuario_lecturista() 
    {
        $query = $this->db->query('SELECT "Lecturista".*,"Usuario".* FROM "Lecturista" '
                . 'JOIN "Usuario" ON "LtaUsrId" = "UsrId" WHERE "LtaEli" = FALSE AND "LtaSup" = FALSE ORDER BY "LtaId"');
        return $query->result_array();
    }

    //OTTomaEstado_cst_ctrllr -> orden_avance($idOrdenTrabajo)
    public function get_one_usuario_lecturista($idLecturista) 
    {
        $query = $this->db->query('SELECT "Lecturista".*,"Usuario".* FROM "Lecturista" '
                . 'JOIN "Usuario" ON "LtaUsrId" = "UsrId" WHERE "LtaId" = ? AND "LtaEli" = FALSE;', array($idLecturista));
        return $query->row_array();
    }
    
    //OTDistribucion_cst_ctrllr -> orden_ver($idOrdenTrabajo)
    public function get_all_usuario_distribuidor() 
    {
        $query = $this->db->query('SELECT "Distribuidor".*,"Usuario".* FROM "Distribuidor" '
                . 'JOIN "Usuario" ON "DbrUsrId" = "UsrId" WHERE "DbrEli" = FALSE AND "DbrSup" = FALSE ORDER BY "DbrId"');
        return $query->result_array();
    }
    
    

    
    /*  PARA IMPRESION DE DATOS DE LA ORDEN DE TRABAJO  : DE , PARA */
    public function get_one_usuario_de_contratante($idUsuario) 
    {
        $query = $this->db->query('SELECT "Usuario"."UsrApePt", '
                . '"Usuario"."UsrApeMt", '
                . '"Usuario"."UsrNomPr",'
                . '"Usuario"."UsrCor", '
                . '"Usuario"."UsrTel", '
                . '"Empresa"."EmpRaz", '
                . '"Rol"."RolDes" FROM "Usuario" '
                . 'JOIN "Contratante" ON "UsrCntId" = "CntId" '
                . 'JOIN "Empresa" ON "CntEmpId" = "EmpId" '
                . 'JOIN "Rol_Usuario" ON "RusUsrId" = "UsrId" '
                . 'JOIN "Rol" ON "RusRolId" = "RolId" WHERE "UsrEli" = FALSE AND "UsrId" =  ?;', array($idUsuario));
        return $query->row_array();
    }

    public function get_one_usuario_de_contratista($idUsuario) 
    {
        $query = $this->db->query('SELECT "Usuario"."UsrApePt", '
                . '"Usuario"."UsrApeMt",'
                . '"Usuario"."UsrNomPr",'
                . '"Usuario"."UsrCor",'
                . '"Usuario"."UsrTel",'
                . '"Rol"."RolDes",'
                . '"Contratista"."CstRaz" FROM "Usuario" '
                . 'JOIN "Contratista" ON "UsrCstId" = "CstId" '
                . 'JOIN "Rol_Usuario" ON "RusUsrId" = "UsrId" '
                . 'JOIN "Rol" ON "RusRolId" = "RolId" WHERE "UsrEli" = FALSE AND "UsrId" =  ?;', array($idUsuario));
        return $query->row_array();
    }
    /*   *************************  */
    
    
    public function update_mi_usuario($usuario, $idUsuario) {
        $query = $this->db->query('UPDATE "Usuario" SET "UsrPsw" =?, "UsrCor" =?, "UsrTel" = ?, "UsrMov1" = ?, "UsrMov2" = ?, "UsrFot" = ?, "UsrFchAc" = ?, "UsrAct" = ? WHERE "UsrId" = ?'
                , array(sha1($usuario['nueva_contrasenia']), $usuario['email'], $usuario['telefono'], $usuario['UsrMov1'] , $usuario['UsrMov2'] , $usuario['foto'], date("Y-m-d H:i:s"), true, $idUsuario));
        return $idUsuario;
    }
    
    public function update_mi_usuario_no($usuario, $idUsuario) {
        $query = $this->db->query('UPDATE "Usuario" SET "UsrCor" =?, "UsrTel" = ?, "UsrMov1" = ?, "UsrMov2" = ?, "UsrFot" = ?, "UsrFchAc" = ?, "UsrAct" = ? WHERE "UsrId" = ? ;'
                , array($usuario['email'], $usuario['telefono'], $usuario['UsrMov1'] , $usuario['UsrMov2']  , $usuario['foto'], date("Y-m-d H:i:s"), true, $idUsuario));
        return $idUsuario;
    }
    
    public function is_used_username($nombreUsuario) {
        $query = $this->db->query('SELECT * FROM "Usuario" '
                . 'WHERE "UsrNomCt" = ?', array($nombreUsuario));
        $result = $query->result_array();
        return (count($result)>0);
    }
    
    //DIRECTORIO
    //Usuario_cst_ctrllr=>ver_directorio_contratante
    public function get_usuarios_contratante_x_nivel($idContratante) {
        $query = $this->db->query('SELECT * FROM "Usuario" JOIN "Cargo" ON "CarId" = "UsrCarId" '
                . 'JOIN "Nivel" ON "CarNvlId" = "NvlId" WHERE "UsrEli" = FALSE AND "UsrCntId" = ? AND "UsrCstId" IS NULL ORDER BY "NvlDes","CarDes","UsrApePt"', array($idContratante));
        $usuarios = $query->result_array();
        $niveles=array();
        foreach ($usuarios as $key => $usuario) {
            $niveles[$usuario['NvlId']][$usuario['CarId']][$key] = $usuario;
        }
        return $niveles;
    }
    
    //DIRECTORIO
    //Usuario_cst_ctrllr=>ver_directorio_contratista
    public function get_usuarios_contratista_x_nivel($idContratante) {
        $query = $this->db->query('SELECT * FROM "Usuario" JOIN "Cargo" ON "CarId" = "UsrCarId" 
        JOIN "Nivel" ON "CarNvlId" = "NvlId" WHERE "UsrEli" = FALSE AND "UsrCntId" = ? AND "UsrCstId" IS NOT NULL ORDER BY "NvlDes","CarDes","UsrApePt"', array($idContratante));
        $usuarios = $query->result_array();
        $niveles=array();
        foreach ($usuarios as $key => $usuario) {
            $niveles[$usuario['NvlId']][$usuario['CarId']][$key] = $usuario;
        }
        return $niveles;
    }
    
    //DIRECTORIO
    //Usuario_cst_ctrllr=>ver_directorio_contratista
    public function get_contratista_x_usuario_nivel($idContratante) {
        $query = $this->db->query('SELECT DISTINCT("Contratista".*) FROM "Usuario" JOIN "Cargo" ON "CarId" = "UsrCarId" 
        JOIN "Nivel" ON "CarNvlId" = "NvlId"
        JOIN "Contratista" ON "UsrCstId" = "CstId"
        WHERE "UsrEli" = FALSE AND "UsrCntId" = ? AND "UsrCstId" IS NOT NULL', array($idContratante));
        return $query->row_array();
    }
}
