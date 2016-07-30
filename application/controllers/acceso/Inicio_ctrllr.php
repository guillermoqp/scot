<?php

class Inicio_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('lectura/OTTomaEstado_model');
        $this->load->model('acceso/Usuario_model');
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->data['menu']['padre'] = '';
        $this->data['menu']['hijo'] = '';
    }

    public function mostrar() {
        $this->load->view('acceso/Login');
    }
    
    public function autoCompleteUsuario()
    {
        if (isset($_GET['term']))
        {
            $q = strtolower($_GET['term']);
            $this->Usuario_model->autoCompleteUsuario($q);
        }
    }

    public function login() {
        $ajax = $this->input->get('ajax');
        if (isset($_SESSION['usuario_nombre']) && isset($_SESSION['usuario_id'])) {
            redirect(base_url() . 'inicio');
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('username', 'Nombre de Usuario', 'required');
            $this->form_validation->set_rules('password', 'Contraseña', 'required');

            /* SI LOS DATOS DE LA VALIDACION SON VALIDOS, BUSCAR EN LA TABLA USUARIOS, 
              CASO CONTRARIO RETORNAR FALSE PARA EL MODAL DE LOGIN DE LA VISTA LOGIN */
            if ($this->form_validation->run() == TRUE) {
                $usuario = $this->Usuario_model->validarUsuario($this->input->post('username'), $this->input->post('password'));

                if (count($usuario) > 0) {
                    if ((time() - (60 * 60 * 24)) < strtotime($usuario['UsrFchEx'])) {

                        $_SESSION['usuario_nombre'] = $usuario['UsrNomCt'] . ' ' . $usuario['UsrApePt'] . ' ' . $usuario['UsrApeMt'];
                        $_SESSION['usuario_id'] = $usuario['UsrId'];

                        if ($ajax == true) {
                            $json = array('result' => true, 'mensaje' => 'OK');
                            header('Access-Control-Allow-Origin: *');
                            header('Content-Type: application/x-json; charset=utf-8');
                            echo json_encode($json);
                        } else {
                            /* REDIRECCIONAR AL CONTROLADOR PRINCIPAL */
                            if (is_null($usuario['UsrCstId'])) {
                                redirect(base_url() . 'inicio');
                            } else {
                                redirect(base_url() . 'cst/inicio');
                            }
                        }
                    } else {
                        $json = array('result' => false, 'mensaje' => 'Su cuenta ha expirado. Sírvase comunicarse con el administrador del Sistema.');
                        header('Access-Control-Allow-Origin: *');
                        header('Content-Type: application/x-json; charset=utf-8');
                        echo json_encode($json);
                    }
                } else {
                    /* si no se encontro al usuario solicitado */
                    $json = array('result' => false, 'mensaje' => 'Los datos de acceso son incorrectos. Por favor inténtelo de nuevo.');
                    header('Access-Control-Allow-Origin: *');
                    header('Content-Type: application/x-json; charset=utf-8');
                    echo json_encode($json);
                }
            } else {
                $json = array('result' => false, 'mensaje' => 'Complete los campos.');
                header('Access-Control-Allow-Origin: *');
                header('Content-Type: application/x-json; charset=utf-8');
                echo json_encode($json);
            }
        }
    }

    public function login_movil() {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Content-Type: application/x-json; charset=utf-8');
        $ajax = $this->input->get('ajax');
        /* SI LOS DATOS DE LA VALIDACION SON VALIDOS, BUSCAR EN LA TABLA USUARIOS, 
          CASO CONTRARIO RETORNAR FALSE PARA EL MODAL DE LOGIN DE LA VISTA LOGIN */
        $usuario = $this->Usuario_model->validarUsuario($this->input->post('username'), $this->input->post('password'));
        if (count($usuario) > 0) {
            $esLecturista = $this->Usuario_model->es_lecturista($usuario['UsrId']);
            if ($esLecturista != FALSE) {
                if ((time() - (60 * 60 * 24)) < strtotime($usuario['UsrFchEx'])) {
                    if ($ajax == true) {
                        //date("Y-m-d")
                        $json = array('result' => true , 'datos' => $esLecturista, 'fch_sesion' => '2016-07-26');
                    }
                } else {
                    $json = array('result' => false, 'mensaje' => 'Su cuenta ha expirado. Sírvase comunicarse con el administrador del Sistema.');
                }
            } else {
                $json = array('result' => false, 'mensaje' => 'Solo Pueden Acceder los lecturistas registrados.');
            }
        } else {
            /* si no se encontro al usuario solicitado */
            $json = array('result' => false, 'mensaje' => 'Los datos de acceso son incorrectos. Por favor intentelo de nuevo.');
        }
        
        echo json_encode($json);
    }

    public function inicio() {
        $this->acceso_cls->isLogin();
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $bienvenido = $this->input->get('bienvenido');
        if (isset($bienvenido)) {
            $dias = (strtotime("now") - strtotime($this->data['userdata']['UsrFchEx'])) / 86400;
            $dias = abs($dias);
            $dias = floor($dias) + 1;
            if ($dias <= 15) {
                $this->session->set_flashdata('mensaje', array('error', 'Le quedan: ' . $dias . ' dias de acceso al sistema.'));
            }
        }
        if (is_null($this->data['userdata']['UsrCstId'])) {
            $this->data['view'] = 'acceso/Inicio';
            $this->data['proceso'] = 'Administración General';
            $this->data['breadcrumbs'] = array();
            $this->load->view('template/Master', $this->data);
        } else {
            redirect(base_url() . 'cst/inicio');
        }
    }

    public function sinPermiso() {
        $this->acceso_cls->isLogin();
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['proceso'] = 'Administración General';
        $this->data['menu']['padre'] = '';
        $this->data['menu']['hijo'] = '';
        $this->data['mensaje'] = array('error', 'No tiene el permiso para ver la interfaz.');
        $this->data['breadcrumbs'] = array();
        $this->data['view'] = 'acceso/401';
        
        if (is_null($this->data['userdata']['UsrCstId'])) {
            $this->load->view('template/Master', $this->data);
        } else {
            $this->load->view('contratista/template/Master_cst_view', $this->data);
        }
    }

    public function logout() {
        //unset($_SESSION['usuario_nombre'], $_SESSION['usuario_id'], $_SESSION['userdata']);
        session_destroy();
        redirect(base_url() . 'login');
    }

}
