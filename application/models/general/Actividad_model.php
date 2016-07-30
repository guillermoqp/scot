<?php

class Actividad_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('general/SubActividad_model');
        $this->load->model('general/Unidad_model');
    }

    public function sanear_string($string)
    {
        $string = trim($string);
        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );
        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
        );
        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
        );
        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
        );
        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
        );
        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C',),
            $string
        );
        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
            array("\\", "¨", "º", "-", "~",
                 "#", "@", "|", "!", "\"",
                 "·", "$", "%", "&", "/",
                 "(", ")", "?", "'", "¡",
                 "¿", "[", "^", "<code>", "]",
                 "+", "}", "{", "¨", "´",
                 ">", "< ", ";", ",", ":",
                 ".", " "),
            '',
            $string
        );
        return $string;
    }
    
    // Valorizacion_ctrllr -> valorizacion_detalle
    public function get_one_actividad($idActividad) 
    {
        $query = $this->db->query('SELECT * FROM "Actividad" '
                . 'WHERE "ActId" = ?', array($idActividad));
        return $query->row_array();
    }

    public function get_id_actividad_x_descripcion($actividadDescripcion) 
    {
        $consulta = sprintf("SELECT \"ActId\" FROM \"Actividad\" WHERE \"ActDes\" ILIKE '%%$actividadDescripcion%%';");
        $query = $this->db->query($consulta);
        return $query->row_array();
    }

    //CronogramaLectura_ctrllr -> construct
    public function get_id_actividad_x_codigo($codigo) {
        $query = $this->db->query('SELECT * FROM "Actividad" '
                . 'WHERE "ActEli" = FALSE AND "ActCod" = ? ORDER BY "ActDes" ASC',array($codigo));
        return $query->row_array()['ActId'];
    }

    public function get_all_actividad() {
        $query = $this->db->query('SELECT * FROM "Actividad" '
                . 'WHERE "ActEli" = FALSE ORDER BY "ActDes" ASC');
        return $query->result_array();
    }

    public function get_all_actividad_subactividad() 
    {
        $actividades = $this->get_all_actividad();

        foreach ($actividades as $key => $actividad) 
        {
            $subactividades = $this->SubActividad_model->get_subactividad_x_actividad($actividad['ActId']);
            foreach ($subactividades as $key2 => $subactividad) 
            {
                $unidad = $this->Unidad_model->get_one_Unidad($subactividad['SacUniId']);
                $subactividades[$key2]['auxUniDes'] = $unidad['UniDes'];
            }
        
            $actividades[$key]['subactividades'] = $subactividades;
        }
        return $actividades;
    }

    public function insert_actividad($descripcion) {
        $sin_signos = $this->sanear_string($descripcion);
        $ActCod = strtolower($sin_signos);
        $query = $this->db->query('INSERT into "Actividad" ("ActDes", "ActEli", "ActFchRg", "ActFchAc", "ActCod", "ActMod") VALUES (?,?,?,?,?,?); '
                , array($descripcion, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $ActCod, FALSE));
        return $query;
    }

    public function insert_grupo_permiso($descripcion,$valor) {
        $idGrpPermiso = false;
        $this->db->trans_start();
        $query = $this->db->query('INSERT into "Grupo_permiso" ("GpeDes", "GpeVal", "GpeEli", "GpeFchRg", "GpeFchAc") VALUES (?,?,?,?,?); '
                , array($descripcion, $valor, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")));
        $idGrpPermiso = $this->db->insert_id();
        $this->db->trans_complete();
        return $idGrpPermiso;
    }
    
    public function insert_grupo_permiso_id($descripcion,$valor,$id) {
        $query = $this->db->query('INSERT into "Grupo_permiso" ("GpeDes", "GpeVal", "GpeEli", "GpeFchRg", "GpeFchAc", "GpeGpeId") VALUES (?,?,?,?,?,?); '
                , array($descripcion, $valor, FALSE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $id));
        return $query;
    }
    
    public function insert_permiso($des,$valor,$idGpe) {
        $query = $this->db->query('INSERT into "Permiso" ("PerDes", "PerVal", "PerFchRg", "PerFchAc", "PerEli", "PerGpeId") VALUES (?,?,?,?,?,?); '
                , array($des, $valor, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), FALSE, $idGpe));
        return $query;
    }
    
    public function update_actividad($idActividad, $descripcion) 
    {
        $query = $this->db->query('UPDATE "Actividad" SET "ActDes" = ?, "ActFchAc" = ? WHERE "ActId" = ? ;'
                , array($descripcion, date("Y-m-d H:i:s"), $idActividad));
        return $query;
    }

    public function delete_actividad($idActividad) 
    {
        $subactividadeseliminadas = $this->SubActividad_model->delete_subactividad_x_actividad($idActividad);
        if($subactividadeseliminadas == FALSE)
        {
            return FALSE;
        }
        else
        {        
            $query = $this->db->query('UPDATE "Actividad" SET "ActEli" = TRUE, "ActFchAc" = ? '
                . 'WHERE "ActId" = ?', array(date("Y-m-d H:i:s"), $idActividad));
            return $query;
        }
    }
    
    public function get_permisos_x_act_cod($ActCod) {
        $query = $this->db->query('SELECT "GpeDes", "GpeVal" FROM "Grupo_permiso" WHERE "GpeGpeId" = '
                . '(SELECT "GpeId" FROM "Grupo_permiso" WHERE "GpeVal" = ?) ORDER BY "GpeDes" ASC;', array($ActCod));
        return $query->result_array();
    }
    
    public function get_all_actividad_permiso() {
        $actividades = $this->get_all_actividad();
        foreach ($actividades as $key => $actividad) {
            $permisos = $this->get_permisos_x_act_cod($actividad['ActCod']);
            $actividades[$key]['permisos'] = $permisos;
        }
        return $actividades;
    }
    
    public function get_id_x_GpeVal($GpeVal)
    {
        $query = $this->db->query('SELECT * FROM "Grupo_permiso" WHERE "GpeVal" = ? ;', array($GpeVal));
        return $query->row_array()['GpeId'];
    }
    
}
