<?php

class Contratante_ctrllr extends CI_Controller {

    var $esnuevo = true;
    public function __construct() {
        parent::__construct();
        $this->load->model('general/Empresa_model');
        $this->load->model('general/Contratante_model');
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();
        $this->data['menu']['padre'] = 'configuracion_global';
        $this->data['menu']['hijo'] = 'contratante';
    }

    public function contratante_listar() 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_contratante');
        $this->data['contratantes'] = $this->Empresa_model->get_empresa_contratante();
        $this->data['view'] = 'general/Contratantes_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Contratantes', ''));
        $this->load->view('template/Master', $this->data);
    }
    
    public function get_empresa_x_ruc_json()
    {   
        /* Obtener valosres por json    */
        $ruc = $this->input->post('ruc');
        $empresa = $this->Empresa_model->get_one_empresa_x_ruc($ruc);
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($empresa));
        /*  print json_encode($data); */
    }
        
    public function get_contratante_x_ruc_json()
    {   
        /* Obtener valosres por json    */
        $ruc = $this->input->post('ruc');
        $empresa = $this->Empresa_model->get_one_empresa_contratante_x_ruc($ruc);
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($empresa));
    }
    
    public function validar_empresa_libre() 
    {   
        /* Obtener valosres por json    */
        $ruc = $this->input->post('ruc');
        
        $empresa = $this->Empresa_model->get_one_empresa_x_ruc($ruc);
        $idEmpresa =  $empresa['EmpId'];
        
        $empresas = $this->Empresa_model->get_all_empresa();
        $rucEmpresas = array_column($empresas, 'EmpRuc');
        
        $empresasLibres = $this->Empresa_model->get_empresas_libres();
        $idEmpresasLibres = array_column($empresasLibres, 'EmpId');
        
        if(in_array($idEmpresa , $idEmpresasLibres ))
        {
            $json = array('result' => true , 'empresa'=> $empresa);
            header('Content-Type: application/x-json; charset=utf-8');
            echo json_encode($json);
        }
        else{
            if(!in_array($ruc , $rucEmpresas ))
            {
                $json = array('result' => true);
                header('Content-Type: application/x-json; charset=utf-8');
                echo json_encode($json);
            }
            else
            {
                $json = array('result' => false);
                header('Content-Type: application/x-json; charset=utf-8');
                echo json_encode($json);
            }
        }
    }
    
    public function do_upload($field_name)
    {
        $this->load->library('upload');
        $this->load->helper('file');

        $image_upload_folder = FCPATH . 'assets/uploads/contratantes/';

        if (!file_exists($image_upload_folder)) 
        {
            mkdir($image_upload_folder, DIR_WRITE_MODE, true);
        }

        $this->upload_config = array(
            'upload_path'   => $image_upload_folder,
            'allowed_types' => 'png|jpg|jpeg|bmp|JPEG||PNG||JPG||BMP',
            'max_size'      => 2048,
            'remove_space'  => TRUE,
            'encrypt_name'  => TRUE,
        );
        $this->upload->initialize($this->upload_config);
        if (!$this->upload->do_upload($field_name)) 
        {
            $upload_error = $this->upload->display_errors();
            /*print_r($upload_error);
            exit();*/
            return false;
            
        } else 
        {
            $file_info = array($this->upload->data());
            return $file_info[0]['file_name'];
        }
    }
    
    public function contratante_editar($contratanteId) 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_contratante');
        $empresaEdit = $this->Empresa_model->get_one_empresa_x_contratante($contratanteId);
        $contratanteEdit = $this->Contratante_model->get_one_contratante_empresa($contratanteId);

        if (!isset($contratanteEdit)) 
        {
            show_404();
        }
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['contratanteEdit'] = $contratanteEdit;
        $this->data['empresaEdit'] = $empresaEdit;
        $this->data['empresasId'] = $this->Empresa_model->get_empresa_x_contratante($contratanteEdit['CntId']);

        $this->data['accion'] = 'editar';
        $this->data['view'] = 'general/Contratante_view';
        $this->data['proceso'] = 'Contratantes';
        $this->data['breadcrumbs'] = array(array('Contratantes','configuracion/contratantes'),array('Editar Contratante',''));
        $this->load->view('template/Master', $this->data);
    }
    
    public function contratante_editar_guardar($idContratante) 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_contratante');
        $idEmpresa = $this->Empresa_model->get_one_empresa_x_contratante($idContratante);
        if (!isset($idContratante)) 
        {
            show_404();
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('ruc', 'RUC', 'required');
        $this->form_validation->set_rules('razSocial', 'Razon Social', 'required');
        $this->form_validation->set_rules('direccion', 'Direcion', 'required');
        $this->form_validation->set_rules('tel11', 'Telefono', 'required');
        $this->form_validation->set_rules('email11', 'Correo', 'required');
        $this->form_validation->set_rules('dni1', 'DNI Representante Legal', 'required');
        $this->form_validation->set_rules('nombres1', 'Nombre Representante Legal', 'required');
        /*$this->form_validation->set_rules('log1', 'Logo de Contratante', 'required');
        $this->form_validation->set_rules('log2', 'Logo de Contratante', 'required');*/

        if ($this->form_validation->run() == TRUE)
        {
            $EmpRuc = $this->input->post('ruc');
            $EmpRaz = $this->input->post('razSocial');
            $direccion = $this->input->post('direccion');
            $tel11 = $this->input->post('tel11');
            $email11 = $this->input->post('email11');
            $EmpDniRp  = $this->input->post('dni1');
            $EmpNomRp = $this->input->post('nombres1');
            
            $campo1 = "log1";
            if($this->do_upload($campo1)) 
            {
                $imagen1 = $this->do_upload($campo1);
                $logo1 = 'assets/uploads/contratantes/'.$imagen1;
            }
            else
            {
                $logo1 = $this->input->post('log1Oculto');
            }
            $campo2 = "log2";  
            if ($this->do_upload($campo2)) 
            {
                $imagen2 = $this->do_upload($campo2);
                $logo2 = 'assets/uploads/contratantes/'.$imagen2;
            }
            else 
            {
                $logo2 = $this->input->post('log2Oculto');
            }
            
            $this->Contratante_model->update_contratante($EmpRuc,$EmpRaz, $direccion, $tel11 , $email11 , $EmpDniRp , $EmpNomRp , $logo1,$logo2, $idEmpresa['EmpId'] , $idContratante);
            
            $this->session->set_flashdata('mensaje', array('success','Los datos de la Contratante se han actualizado satisfactoriamente.'));
            redirect(base_url() . 'configuracion/contratante/editar/'.$idContratante);
        } else 
        {
            $this->session->set_flashdata('mensaje', array('error', 'Porfavor, Complete los campos obligatorios.'));
            $this->contratante_editar($idContratante);
        }
    }
    
    //Nueva contratante
    public function contratante_nuevo() 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_contratante');
        if ($this->esnuevo) 
        {
            $_SESSION["empresas"] = array();
        }
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'general/Contratante_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Contratantes', 'configuracion/contratantes'), array('Registrar Contratante', ''));
        $this->load->view('template/Master', $this->data);
    }
    
    public function contratante_nuevo_guardar() 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_contratante');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('ruc', 'RUC', 'required');
        $this->form_validation->set_rules('razSocial', 'Razon Social', 'required');
        $this->form_validation->set_rules('direccion', 'Direcion', 'required');
        $this->form_validation->set_rules('tel11', 'Telefono', 'required');
        $this->form_validation->set_rules('email11', 'Correo', 'required');
        $this->form_validation->set_rules('dni1', 'DNI Representante Legal', 'required');
        $this->form_validation->set_rules('nombres1', 'Nombre Representante Legal', 'required');
        //$this->form_validation->set_rules('log1', 'Logo 1 de Contratista', 'required');
        //$this->form_validation->set_rules('log2', 'Logo 2 de Contratista', 'required');
        
       
        if ($this->form_validation->run() == TRUE) 
        {
            $ruc = $this->input->post('ruc');
            $razSocial = $this->input->post('razSocial');
            $direccion = $this->input->post('direccion');
            $tel11 = $this->input->post('tel11');
            $email11 = $this->input->post('email11');
            $repLegalDni  = $this->input->post('dni1');
            $repLegalNomb = $this->input->post('nombres1');
            
            $campo1 = "log1";
            if($this->do_upload($campo1)) 
            {
                $imagen1 = $this->do_upload($campo1);
                $logo1 = 'assets/uploads/contratantes/'.$imagen1;
            }
            else
            {
                $logo1 = "";
            }
            $campo2 = "log2";  
            if ($this->do_upload($campo2)) 
            {
                $imagen2 = $this->do_upload($campo2);
                $logo2 = 'assets/uploads/contratantes/'.$imagen2;
            }
            else 
            {
                $logo2 = "";
            }

            $retorno = $this->Contratante_model->insert_contratante($ruc,$razSocial, $direccion,$tel11 ,$email11 , $repLegalDni,$repLegalNomb,$logo1,$logo2,$_SESSION['empresas']);
            echo $retorno;
            if($retorno)
            {
                $this->session->set_flashdata('mensaje', array('success', 'La Contratante se ha Guardado Satisfactoriamente.'));
                $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                $this->data['accion'] = 'nuevo';
                $this->data['view'] = 'general/Contratante_view';
                $this->data['breadcrumbs'] = array(array('Contratantes', ''));
                $this->load->view('template/Master', $this->data);
                redirect(base_url().'configuracion/contratantes');
            }
            else
            {
                $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al momento de guardar.'));
                redirect(base_url().'configuracion/contratante/nuevo');
                $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                $this->data['accion'] = 'nuevo';
                $this->data['view'] = 'general/Contratante_view';
                $this->data['breadcrumbs'] = array(array('Contratantes', 'configuracion/contratantes'), array('Registrar Contratante', ''));
                $this->load->view('template/Master', $this->data);
                redirect(base_url().'configuracion/contratante/nuevo');
            }
        } 
        else 
        {
            $this->session->set_flashdata('mensaje', array('error', 'Porfavor, Complete los campos obligatorios.'));
            $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
       
            $this->data['empresas'] = $_SESSION['empresas'];
            $this->data['accion'] = 'nuevo';
            $this->data['view'] = 'general/Contratante_view';
            $this->data['breadcrumbs'] = array(array('Contratantes', 'configuracion/contratantes'), array('Registrar Contratante', ''));
            $this->load->view('template/Master', $this->data);
            redirect(base_url().'configuracion/contratante/nuevo');
        }
        
    }
    
    public function contratante_eliminar($idContratante) 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('eliminar_contratante');
        $this->Contratante_model->delete_contratante($idContratante);
        $this->session->set_flashdata('mensaje', array('success', 'El Contratante se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'configuracion/contratantes');
    }
    
}
