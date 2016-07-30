<?php

class Usuario_cst_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('contratista/acceso/Usuario_cst_model');
        $this->load->model('acceso/Cargo_model');
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();
        $this->data['userdata'] = $this->acceso_cls->get_userdata();
        $this->data['proceso'] = 'Administración General';
        $this->data['menu']['padre'] = 'permisos';
        $this->data['menu']['hijo'] = 'usuario';
    }

    public function mi_usuario_editar($idUsuario) {
        $this->data['menu']['padre'] = '';
        $this->data['menu']['hijo'] = '';
        $usuario = $this->Usuario_cst_model->get_one_usuario($idUsuario);

        /*if (!isset($this->userdata['contratista'])) {
            show_404();
        }*/
        if ($idUsuario != $_SESSION['usuario_id']) {
            show_404();
        }
        if (!isset($usuario)) {
            show_404();
        }
        if ($usuario['UsrCntId'] != $this->data['userdata']['contratante']['CntId']) {
            show_404();
        }
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Numero de DNI', 'required');
            $this->form_validation->set_rules('telefono', 'Telefono', 'required');
            /*$this->form_validation->set_rules('password', 'Contraseña', 'required');
            $this->form_validation->set_rules('nueva_contrasenia', 'Nueva Contraseña', 'required');
            $this->form_validation->set_rules('nueva_contrasenia2', 'Nueva Contraseña 2', 'required');*/
            if ($this->form_validation->run() == TRUE) {
                $usuario['email'] = $this->input->post('email');
                
                $usuario['telefono'] = $this->input->post('telefono');
                $usuario['UsrMov1'] = $this->input->post('UsrMov1');
                $usuario['UsrMov2'] = $this->input->post('UsrMov2');
                
                $usuario['password'] = $this->input->post('password');
                $usuario['nueva_contrasenia'] = $this->input->post('nueva_contrasenia');
                $usuario['nueva_contrasenia2'] = $this->input->post('nueva_contrasenia2');
                $usuario['foto'] = $usuario['UsrFot'];
                $imagen = $this->do_upload('foto');
                if ($imagen) {
                    $usuario['foto'] = 'assets/uploads/usuarios/' . $imagen;
                }
                $contra=$usuario['password'];
                if($contra!=null)
                {
                    $contra=sha1($usuario['password']);
                    $datoUsuario = $this->Usuario_cst_model->validarMiUsuario($usuario['UsrId'], $contra);
                    if (count($datoUsuario) > 0)
                    {
                        if($usuario['nueva_contrasenia']!=null)
                        {
                            $this->Usuario_cst_model->update_mi_usuario($usuario,$idUsuario);
                            $this->session->set_flashdata('mensaje', array('exito', 'El usuario se ha actualizado satisfactoriamente.'));
                            redirect(base_url() . 'cst/mi_usuario/editar/' . $idUsuario);
                        }
                        else 
                        {
                            $this->session->set_flashdata('mensaje', array('error', 'Falto ingresar Contraseña Nueva'));
                        }
                    }
                    else
                    {
                        $this->session->set_flashdata('mensaje', array('error', 'Contraseña Anterior Incorrecta.'));
                    }
                }
                else
                {
                    $this->Usuario_cst_model->update_mi_usuario_no($usuario,$idUsuario);
                    $this->session->set_flashdata('mensaje', array('exito', 'El usuario se ha actualizado satisfactoriamente.'));
                    redirect(base_url() . 'cst/mi_usuario/editar/' . $idUsuario);
                }
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['usuarioEdit'] = $usuario;
        $this->data['accion'] = 'editar';
        $this->data['proceso'] = 'Configurar Usuario';
        $this->data['view'] = 'contratista/acceso/Mi_usuario_cst_view';
        $this->data['breadcrumbs'] = array(array('Editar Usuario', ''));
        $this->load->view('contratista/template/Master_cst_view', $this->data);
    }
    
    public function ajax_obtener_cargos($tipo) {
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($this->Cargo_model->get_cargos_x_tipo($tipo, $this->data['userdata']['contratante']['CntId'])));
    }

    public function do_upload($field_name) {
        $this->load->library('upload');
        $this->load->helper('file');

        $image_upload_folder = FCPATH . 'assets/uploads/usuarios';

        if (!file_exists($image_upload_folder)) {
            mkdir($image_upload_folder, DIR_WRITE_MODE, true);
        }

        $this->upload_config = array(
            'upload_path' => $image_upload_folder,
            'allowed_types' => 'png|jpg|jpeg|bmp|JPEG||PNG||JPG||pdf||BMP',
            'max_size' => 2048,
            'remove_space' => TRUE,
            'encrypt_name' => TRUE,
        );
        $this->upload->initialize($this->upload_config);
        if (!$this->upload->do_upload($field_name)) {
            $upload_error = $this->upload->display_errors();
            /* print_r($upload_error);
              exit(); */
            return false;
        } else {
            $file_info = array($this->upload->data());
            return $file_info[0]['file_name'];
        }
    }
    
    public function directorio_contratante() {
        $this->acceso_cls->verificarPermiso('ver_directorio_contratante');
        $this->data['niveles'] = $this->Usuario_cst_model->get_usuarios_contratante_x_nivel($this->data['userdata']['contratante']['CntId']);
        $this->data['view'] = 'acceso/Dir_contratante_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Directorio EPS', ''));
        $this->data['menu']['padre'] = 'directorio';
        $this->data['menu']['hijo'] = 'directorio_contratante';
        $this->load->view('contratista/template/Master_cst_view', $this->data);
    }
    
    public function directorio_contratista() {
        $this->acceso_cls->verificarPermiso('ver_directorio_contratante');
        $this->data['niveles'] = $this->Usuario_cst_model->get_usuarios_contratista_x_nivel($this->data['userdata']['contratante']['CntId']);
        $this->data['contsta'] = $this->Usuario_cst_model->get_contratista_x_usuario_nivel($this->data['userdata']['contratante']['CntId']);
        $this->data['view'] = 'acceso/Dir_contratista_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Directorio Contratista', ''));
        $this->data['menu']['padre'] = 'directorio';
        $this->data['menu']['hijo'] = 'directorio_contratista';
        $this->load->view('contratista/template/Master_cst_view', $this->data);
    }
    
    }
