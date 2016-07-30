<?php

class Contratista_ctrllr extends CI_Controller 
{
    var $esnuevo = true;
    var $porcentajeMaximo = 100;

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('general/Contratista_model');
        $this->load->model('general/Empresa_model');
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();
        $this->data['userdata']=$this->acceso_cls->get_userdata();
        $this->data['menu']['padre'] = 'contratacion';
        $this->data['menu']['hijo'] = 'contratista';
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

    public function get_one_empresa_detallecontratista_json() 
    {   
        /* Obtener valosres por json    */
        $empresaId = $this->input->post('empresaId');
        $empresa = $this->Empresa_model->get_one_empresa_detallecontratista($empresaId);
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($empresa));
        /*  print json_encode($data); */
    } 


    public function get_contratista_x_ruc_json()
    {   
        /* Obtener valosres por json    */
        $ruc = $this->input->post('ruc');
        $contratista = $this->Contratista_model->get_one_contratista_x_ruc($ruc);
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($contratista));
        /*  print json_encode($data); */
    } 

    public function do_upload($field_name)
    {
        $this->load->library('upload');
        $this->load->helper('file');

        $image_upload_folder = FCPATH . 'assets/uploads/contratistas/';

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

    public function contratista_listar() 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_contratista');
        
        $this->data['contratistas'] = $this->Contratista_model->get_all_contratista();
        $this->data['view'] = 'general/Contratistas_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Contratistas',''));
        $this->load->view('template/Master', $this->data);
    }
    
    public function contratista_nuevo() 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_contratista');
        if ($this->esnuevo) 
        {
            $_SESSION["empresas"] = array();
        }
        $this->data['accion'] = 'nuevo';
        $this->data['view'] = 'general/Contratista_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Contratistas', 'contratistas'), array('Registrar Contratista', ''));
        $this->load->view('template/Master', $this->data);
    }

    public function contratista_nuevo_empresas()
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_contratista');
       
        $ruc = $this->input->post('ruc2');
        if(isset($ruc))
        {
            $ruc = $this->input->post('ruc2');
            $razSocial = $this->input->post('razSocial2');
            $direccion = $this->input->post('direccion2');
            $tel = $this->input->post('tel2');
            $email = $this->input->post('email2');
            $repLegalDni  = $this->input->post('rldni');
            $repLegalNomb = $this->input->post('rlnombres');
            $porcentaje = $this->input->post('porcentaje');

            $empresa = array("ruc" => $ruc,"razSocial" => $razSocial,"direccion" => $direccion,"tel" => $tel,
                "email" => $email,"repLegalDni" => $repLegalDni,"repLegalNomb" =>$repLegalNomb ,
                "porcentaje" => $porcentaje);

            $porcentajes  = array_map('intval', array_column($_SESSION['empresas'], 'porcentaje'));
            $sumaporcentaje = array_sum($porcentajes);
            $this->session->set_flashdata('mensaje', array('info','Total de Porcentaje de Participacion acumulado: '.($sumaporcentaje + $porcentaje).' %'));
            if( $sumaporcentaje + $porcentaje > 100 )
            {
                $this->session->set_flashdata('mensaje', array('error','No se Puede Agregar un Consorciado mas al consorcio por superar el limite permitido del porcentaje de participación.'));
            }
            else
            {
                array_push($_SESSION['empresas'] , $empresa);
            }
            $this->esnuevo = false;
            $this->session->set_flashdata('success','Consorciado agregado con éxito.');
            $this->contratista_nuevo();
        }
    }

    public function contratista_nuevo_guardar() 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_contratista');
        
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
            $tel12 = $this->input->post('tel12');
            $email11 = $this->input->post('email11');
            $email12 = $this->input->post('email12');
            $repLegalDni  = $this->input->post('dni1');
            $repLegalNomb = $this->input->post('nombres1');
            $CstCio = $this->input->post('CstCio');
            
            $campo1 = "log1";
            if($this->do_upload($campo1)) 
            {
                $imagen1 = $this->do_upload($campo1);
                $logo1 = 'assets/uploads/contratistas/'.$imagen1;
            }
            else
            {
                $logo1 = "";
            }
            $campo2 = "log2";  
            if ($this->do_upload($campo2)) 
            {
                $imagen2 = $this->do_upload($campo2);
                $logo2 = 'assets/uploads/contratistas/'.$imagen2;
            }
            else 
            {
                $logo2 = "";
            }

            $retorno = $this->Contratista_model->insert_contratista($ruc,$razSocial, $direccion,$tel11 ,$tel12 ,$email11 , $email12,$repLegalDni,$repLegalNomb,$logo1,$logo2,$CstCio,$_SESSION['empresas']);
            if($retorno)
            {
                $this->session->set_flashdata('mensaje', array('success', 'La Contratista se ha Guardado Satisfactoriamente.'));
                $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                $this->data['accion'] = 'nuevo';
                $this->data['view'] = 'general/Contratista_view';
                $this->data['breadcrumbs'] = array(array('Contratistas', ''));
                $this->load->view('template/Master', $this->data);
                redirect(base_url().'contratistas');
            }
            else
            {
                $this->session->set_flashdata('mensaje', array('error', 'Ocurrio algun error al momento de guardar.'));
                $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
                $this->data['accion'] = 'nuevo';
                $this->data['view'] = 'general/Contratista_view';
                $this->data['breadcrumbs'] = array(array('Contratistas', 'contratistas'), array('Registrar Contratista', ''));
                $this->load->view('template/Master', $this->data);
                redirect(base_url().'contratista/nuevo');
            }
        } 
        else 
        {
            $this->session->set_flashdata('mensaje', array('error', 'Complete los campos'));
            $this->data['empresas'] = $_SESSION['empresas'];
            $this->data['accion'] = 'nuevo';
            $this->data['view'] = 'general/Contratista_view';
            $this->data['breadcrumbs'] = array(array('Contratistas', 'contratistas'), array('Registrar Contratista', ''));
            $this->load->view('template/Master', $this->data);
            redirect(base_url().'contratista/nuevo');
        }
        
    }

    public function contratista_editar($contratistaId) 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_contratista');
        
        if ($this->esnuevo) 
        {
            $_SESSION["empresas"] = array();
        }
        $contratistaEdit = $this->Contratista_model->get_one_contratista($contratistaId);

        if (!isset($contratistaEdit)) 
        {
            show_404();
        }
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->data['contratistaEdit'] = $contratistaEdit;  

        $this->data['empresasId'] = $this->Empresa_model->get_empresas_x_contratista($contratistaEdit['CstId']);

        $this->data['accion'] = 'editar';
        $this->data['view'] = 'general/Contratista_view';
        $this->data['proceso'] = 'Contratistas';
        $this->data['breadcrumbs'] = array(array('Contratistas','contratistas'),array('Editar Contratista',''));
        $this->load->view('template/Master', $this->data);
    }
    
    public function contratista_editar_guardar($idContratista) 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('editar_contratista');
        
        if (!isset($idContratista)) 
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
        //$this->form_validation->set_rules('log1', 'Logo 1 de Contratista', 'required');
        //$this->form_validation->set_rules('log2', 'Logo 2 de Contratista', 'required');
        
        if ($this->form_validation->run() == TRUE)
        {
            $CstRuc = $this->input->post('ruc');
            $CstRaz = $this->input->post('razSocial');
            $direccion = $this->input->post('direccion');
            $tel11 = $this->input->post('tel11');
            $tel12 = $this->input->post('tel12');
            $email11 = $this->input->post('email11');
            $email12 = $this->input->post('email12');
            $CstDniRp  = $this->input->post('dni1');
            $CstNomRp = $this->input->post('nombres1');
            $CstCio = $this->input->post('CstCio');
            
            $campo1 = "log1";
            if($this->do_upload($campo1)) 
            {
                $imagen1 = $this->do_upload($campo1);
                $logo1 = 'assets/uploads/contratistas/'.$imagen1;
            }
            else
            {
                $logo1 = $this->input->post('log1Oculto');
            }
            $campo2 = "log2";  
            if ($this->do_upload($campo2)) 
            {
                $imagen2 = $this->do_upload($campo2);
                $logo2 = 'assets/uploads/contratistas/'.$imagen2;
            }
            else 
            {
                $logo2 = $this->input->post('log2Oculto');
            }
            
            $this->Contratista_model->update_contratista($CstRuc,$CstRaz, $direccion, $tel11 , $tel12 , $email11 , $email12, $CstDniRp , $CstNomRp , $logo1,$logo2,$CstCio, $_SESSION['empresas'] , $idContratista);
            
            $this->session->set_flashdata('mensaje', array('success','Los datos de la Contratista se han actualizado satisfactoriamente.'));
            redirect(base_url() . 'contratista/editar/'.$idContratista);
        } else 
        {
            $this->session->set_flashdata('mensaje', array('error', 'Porfavor, Complete los campos obligatorios.'));
            $this->contratista_editar($idContratista);
        }
    }

    public function contratista_eliminar($idContratista) 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('eliminar_contratista');
        if (!isset($idContratista)) 
        {
            show_404();
        }
        $this->Contratista_model->delete_contratista($idContratista);

        $this->session->set_flashdata('mensaje', array('success', 'La Contratista se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'contratistas');
    }

    public function consorciado_eliminar($idContratista , $idConsorciado) 
    {
        $this->data['userdata']=$this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('eliminar_contratista');
        
        if (!isset($idContratista)&&!isset($idConsorciado)) 
        {
            show_404();
        }
        $this->Contratista_model->delete_consorciado($idContratista , $idConsorciado);
        $this->session->set_flashdata('mensaje', array('success', 'El consorciado se ha eliminado satisfactoriamente.'));
        redirect(base_url() . 'contratista/editar/'.$idContratista);
    }
   
    
}
