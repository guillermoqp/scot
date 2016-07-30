<?php

class Usuario_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('acceso/Usuario_model');
        $this->load->model('acceso/Perfil_model');
        $this->load->model('acceso/Cargo_model');
        $this->load->model('general/Empresa_model');
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();
        $this->data['userdata'] = $this->acceso_cls->get_userdata();
        $this->data['proceso'] = 'Administración General';
        $this->data['menu']['padre'] = 'permisos';
        $this->data['menu']['hijo'] = 'usuario';
    }

    public function usuario_listar() {
        $this->acceso_cls->verificarPermiso('ver_usuario');
        $this->data['usuarios'] = $this->Usuario_model->get_all_usuario($this->data['userdata']['contratante']['CntId']);
        $this->data['view'] = 'acceso/Usuarios_view';
        $this->data['breadcrumbs'] = array(array('Usuarios', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function usuario_nuevo() {
        $this->acceso_cls->verificarPermiso('editar_usuario');
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('apellidoPat', 'Apellido Paterno', 'required');
            $this->form_validation->set_rules('apellidoMat', 'Apellido Materno', 'required');
            $this->form_validation->set_rules('nombres', 'Nombres', 'required');
            $this->form_validation->set_rules('dni', 'Numero de DNI', 'required');
            $this->form_validation->set_rules('nombreUsuario', 'Nombre de Usuario', 'required');
            $this->form_validation->set_rules('password', 'Contraseña', 'required');
            $this->form_validation->set_rules('email', 'Numero de DNI', 'required');
            $this->form_validation->set_rules('UsrFchNac', 'Fecha de Nacimiento', 'required');
            $this->form_validation->set_rules('UsrSex', 'Sexo', 'required');
            
            
            $this->form_validation->set_rules('UsrMov1', 'Movil 1', 'required');
            $this->form_validation->set_rules('telefono', 'Telefono', 'required');
            
            $this->form_validation->set_rules('fchEx', 'Fecha de expiración', 'required');
            $this->form_validation->set_rules('tipo', 'Tipo de usuario', 'required');
            $this->form_validation->set_rules('cargo', 'Cargo', 'required');
            if ($this->form_validation->run() == TRUE) {
                if ($this->Usuario_model->is_used_username($this->input->post('nombreUsuario'))) {
                    $this->session->set_flashdata('mensaje', array('error', 'El nombre de usuario ya está siendo usado'));
                } else {
                    $usuario['apellidoPat'] = $this->input->post('apellidoPat');
                    $usuario['apellidoMat'] = $this->input->post('apellidoMat');
                    $usuario['nombres'] = $this->input->post('nombres');
                    $usuario['dni'] = $this->input->post('dni');
                    
                    $usuario['UsrFchNac'] = $this->input->post('UsrFchNac');
                    $usuario['UsrSex'] = $this->input->post('UsrSex');
                    
                    
                    $usuario['nombreUsr'] = $this->input->post('nombreUsuario');
                    $usuario['password'] = $this->input->post('password');
                    $usuario['email'] = $this->input->post('email');
                    $usuario['telefono'] = $this->input->post('telefono');
                    
                    $usuario['UsrMov1'] = $this->input->post('UsrMov1');
                    $usuario['UsrMov2'] = $this->input->post('UsrMov2');
                    
                    $usuario['fechaEx'] = $this->input->post('fchEx');
                    $usuario['tipo'] = $this->input->post('tipo');
                    
                    if ($usuario['tipo'] == 0) {
                        $usuario['idContratista'] = $this->input->post('idContratista');
                    }
                    $imagen = $this->do_upload('foto');
                    if ($imagen) {
                        $usuario['foto'] = 'assets/uploads/usuarios/' . $imagen;
                    } else {
                        $usuario['foto'] = '';
                    }
                    $cargo = $this->input->post('cargo');
                    $roles = $this->input->post('roles');
                    
                    var_dump($usuario);
                    
                    $resultado = $this->Usuario_model->insert_usuario($usuario, $cargo, $roles, $this->data['userdata']['contratante']['CntId']);
                    if($resultado)
                    {
                       $this->session->set_flashdata('mensaje', array('exito', 'El usuario se ha registrado satisfactoriamente.'));
                       redirect(base_url() . 'permisos/usuarios'); 
                    }else{
                       $this->session->set_flashdata('error', 'Ocurrio algun error al Guardar los datos.'); 
                    }
                }
            } else {
                $this->session->set_flashdata('error', 'Complete los campos');
            }
        }
        $this->data['roles'] = $this->Perfil_model->get_roles_x_tipo(1, $this->data['userdata']['contratante']['CntId']);
        if ($this->acceso_cls->tienePermiso('ver_contratante')) {
            $this->load->model('Empresa_model');
            $this->data['contratantes'] = $this->Empresa_model->get_empresa_contratante();
        } else {
            $this->data['contratantes'] = array($this->data['userdata']['contratante']);
        }
        $this->load->model('general/Contratista_model');
        $this->data['contratistas'] = $this->Contratista_model->get_contratista_x_contratante($this->data['userdata']['contratante']['CntId']);
        $this->data['cargos'] = $this->Cargo_model->get_cargos_x_tipo(1,$this->data['userdata']['contratante']['CntId']);
        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'acceso/Usuario_view';
        $this->data['breadcrumbs'] = array(array('Usuarios', 'permisos/usuarios'), array('Registrar Usuario', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function usuario_editar($idUsuario) {
        $usuario = $this->Usuario_model->get_one_usuario($idUsuario);
        if (!isset($usuario)) {
            show_404();
        }
        if ($usuario['UsrCntId'] != $this->data['userdata']['contratante']['CntId']) {
            show_404();
        }
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_usuario');
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('apellidoPat', 'Apellido Paterno', 'required');
            $this->form_validation->set_rules('apellidoMat', 'Apellido Materno', 'required');
            $this->form_validation->set_rules('nombres', 'Nombres', 'required');
            $this->form_validation->set_rules('dni', 'Numero de DNI', 'required');
            $this->form_validation->set_rules('email', 'Numero de DNI', 'required');
            
            //$this->form_validation->set_rules('UsrMov1', 'Movil 1', 'required');
            $this->form_validation->set_rules('telefono', 'Telefono', 'required');
            
            $this->form_validation->set_rules('fchEx', 'Fecha de expiración', 'required');
            $this->form_validation->set_rules('cargo', 'Cargo', 'required');
            if ($this->form_validation->run() == TRUE) {
                $usuario['apellidoPat'] = $this->input->post('apellidoPat');
                $usuario['apellidoMat'] = $this->input->post('apellidoMat');
                $usuario['nombres'] = $this->input->post('nombres');
                $usuario['dni'] = $this->input->post('dni');
                $usuario['nombreUsr'] = $this->input->post('nombreUsuario');
                $usuario['password'] = $this->input->post('password');
                $usuario['email'] = $this->input->post('email');
                
                $usuario['UsrMov1'] = $this->input->post('UsrMov1');
                $usuario['UsrMov2'] = $this->input->post('UsrMov2');
                $usuario['telefono'] = $this->input->post('telefono');
                
                $usuario['fechaEx'] = $this->input->post('fchEx');
                $usuario['foto'] = $usuario['UsrFot'];
                $imagen = $this->do_upload('foto');
                if ($imagen) {
                    $usuario['foto'] = 'assets/uploads/usuarios/' . $imagen;
                }
                $cargo = $this->input->post('cargo');
                $roles = $this->input->post('roles');
                $this->Usuario_model->update_usuario($usuario, $cargo, $roles, $idUsuario, $this->data['userdata']['contratante']['CntId']);
                $this->acceso_cls->recargar_userdata();
                $this->session->set_flashdata('mensaje', array('exito', 'El usuario se ha actualizado satisfactoriamente.'));
                redirect(base_url() . 'permisos/usuario/editar/' . $idUsuario);
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['roles'] = $this->Perfil_model->get_all_rol($this->data['userdata']['contratante']['CntId']);
        $tipo = is_null($usuario['UsrCstId'])?1:0;
        $this->data['cargos'] = $this->Cargo_model->get_cargos_x_tipo($tipo,$this->data['userdata']['contratante']['CntId']);
        $this->data['usuarioEdit'] = $usuario;
        $this->data['rolesId'] = array_column($this->Perfil_model->get_roles_x_usuario($usuario['UsrId']), 'RolId');
        $this->data['contratantes'] = array($this->data['userdata']['contratante']);
        $this->load->model('general/Contratista_model');
        $this->data['contratistas'] = $this->Contratista_model->get_contratista_x_contratante($this->data['userdata']['contratante']['CntId']);
        $this->data['accion'] = 'editar';
        $this->data['view'] = 'acceso/Usuario_view';
        $this->data['breadcrumbs'] = array(array('Usuarios', 'permisos/usuarios'), array('Editar Usuario', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function mi_usuario_editar($idUsuario) {
        $this->data['menu']['padre'] = '';
        $this->data['menu']['hijo'] = '';
        $usuario = $this->Usuario_model->get_one_usuario($idUsuario);
        
        
        //$this->acceso_cls->verificarPermisoCst('editar_usuario');
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
            
            /* $this->form_validation->set_rules('password', 'Contraseña', 'required');
              $this->form_validation->set_rules('nueva_contrasenia', 'Nueva Contraseña', 'required');
              $this->form_validation->set_rules('nueva_contrasenia2', 'Nueva Contraseña 2', 'required'); */
            
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
                $contra = $usuario['password'];
                if ($contra != null) {
                    $contra = sha1($usuario['password']);
                    $datoUsuario = $this->Usuario_model->validarMiUsuario($usuario['UsrId'], $contra);
                    if (count($datoUsuario) > 0) {
                        if ($usuario['nueva_contrasenia'] != null) {
                            $this->Usuario_model->update_mi_usuario($usuario, $idUsuario);
                            $this->session->set_flashdata('mensaje', array('exito', 'El usuario se ha actualizado satisfactoriamente.'));
                            redirect(base_url() . 'permisos/mi_usuario/editar/' . $idUsuario);
                        } else {
                            $this->session->set_flashdata('mensaje', array('error', 'Falto ingresar Contraseña Nueva'));
                        }
                    } else {
                        $this->session->set_flashdata('mensaje', array('error', 'Contraseña Anterior Incorrecta.'));
                    }
                } else {
                    $this->Usuario_model->update_mi_usuario_no($usuario, $idUsuario);
                    $this->session->set_flashdata('mensaje', array('exito', 'El usuario se ha actualizado satisfactoriamente.'));
                    redirect(base_url() . 'permisos/mi_usuario/editar/' . $idUsuario);
                }
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['usuarioEdit'] = $usuario;
        $this->data['accion'] = 'editar';
        $this->data['proceso'] = 'Configurar Usuario';
        $this->data['view'] = 'acceso/Mi_usuario_view';
        $this->data['breadcrumbs'] = array(array('Editar Usuario', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function usuario_eliminar($idUsuario) {
        $this->acceso_cls->verificarPermiso('eliminar_usuario');
        $usuario = $this->Usuario_model->get_one_usuario($idUsuario);
        if (!isset($usuario)) {
            show_404();
        }
        if ($usuario['UsrCntId'] != $this->data['userdata']['contratante']['CntId']) {
            show_404();
        }
        $this->Usuario_model->delete_usuario($idUsuario);
        $this->session->set_flashdata('mensaje', array('success', 'El usuario se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'permisos/usuarios');
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
            'allowed_types' => 'png|jpg|jpeg|bmp|JPEG||PNG||JPG||BMP',
            'max_size' => 2048,
            'remove_space' => TRUE,
            'encrypt_name' => TRUE,
        );
        $this->upload->initialize($this->upload_config);
        if (!$this->upload->do_upload($field_name)) {
            $upload_error = $this->upload->display_errors();
            return false;
        } else {
            $file_info = array($this->upload->data());
            return $file_info[0]['file_name'];
        }
    }

    public function directorio_contratante() {
        $this->acceso_cls->verificarPermiso('ver_directorio_contratante');
        $this->data['niveles'] = $this->Usuario_model->get_usuarios_contratante_x_nivel($this->data['userdata']['contratante']['CntId']);
        $this->data['view'] = 'acceso/Dir_contratante_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Directorio EPS', ''));
        $this->data['menu']['padre'] = 'directorio';
        $this->data['menu']['hijo'] = 'directorio_contratante';
        $this->load->view('template/Master', $this->data);
    }
    
    public function directorio_contratista() {
        $this->acceso_cls->verificarPermiso('ver_directorio_contratante');
        $this->data['niveles'] = $this->Usuario_model->get_usuarios_contratista_x_nivel($this->data['userdata']['contratante']['CntId']);
        $this->data['contsta'] = $this->Usuario_model->get_contratista_x_usuario_nivel($this->data['userdata']['contratante']['CntId']);
        $this->data['view'] = 'acceso/Dir_contratista_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Directorio Contratista', ''));
        $this->data['menu']['padre'] = 'directorio';
        $this->data['menu']['hijo'] = 'directorio_contratista';
        $this->load->view('template/Master', $this->data);
    }

}
