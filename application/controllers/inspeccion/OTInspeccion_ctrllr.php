<?php

class OTInspeccion_ctrllr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('general/Periodo_model');
        $this->load->model('general/Actividad_model');  
        
        $this->load->model('sistema/SegundaConexion_model'); 

        $this->load->library('acceso_cls');
        $this->acceso_cls->isLogin();

        $this->data['menu']['padre'] = 'inspeccion';
        $this->data['menu']['hijo'] = 'ordenes_inspeccion';
        
        $this->data['userdata'] = $this->acceso_cls->get_userdata($_SESSION['usuario_id']);
        $this->idContratante = $this->data['userdata']['contratante']['CntId'];
        $this->idActividad = $this->Actividad_model->get_id_actividad_x_codigo('inspeccion');
        
    }
    
    function generar_cod_barras_code39($codigo)
    {
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        Zend_Barcode::render('code39', 'image', array('text'=>$codigo), array());
    }

    public function ficha_actualizacionCatastral() {

        
       if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['guardar_ficha_act_cat'])) {
           
            $nombre  = $this->input->post('nombre') == '' ? null : $this->input->post('nombre');
            $codCensal  = $this->input->post('codCensal') == '' ? null : $this->input->post('codCensal');
            $FechaEmpadronamiento  = $this->input->post('FechaEmpadronamiento') == '' ? null : $this->input->post('FechaEmpadronamiento');
            $dni  = $this->input->post('dni') == '' ? null : $this->input->post('dni');
            $ref_ubi  = $this->input->post('ref-ubi') == '' ? null : $this->input->post('ref-ubi');
            $direccion  = $this->input->post('direccion') == '' ? null : $this->input->post('direccion');
            $num_munic  = $this->input->post('num_munic') == '' ? null : $this->input->post('num_munic');
            $urb  = $this->input->post('urb') == '' ? null : $this->input->post('urb');
            $sect_censal  = $this->input->post('sect_censal') == '' ? null : $this->input->post('sect_censal');
            $mnz_censal  = $this->input->post('mnz_censal') == '' ? null : $this->input->post('mnz_censal');
            $lt_censal  = $this->input->post('lt_censal') == '' ? null : $this->input->post('lt_censal');
            $estado_predio  = $this->input->post('estado_predio') == '' ? null : $this->input->post('estado_predio');
            $nro_pisos = $this->input->post('nro_pisos') == '' ? null : $this->input->post('nro_pisos');
            $nro_conex_agua  = $this->input->post('nro_conex_agua') == '' ? null : $this->input->post('nro_conex_agua');
            $nro_conex_desague  = $this->input->post('nro_conex_desague') == '' ? null : $this->input->post('nro_conex_desague');
            $tipo_almacenamiento  = $this->input->post('tipo_almacenamiento') == '' ? null : $this->input->post('tipo_almacenamiento');
            $con_pavimento  = $this->input->post('con_pavimento') == '' ? null : $this->input->post('con_pavimento');
            $pasa_red_agua  = $this->input->post('pasa_red_agua') == '' ? null : $this->input->post('pasa_red_agua');
            $pasa_red_desague  = $this->input->post('pasa_red_desague') == '' ? null : $this->input->post('pasa_red_desague');
            $material_vereda  = $this->input->post('material_vereda') == '' ? null : $this->input->post('material_vereda');
            $nro_unidades_uso  = $this->input->post('nro_unidades_uso') == '' ? null : $this->input->post('nro_unidades_uso');
            $uso_inmueble  = $this->input->post('uso_inmueble') == '' ? null : $this->input->post('uso_inmueble');
            $estado_actual_medidor  = $this->input->post('estado_actual_medidor') == '' ? null : $this->input->post('estado_actual_medidor');
            $lectura_medidor  = $this->input->post('lectura_medidor') == '' ? null : $this->input->post('lectura_medidor');
            $nro_medidor  = $this->input->post('nro_medidor') == '' ? null : $this->input->post('nro_medidor');
            $diametro_medidor  = $this->input->post('diametro_medidor') == '' ? null : $this->input->post('diametro_medidor');
            $tiene_caja  = $this->input->post('tiene_caja') == '' ? null : $this->input->post('tiene_caja');
            $estado_caja_agua  = $this->input->post('estado_caja_agua') == '' ? null : $this->input->post('estado_caja_agua');
            $material_caja_agua  = $this->input->post('material_caja_agua') == '' ? null : $this->input->post('material_caja_agua');
            $ubicacion_caja_agua = $this->input->post('ubicacion_caja_agua') == '' ? null : $this->input->post('ubicacion_caja_agua');
            $estado_conexion_agua  = $this->input->post('estado_conexion_agua') == '' ? null : $this->input->post('estado_conexion_agua');
            $material_tapa_agua  = $this->input->post('material_tapa_agua') == '' ? null : $this->input->post('material_tapa_agua');
            $estado_tapa_agua  = $this->input->post('estado_tapa_agua') == '' ? null : $this->input->post('estado_tapa_agua');
            $diametro_conexion_agua  = $this->input->post('diametro_conexion_agua') == '' ? null : $this->input->post('diametro_conexion_agua');
            $tiene_caja_desague  = $this->input->post('tiene_caja_desague') == '' ? null : $this->input->post('tiene_caja_desague');
            $ubicacion_caja_desague  = $this->input->post('ubicacion_caja_desague') == '' ? null : $this->input->post('diametro_conexion_agua');
            $estado_caja_desague  = $this->input->post('estado_caja_desague') == '' ? null : $this->input->post('estado_caja_desague');
            $estado_conexion_desague  = $this->input->post('estado_conexion_desague') == '' ? null : $this->input->post('estado_conexion_desague');
            $material_tapa_desague  = $this->input->post('material_tapa_desague') == '' ? null : $this->input->post('material_tapa_desague');
            $observaciones  = $this->input->post('observaciones') == '' ? null : $this->input->post('observaciones');
            $num_munic_derech  = $this->input->post('num_munic_derech') == '' ? null : $this->input->post('num_munic_derech');
            $num_munic_censado  = $this->input->post('num_munic_censado') == '' ? null : $this->input->post('num_munic_censado');
            $num_munic_izquierdo  = $this->input->post('num_munic_izquierdo') == '' ? null : $this->input->post('num_munic_izquierdo');
            $encuestado  = $this->input->post('encuestado') == '' ? null : $this->input->post('encuestado');
            $dni_encuestado  = $this->input->post('dni_encuestado') == '' ? null : $this->input->post('dni_encuestado');
            $parentesco_encuestado  = $this->input->post('parentesco_encuestado') == '' ? null : $this->input->post('parentesco_encuestado');
            $cod_empadronador  = $this->input->post('cod_empadronador') == '' ? null : $this->input->post('cod_empadronador');
            $supervisor_comercial  = $this->input->post('supervisor_comercial') == '' ? null : $this->input->post('supervisor_comercial');
        } 
        
       $codFacturacion = '12081700344';
       $this->data['usuario'] = $this->SegundaConexion_model->get_one_usuario_cod_fact($codFacturacion);
       $this->data['view'] = 'inspeccion/ficha_actualizacion_catastral_view';
       $this->load->view('template/Reporte', $this->data);
        
    }
    
    public function ficha_InspeccionInterna() {

       if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['guardar_ficha_insp_int'])) {
            $nombre  = $this->input->post('nombre') == '' ? null : $this->input->post('nombre');
        } 
        $codFacturacion = '12081700344';
        
       $this->data['usuario'] = $this->SegundaConexion_model->get_one_usuario_cod_fact($codFacturacion);
       
       $this->data['view'] = 'inspeccion/ficha_inspeccion_interna_view';
       
       $this->load->view('template/Reporte', $this->data);
    }

}
