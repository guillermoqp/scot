<?php

class NivelGrupo_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('general/NivelGrupo_model');
        $this->load->model('catastro/Catastro_model');
        $this->load->library('session');
        $this->load->library('acceso_cls');
        $this->load->library('utilitario_cls');
        $this->acceso_cls->isLogin();
        
        $this->data['userdata'] = $this->acceso_cls->get_userdata();
        $this->idContratante = $this->data['userdata']['contratante']['CntId'];
        
        $this->data['menu']['padre'] = 'configuracion';
        $this->data['menu']['hijo'] = 'niveles_comerciales';
    }

    public function nivelesGrupo_listar() {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_nivel_comercial');
        
        if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['btn_nuevo_nivel'])) 
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('NgrDes', 'Nombre Nivel de Grupo', 'required');
            if ($this->form_validation->run() == TRUE) 
            {
                $NgrDes = $this->input->post('NgrDes');
                $ultimoNivel = $this->NivelGrupo_model->get_last_nivelGrupo($this->idContratante);
                $resultado = $this->NivelGrupo_model->insert_nivelGrupo($NgrDes,$ultimoNivel['NgrOrd'],$this->idContratante);
                if($resultado)
                {
                    $this->session->set_flashdata('mensaje', array('success', 'El nivel se ha registrado satisfactoriamente.'));
                    redirect(base_url() . 'configuracion/niveles_comerciales');
                }
                else
                {
                    $this->session->set_flashdata('mensaje', array('success', 'El nivel no se pudo registrar.'));
                    redirect(base_url() . 'configuracion/niveles_comerciales');
                }
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        $this->data['nivelesGrupo'] = $this->NivelGrupo_model->get_all_nivelGrupo($this->idContratante);
        $this->data['view'] = 'general/NivelesGrupo_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Niveles Comerciales', ''));
        $this->load->view('template/Master', $this->data);
    }
    
    public function nivelGrupo_detalle($idNivelGrupo) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_nivel_comercial');
        $nivelGrupo = $this->NivelGrupo_model->get_one_nivelGrupo($idNivelGrupo);
        if (!isset($nivelGrupo)) {
            show_404();
        }
        
        if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['btn_actualizar_nombre_nivel'])) 
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('NgrDes', 'Nombre de Nivel', 'required');
            if ($this->form_validation->run() == TRUE) 
            {
                $NgrDes = $this->input->post('NgrDes');
                $resultado = $this->NivelGrupo_model->update_nivelGrupo($NgrDes , $idNivelGrupo);
                if($resultado)
                {
                    $this->session->set_flashdata('mensaje', array('success', 'El nivel se ha actualizado satisfactoriamente.'));
                    redirect(base_url() . 'configuracion/nivel_comercial/'.$idNivelGrupo.'/detalle');
                }
                else
                {
                    $this->session->set_flashdata('mensaje', array('success', 'El nivel no se ha podido actualizar.'));
                    redirect(base_url() . 'configuracion/nivel_comercial/'.$idNivelGrupo.'/detalle');
                }
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        
        if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['btn_nuevo_nivel_detalle'])) 
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('GprCod', 'Código', 'required');
            $this->form_validation->set_rules('GprDes', 'Nombre', 'required');
            if ($this->form_validation->run() == TRUE) 
            {
                $GprCod = $this->input->post('GprCod');
                $GprDes = $this->input->post('GprDes');
                $resultado = $this->NivelGrupo_model->insert_grupoPredio($GprCod , $GprDes , $idNivelGrupo);
                if($resultado)
                {
                    $this->session->set_flashdata('mensaje', array('success', 'El nuevo '.$nivelGrupo['NgrDes'].' se ha Registrado.'));
                    redirect(base_url() . 'configuracion/nivel_comercial/'.$idNivelGrupo.'/detalle');
                }
                else
                {
                    $this->session->set_flashdata('mensaje', array('success', 'El nuevo '.$nivelGrupo['NgrDes'].' no se ha podido Registrar.'));
                    redirect(base_url() . 'configuracion/nivel_comercial/'.$idNivelGrupo.'/detalle');
                }
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        
        $this->data['nivelGrupo'] = $nivelGrupo;
        $this->data['gruposPredio'] =  $this->NivelGrupo_model->get_all_grupoPredio_x_nivelGrupo($idNivelGrupo);
        $this->data['view'] = 'general/NivelGrupo_detalle_view';
        $this->data['proceso'] = 'Administración General';
        $this->data['breadcrumbs'] = array(array('Niveles Comerciales', 'configuracion/niveles_comerciales'), array('Detalle del Nivel Comercial : '.$nivelGrupo['NgrDes'], ''));
        $this->load->view('template/Master', $this->data);
    }
    
    public function nivelGrupo_subnivel($idSubNivel) {
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->acceso_cls->verificarPermiso('ver_nivel_comercial');
        $subNivelesAsignados = $this->NivelGrupo_model->get_all_subnivel_asignado($idSubNivel);
        if (!isset($subNivelesAsignados)) {
            show_404();
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['btn_actualizar_subnivel'])) 
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('GprDes', 'Nombre', 'required');
            $this->form_validation->set_rules('GprCod', 'Codigo', 'required');
            if ($this->form_validation->run() == TRUE) 
            {
                $GprCod= $this->input->post('GprCod');
                $GprDes = $this->input->post('GprDes');
                
                $resultado = $this->NivelGrupo_model->update_grupoPredio($GprCod , $GprDes , $idSubNivel);
                if($resultado)
                {
                    $this->session->set_flashdata('mensaje', array('success', 'El nivel se ha actualizado satisfactoriamente.'));
                    redirect(base_url() . 'configuracion/nivel_comercial/subnivel/'.$idSubNivel);
                }
                else
                {
                    $this->session->set_flashdata('mensaje', array('success', 'El nivel no se ha podido actualizar.'));
                    redirect(base_url() . 'configuracion/nivel_comercial/subnivel/'.$idSubNivel);
                }
            } else {
                $this->session->set_flashdata('mensaje', array('error', 'Faltan datos.'));
            }
        }
        
        $this->data['subNivelesAsignados'] = $subNivelesAsignados;
        $this->data['subNivelesNoAsignados'] = $this->NivelGrupo_model->get_all_subnivel_noasignado($idSubNivel);
        $this->data['grupoPredio'] = $this->NivelGrupo_model->get_one_grupoPredio($idSubNivel);
        $this->data['nivelGrupo'] = $this->NivelGrupo_model->get_one_nivelGrupo($this->data['grupoPredio']['GprNgrId']);
        
        
        $this->data['SubNivel'] = $this->NivelGrupo_model->get_subnivel_inferior($this->data['nivelGrupo']['NgrId']);
        if(empty($this->data['SubNivel']))
        {
            $this->data['predios'] = $this->Catastro_model->get_all_predios_x_idGrupoPredio($this->data['grupoPredio']['GprId']);       
        }
        
        $this->data['view'] = 'general/NivelGrupo_subnivel_view';
        $this->data['proceso'] = 'Administración General';
        if(empty($this->data['SubNivel'])){
        $this->data['breadcrumbs'] = array(array('Niveles Comerciales', 'configuracion/niveles_comerciales'), array('Detalle del Nivel Comercial: '.$this->data['nivelGrupo']['NgrDes'], 'configuracion/nivel_comercial/'.$this->data['nivelGrupo']['NgrId'].'/detalle'), array($this->data['nivelGrupo']['NgrDes'].' '.$this->data['grupoPredio']['GprDes'],''));
        }
        else{$letra = substr($this->data['SubNivel']['NgrDes'], -1);
             if (preg_match('/[aeiouáéíóúü]/i',$letra)){$letras = "s";}
             else if (preg_match('/[a-z]/i',$letra)){$letras = "es";}
             $Subnivel = $this->data['SubNivel']['NgrDes'].$letras;
             $this->data['breadcrumbs'] = array(array('Niveles Comerciales', 'configuracion/niveles_comerciales'), array('Detalle del Nivel Comercial: '.$this->data['nivelGrupo']['NgrDes'], 'configuracion/nivel_comercial/'.$this->data['nivelGrupo']['NgrId'].'/detalle'), array($Subnivel.' del '.$this->data['nivelGrupo']['NgrDes'].' '.$this->data['grupoPredio']['GprDes'],''));
            }
        $this->load->view('template/Master', $this->data);
    }
     
    public function nivelGrupo_subnivel_agrupar($idNivel,$idSubNivel)
    {
        $Nivel = $this->NivelGrupo_model->get_one_grupoPredio($idNivel);
        if (!isset($Nivel)) {
            show_404();
        }
        $SubNivel = $this->NivelGrupo_model->get_one_grupoPredio($idSubNivel);
        if (!isset($SubNivel)) {
            show_404();
        }
        $resultado = $this->NivelGrupo_model->agrupar_grupoPredio($idNivel,$idSubNivel);
        if($resultado)
        {
        $this->session->set_flashdata('mensaje', array('success', 'Se ha agrupado al '.$Nivel['NgrDes'].' '.$Nivel['GprCod']));
        redirect(base_url() . 'configuracion/nivel_comercial/subnivel/' . $idNivel);
        } else {
        $this->session->set_flashdata('mensaje', array('success', 'No se pudo agrupar al '.$Nivel['NgrDes'].' '.$Nivel['GprCod']));
        redirect(base_url() . 'configuracion/nivel_comercial/subnivel/' . $idNivel);
        } 
    }
    
    public function nivelGrupo_subnivel_desagrupar($idNivel,$idSubNivel)
    {
        $Nivel = $this->NivelGrupo_model->get_one_grupoPredio($idNivel);
        if (!isset($Nivel)) {
            show_404();
        }
        $SubNivel = $this->NivelGrupo_model->get_one_grupoPredio($idSubNivel);
        if (!isset($SubNivel)) {
            show_404();
        }
        $resultado = $this->NivelGrupo_model->desagrupar_grupoPredio($idSubNivel);
        if($resultado)
        {
        $this->session->set_flashdata('mensaje', array('success', 'Se ha desagrupado del '.$Nivel['NgrDes'].' '.$Nivel['GprCod']));
        redirect(base_url() . 'configuracion/nivel_comercial/subnivel/' . $idNivel);
        } else {
        $this->session->set_flashdata('mensaje', array('success', 'No se pudo desagrupar del '.$Nivel['NgrDes'].' '.$Nivel['GprCod']));
        redirect(base_url() . 'configuracion/nivel_comercial/subnivel/' . $idNivel);
        }
    }
    
    
    public function subciclos_sin_agrupar_notificaciones_json()
    {
        $SubCiclosSinAgrupar = $this->NivelGrupo_model->get_subciclos_sin_agrupar_notificaciones($this->idContratante);
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($SubCiclosSinAgrupar));
    }
    

}
