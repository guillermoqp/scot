<?php

class Usuario_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('acceso/Perfil_model');
        $this->load->model('lectura/Lecturista_model');
        $this->load->model('distribucion/Distribuidor_model');
    }

    //LOGIN DE USUARIOS AYUDA DE AUTOCOMPLETADO DE CUENTAS DE USUARIO
    public function autoCompleteUsuario($q)
    {
        $consulta = sprintf("SELECT * FROM \"Usuario\" WHERE \"UsrNomCt\" LIKE '$q%%'");
        $query = $this->db->query($consulta);
        if($query->num_rows >= 0)
        {
            foreach ($query->result_array() as $row)
            {
                $row_set[] = array('label' => $row['UsrNomCt'] , 'usuarioSET' => $row['UsrNomCt'] );
            }
            echo json_encode($row_set);
        }
    }

    public function validarUsuario($nombreUsuario, $password) {
        $query = $this->db->query('SELECT * FROM "Usuario" WHERE "UsrNomCt" = ? and "UsrPsw" = ?', array($nombreUsuario, sha1($password)));
        return $query->row_array();
    }

    public function validarMiUsuario($idUsuario, $password) {
        $query = $this->db->query('SELECT * FROM "Usuario" WHERE "UsrId" = ? and "UsrPsw" = ?', array($idUsuario, $password));
        return $query->row_array();
    }

    public function get_one_usuario($idUsuario) {
        $query = $this->db->query('SELECT * FROM "Usuario" JOIN "Cargo" ON "UsrCarId" = "CarId" WHERE "UsrId" = ?', array($idUsuario));
        return $query->row_array();
    }
    
    public function get_all_usuario($idContratante) {
        $query = $this->db->query('SELECT * FROM "Usuario" JOIN "Cargo" ON "UsrCarId" = "CarId" WHERE "UsrEli" = FALSE AND "UsrCntId" = ?', array($idContratante));
        $usuarios = $query->result_array();
        for ($i = 0; $i < count($usuarios); ++$i) {
            $rolesDes = array();
            $roles = $this->Perfil_model->get_roles_x_usuario($usuarios[$i]['UsrId']);
            $usuarios[$i]['roles'] = array_column($roles, 'RolDes');
        }
        return $usuarios;
    }

    public function get_all_usuario_lecturista() 
    {
        $query = $this->db->query('SELECT "Lecturista".*,"Usuario".* FROM "Lecturista" JOIN "Usuario" ON "LtaUsrId" = "UsrId" WHERE "LtaEli" = FALSE;');
        return $query->result_array();
    }
    
    //OTDistribucion_ctrllr -> orden_ver($idOrdenTrabajo)
    public function get_all_usuario_distribuidor() 
    {
        $query = $this->db->query('SELECT "Distribuidor".*,"Usuario".* FROM "Distribuidor" '
                . 'JOIN "Usuario" ON "DbrUsrId" = "UsrId" WHERE "DbrEli" = FALSE AND "DbrSup" = FALSE ORDER BY "DbrId"');
        return $query->result_array();
    }
    
    
    public function es_lecturista($idUsuario) 
    {
        $query = $this->db->query('SELECT "Lecturista".*,"Usuario".* FROM "Lecturista" JOIN "Usuario" ON "LtaUsrId" = "UsrId" WHERE "UsrId" = ?  AND "LtaEli" = FALSE', array($idUsuario));
        $esLecturista = $query->row_array();
        if(!empty($esLecturista))
        {
            return $esLecturista;
        }
        else
        {
            return FALSE;
        }
    }
    
    
    /*  PARA IMPRESION DE DATOS DE LA ORDEN DE TRABAJO  : DE , PARA */

    public function get_one_usuario_de_contratante($idUsuario) {
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

    public function get_one_usuario_de_contratista($idUsuario) {
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

    /*     * ************************  */

    public function insert_usuario($usuario, $cargo, $roles, $idContratista) {
        $this->db->trans_start();
        if ($usuario['tipo'] == 0) {
            $query = $this->db->query('INSERT into "Usuario" ("UsrNomCt", "UsrPsw", "UsrDni", "UsrApePt", "UsrApeMt", '
                    . '"UsrNomPr", "UsrCor", "UsrTel", "UsrFchEx", "UsrFot", "UsrFchRg", "UsrFchAc", "UsrEli", "UsrCarId", '
                    . '"UsrAct", "UsrCstId", "UsrCntId" , "UsrMov1" , "UsrMov2" , "UsrFchNac" , "UsrSex" , "UsrUsrId" , "UsrIpAdd" , "UsrHosNm" ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
                    , array($usuario['nombreUsr'], sha1($usuario['password']), $usuario['dni'], $usuario['apellidoPat'],
                $usuario['apellidoMat'], $usuario['nombres'], $usuario['email'], $usuario['telefono'], $usuario['fechaEx'],
                $usuario['foto'], date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), false, $cargo, true, $usuario['idContratista'], $idContratista , $usuario['UsrMov1'] , $usuario['UsrMov2'] , $usuario['UsrFchNac'] , $usuario['UsrSex'] , $_SESSION['usuario_id'] , $_SERVER['REMOTE_ADDR'] , gethostbyaddr($_SERVER['REMOTE_ADDR']) ));
        } else {
            $query = $this->db->query('INSERT into "Usuario" ("UsrNomCt", "UsrPsw", "UsrDni", "UsrApePt", "UsrApeMt", '
                    . '"UsrNomPr", "UsrCor", "UsrTel", "UsrFchEx", "UsrFot", "UsrFchRg", "UsrFchAc", "UsrEli", "UsrAct", '
                    . '"UsrCarId", "UsrCntId" , "UsrMov1" , "UsrMov2" , "UsrFchNac" , "UsrSex" , "UsrUsrId" , "UsrIpAdd" , "UsrHosNm" ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
                    , array($usuario['nombreUsr'], sha1($usuario['password']), $usuario['dni'], $usuario['apellidoPat'],
                $usuario['apellidoMat'], $usuario['nombres'], $usuario['email'], $usuario['telefono'], $usuario['fechaEx'],
                $usuario['foto'], date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), false, true, $cargo, $idContratista , $usuario['UsrMov1']  , $usuario['UsrMov2'] , $usuario['UsrFchNac'] , $usuario['UsrSex']  , $_SESSION['usuario_id'] , $_SERVER['REMOTE_ADDR'] , gethostbyaddr($_SERVER['REMOTE_ADDR']) ));
        }
        $idUsuario = $this->db->insert_id();
        
        $this->Lecturista_model->update_lecturista($idUsuario, $roles, $idContratista);
        $this->Distribuidor_model->update_distribuidor($idUsuario, $roles, $idContratista);
        
        foreach ($roles as $rol) {
            $query = $this->db->query('INSERT into "Rol_Usuario" ("RusFchRg", "RusFchAc", "RusUsrId", "RusRolId") VALUES (?,?,?,?)'
                    , array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idUsuario, $rol));
        }
        
        
        $this->db->trans_complete();
        return $idUsuario;
    }

    
    public function update_usuario($usuario, $cargo, $roles, $idUsuario, $idContratista) {
        $query = $this->db->query('UPDATE "Usuario" SET "UsrDni" = ?, "UsrApePt" = ?, "UsrApeMt" = ?, '
                . '"UsrNomPr" = ?, "UsrCor" =?, "UsrTel" = ?, "UsrMov1" = ? , "UsrMov2" = ?, "UsrFchEx" = ?, "UsrFot" = ?, "UsrFchAc" = ?, "UsrAct" = ?, "UsrCarId" = ? , "UsrUsrId" = ? , "UsrIpAdd" = ? , "UsrHosNm" = ? WHERE "UsrId" = ?'
                , array($usuario['dni'], $usuario['apellidoPat'], $usuario['apellidoMat'], $usuario['nombres'], $usuario['email'],
            $usuario['telefono'], $usuario['UsrMov1'], $usuario['UsrMov2']  , $usuario['fechaEx'], $usuario['foto'], date("Y-m-d H:i:s"), true, $cargo, $_SESSION['usuario_id'] , $_SERVER['REMOTE_ADDR'] , gethostbyaddr($_SERVER['REMOTE_ADDR']) , $idUsuario));
        
        
        $this->delete_all_roles_x_usuario($idUsuario);
        $this->Lecturista_model->update_lecturista($idUsuario, $roles, $idContratista);
        $this->Distribuidor_model->update_distribuidor($idUsuario, $roles, $idContratista);
        
        
        foreach ($roles as $rol) {
            $query = $this->db->query('INSERT into "Rol_Usuario" ("RusFchRg", "RusFchAc", "RusUsrId", "RusRolId") VALUES (?,?,?,?); '
                    , array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $idUsuario, $rol));
        }
        return $idUsuario;
    }

    
    public function update_mi_usuario($usuario, $idUsuario) {
        $query = $this->db->query('UPDATE "Usuario" SET "UsrPsw" =?, "UsrCor" =?, "UsrTel" = ?, "UsrMov1" = ?, "UsrMov2" = ?, "UsrFot" = ?, "UsrFchAc" = ?, "UsrAct" = ? , "UsrUsrId" = ? , "UsrIpAdd" = ? , "UsrHosNm" = ? WHERE "UsrId" = ?'
                , array(sha1($usuario['nueva_contrasenia']), $usuario['email'], $usuario['telefono'],  $usuario['UsrMov1'] , $usuario['UsrMov2']  , $usuario['foto'], date("Y-m-d H:i:s"), true, $_SESSION['usuario_id'] , $_SERVER['REMOTE_ADDR'] , gethostbyaddr($_SERVER['REMOTE_ADDR']) , $idUsuario));
        return $idUsuario;
    }

    
    public function update_mi_usuario_no($usuario, $idUsuario) {
        $query = $this->db->query('UPDATE "Usuario" SET "UsrCor" =?, "UsrTel" = ?, "UsrMov1" = ?, "UsrMov2" = ?, "UsrFot" = ?, "UsrFchAc" = ?, "UsrAct" = ? , "UsrUsrId" = ? , "UsrIpAdd" = ? , "UsrHosNm" = ? WHERE "UsrId" = ?'
                , array($usuario['email'], $usuario['telefono'], $usuario['UsrMov1'] , $usuario['UsrMov2']  , $usuario['foto'], date("Y-m-d H:i:s"), true, $idUsuario , $_SESSION['usuario_id'] , $_SERVER['REMOTE_ADDR'] , gethostbyaddr($_SERVER['REMOTE_ADDR']) ));
        return $idUsuario;
    }

    
    public function is_used_username($nombreUsuario) {
        $query = $this->db->query('SELECT * FROM "Usuario" '
                . 'WHERE "UsrNomCt" = ?', array($nombreUsuario));
        $result = $query->result_array();
        return (count($result) > 0);
    }

    
    public function delete_usuario($idUsuario) {
        $query = $this->db->query('UPDATE "Usuario" SET "UsrEli" = TRUE, "UsrFchAc" = ? , "UsrUsrId" = ? , "UsrIpAdd" = ? , "UsrHosNm" = ? WHERE "UsrId" = ?', array(date("Y-m-d H:i:s"), $_SESSION['usuario_id'] , $_SERVER['REMOTE_ADDR'] , gethostbyaddr($_SERVER['REMOTE_ADDR']) , $idUsuario));
        return $query;
    }
    
    public function delete_all_roles_x_usuario($idUsuario) {
        $query = $this->db->query('DELETE FROM "Rol_Usuario" WHERE "RusUsrId" = ?', array($idUsuario));
        return $query;
    }

    //DIRECTORIO
    //Usuario_ctrllr=>ver_directorio_contratante
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
    //Usuario_ctrllr=>ver_directorio_contratista
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
    //Usuario_ctrllr=>ver_directorio_contratista
    public function get_contratista_x_usuario_nivel($idContratante) {
        $query = $this->db->query('SELECT DISTINCT("Contratista".*) FROM "Usuario" JOIN "Cargo" ON "CarId" = "UsrCarId" 
        JOIN "Nivel" ON "CarNvlId" = "NvlId"
        JOIN "Contratista" ON "UsrCstId" = "CstId"
        WHERE "UsrEli" = FALSE AND "UsrCntId" = ? AND "UsrCstId" IS NOT NULL', array($idContratante));
        return $query->row_array();
    }
    
    public function get_recepcion_orden($idContratista, $idContratante) {
        $codPerfil = 'administradorc';
        $query = $this->db->query('SELECT * FROM "Usuario" JOIN "Rol_Usuario" ON "UsrId" = "RusUsrId" '
                . 'JOIN "Rol" ON "RolId" = "RusRolId" WHERE "UsrEli" = FALSE AND "UsrCstId" = ? AND "UsrCntId" = ? AND "RolCod" = ?', array($idContratista,$idContratante,$codPerfil));
        $usuarios = $query->result_array();
        foreach ($usuarios as $key => $usuario) {
            $usuarios[$key]['nombreCompleto'] = $usuario['UsrApePt'].' '.$usuario['UsrApeMt'].' '.$usuario['UsrNomPr'];
        }
        return array_column($usuarios, 'nombreCompleto');
    }

}
