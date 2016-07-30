<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Acceso_cls {

    var $userdata;

    public function isLogin() {
        if (!(isset($_SESSION['usuario_nombre'])) || !(isset($_SESSION['usuario_id']))) {
            redirect(base_url() . 'login');
        }
    }

    public function get_permisos($idUsuario) {
        $this->load->model('acceso/Permiso_model');
        return $this->Usuario_model->get_permisos_x_usuario($idUsuario);
    }

    public function get_userdata($idUsuario = 'no importa') {
        if (!isset($_SESSION['userdata'])) {
            $CI = & get_instance();
            $CI->load->model('acceso/Permiso_model');
            $CI->load->model('acceso/Perfil_model');
            $CI->load->model('acceso/Usuario_model');
            $CI->load->model('general/Contratante_model');
            $CI->load->model('general/Contratista_model');
            $CI->load->model('general/Actividad_model');
            $this->userdata = $CI->Usuario_model->get_one_usuario($_SESSION['usuario_id']);
            $this->userdata['roles'] = $CI->Perfil_model->get_roles_x_usuario($this->userdata['UsrId']);
            $this->userdata['cargo'] = array_column($this->userdata['roles'], 'RolDes');
            $this->userdata['actividades'] = $CI->Actividad_model->get_all_actividad_permiso();
            $this->userdata['permisos'] = $CI->Permiso_model->get_permisos_x_usuario($this->userdata['UsrId']);
            $this->userdata['contratante'] = $CI->Contratante_model->get_one_contratante_empresa($this->userdata['UsrCntId']);
            $this->userdata['contratista'] = $CI->Contratista_model->get_one_contratista($this->userdata['UsrCstId']);

            $_SESSION['userdata'] = $this->userdata;
        }else{
            $this->userdata = $_SESSION['userdata'];
        }
        return $this->userdata;
    }

    public function recargar_userdata() {
        unset($_SESSION['userdata']);
        $this->get_userdata();
    }

    public function verificarPermiso($valor) {
        $tienePermiso = FALSE;
        foreach ($this->userdata['permisos'] as $grupo) {
            foreach ($grupo as $subgrupo) {
                foreach ($subgrupo as $permiso) {
                    if ($permiso['PerVal'] == $valor) {
                        $tienePermiso = TRUE;
                    }
                }
            }
        }
        if (!$tienePermiso) {
            redirect(base_url() . '401');
        }
    }
    
    public function verificarPermisoCst($valor) {
        $tienePermiso = FALSE;
        foreach ($this->userdata['permisos'] as $grupo) {
            foreach ($grupo as $subgrupo) {
                foreach ($subgrupo as $permiso) {
                    if ($permiso['PerVal'] == $valor AND $this->userdata['contratista']) {
                        $tienePermiso = TRUE;
                    }
                }
            }
        }
        if (!$tienePermiso) {
            //redirect(base_url() . '401');
            show_404();
        }
    }

    public function tienePermiso($valor) {
        $tienePermiso = FALSE;
        foreach ($this->userdata['permisos'] as $grupo) {
            foreach ($grupo as $subgrupo) {
                foreach ($subgrupo as $permiso) {
                    if ($permiso['PerVal'] == $valor) {
                        $tienePermiso = TRUE;
                    }
                }
            }
        }
        return $tienePermiso;
    }
}
