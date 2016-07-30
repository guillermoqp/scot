<?php

class ConsultasMovil_cst_ctrllr extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('contratista/lectura/OTTomaEstado_cst_model');
        $this->load->model('general/Actividad_model');
        $this->load->model('sistema/Parametro_model');
        $this->load->model('general/Observacion_model');
        $this->load->model('contratista/acceso/Usuario_cst_model');
        $this->load->model('lectura/Lectura_model');
        $this->data['proceso'] = 'Toma de Estado';
    }
    
    /*  LECTURAS POR LECTURISTA */
    public function obtener_lecturas_en_ejec_x_lecturista_json() 
    {
        $OrtFchEj = $this->input->post('OrtFchEj');
        $LecLtaId = $this->input->post('LecLtaId');
        $lecturas = $this->Lectura_model->get_lecturas_x_lecturista($OrtFchEj, $LecLtaId);
        if(empty($lecturas))
        {
            $json = array('result' => false, 'mensaje' => 'Sin Lecturas para el dia : '.$OrtFchEj. ', o ya han sido ejecutadas.');
        }
        else
        {
            $idOrdenTrabajo = $lecturas[0]['SolOrtId'];
            $ciclos = $this->Lectura_model->get_desc_ciclos_x_orden_lecturista($idOrdenTrabajo,$LecLtaId);
            $OTTomaEstado = $this->OTTomaEstado_cst_model->get_one_orden_trabajo_all_datos_movil($idOrdenTrabajo);
            $SubOrden = $this->OTTomaEstado_cst_model->get_one_suborden_trabajo_all_datos_movil($OrtFchEj, $LecLtaId);
            
            $json = array('result' => true,'lecturas' => $lecturas, 'ciclo' => $ciclos , 'OTTomaEstado' =>  $OTTomaEstado , 'SubOrden' => $SubOrden );
        }
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($json));
    }
    
    /*  OBSERVACIONES PARA LECTURISTA */
    public function obtener_observaciones_lectura_json() 
    {
        $LecLtaId = $this->input->post('LecLtaId');
        $ActCod = $this->input->post('ActCod');
        $ActId = $this->Actividad_model->get_id_actividad_x_codigo($ActCod);
        $DatosLecturista = $this->Usuario_cst_model->get_one_usuario_lecturista($LecLtaId);
        $idContratante = $DatosLecturista['UsrCntId'];
        $observacionesLectura = $this->Observacion_model->get_all_observacion_movil($ActId , $idContratante);
        header('Access-Control-Allow-Origin:*'); 
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($observacionesLectura));
    }
    
    
    /*  OBSERVACIONES PARA DISTRIBUIDOR  */
    public function obtener_observaciones_distribucion_json() 
    {
        $DbrId = $this->input->post('DbrId');
        
        $ActId = $this->Actividad_model->get_id_actividad_x_codigo($this->input->post('ActCod'));
        $idContratante = $this->Usuario_cst_model->get_one_usuario_lecturista($DbrId)['UsrCntId'];
        
        $observacionesDistribucion = $this->Observacion_model->get_all_observacion_movil($ActId , $idContratante);
        
        header('Access-Control-Allow-Origin:*'); 
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($observacionesDistribucion));
    }

    /*  SINCRONIZAR DATOS DE LECTURA DESDE APLICACION MOVIL : VERSION USANDO AJAX Y ENVIO DE CADENAS STRINGS */
    public function sincronizar_lecturas_json() 
    {
        $LecId = $this->input->post('LecId');
        
        $LecVal  = $this->input->post('LecVal') == '' ? null : $this->input->post('LecVal');
        $LecValInt1 = $this->input->post('LecValInt1') == '' ? null : $this->input->post('LecValInt1');
        $LecValInt2 = $this->input->post('LecValInt2') == '' ? null : $this->input->post('LecValInt2');
        $LecValInt3 = $this->input->post('LecValInt3') == '' ? null : $this->input->post('LecValInt3');
        $LecInt  = $this->input->post('LecInt') == '' ? null : $this->input->post('LecInt');
        $LecLat  = $this->input->post('LecLat') == '' ? null : $this->input->post('LecLat');
        $LecLong   = $this->input->post('LecLong') == '' ? null : $this->input->post('LecLong');
        $LecCom   = $this->input->post('LecCom') == '' ? null : $this->input->post('LecCom');
        $LecFchRg   = $this->input->post('LecFchRg') == '' ? null : $this->input->post('LecFchRg');

        $img = $this->input->post('LecImg');
        if($img!='')
        {
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $lectura = $this->Lectura_model->get_one_lectura($LecId);

            $nombreImagenLectura = $lectura['SolFchEj']."_".$lectura['LecId']. ".png";

            $dir_path = FCPATH . 'assets/archivos/toma_estado/fotos_lectura/';
            if (!file_exists($dir_path)) 
            {
                mkdir($dir_path, DIR_WRITE_MODE, true);
            }
            file_put_contents($dir_path.$nombreImagenLectura, $data);

            $LecImg = 'assets/archivos/toma_estado/fotos_lectura/'.$nombreImagenLectura;
        }
        else
        {
            $LecImg = null;
        }
        
        $resultado = $this->Lectura_model->insert_lectura_sincronizacion_movil($LecVal,$LecValInt1,$LecValInt2,$LecValInt3,$LecInt,$LecLat,$LecLong,$LecImg,$LecCom,$LecFchRg,$LecId);
        
        if($resultado)
        {
            $json = array('result' => true, 'mensaje' => 'Lectura '.$LecId.' Sincronizada!' , 'LecId' => $LecId);
        }
        else
        {
            $json = array('result' => false, 'mensaje' => 'Error : Lectura '.$LecId.', No puedo ser Sincronizada!');
        }
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($json));
    }
   
    // SINCRONIZAR DATOS DE OBSERVACIONES DE LECTURA DESDE APLICACION MOVIL 
    public function sincronizar_observaciones_lecturas_json()
    {
        $OleLecId  = $this->input->post('OleLecId');
        $OleObsId  = $this->input->post('OleObsId');
        $OleObsCod = $this->input->post('OleObsCod');
        $img = $this->input->post('OleImg');
        
        if($img!='')
        {
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $lectura = $this->Lectura_model->get_one_lectura($OleLecId);

            $nombreImagenObservacion = $lectura['SolFchEj']."_".$lectura['LecId']."_".$OleObsCod.".png";

            $dir_path = FCPATH . 'assets/archivos/toma_estado/fotos_observaciones/';
            if (!file_exists($dir_path)) 
            {
                mkdir($dir_path, DIR_WRITE_MODE, true);
            }
            file_put_contents($dir_path.$nombreImagenObservacion, $data);

            $OleImg = 'assets/archivos/toma_estado/fotos_observaciones/'.$nombreImagenObservacion;
        }
        else
        {
            $OleImg = null;
        }
        
        $resultado = $this->Lectura_model->insert_observaciones_lectura_sincronizacion_movil($OleLecId,$OleObsId,$OleImg);
        
        if($resultado)
        {
            $json = array('result' => true, 'mensaje' => 'Datos de Observaciones de Lectura : '.$OleLecId.' Sincronizada!' , 'OleLecId' => $OleLecId , 'OleObsId' => $OleObsId);
        }
        else
        {
            $json = array('result' => false, 'mensaje' => 'Datos de Observaciones de Lectura : '.$OleLecId.' No fueron Sincronizados');
        }
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($json));
    }
    
    
    /*  SINCRONIZAR DATOS DE LECTURA DESDE APLICACION MOVIL : VERSION CON File Transfer de Phonegap 
    public function sincronizar_datos_lecturas_json() 
    {
        $LecId = $this->input->post('LecId');
        $LecVal  = $this->input->post('LecVal') == '' ? null : $this->input->post('LecVal');
        $LecValInt1 = $this->input->post('LecValInt1') == '' ? null : $this->input->post('LecValInt1');
        $LecValInt2 = $this->input->post('LecValInt2') == '' ? null : $this->input->post('LecValInt2');
        $LecValInt3 = $this->input->post('LecValInt3') == '' ? null : $this->input->post('LecValInt3');
        $LecInt  = $this->input->post('LecInt') == '' ? null : $this->input->post('LecInt');
        $LecLat  = $this->input->post('LecLat') == '' ? null : $this->input->post('LecLat');
        $LecLong   = $this->input->post('LecLong') == '' ? null : $this->input->post('LecLong');
        $LecCom   = $this->input->post('LecCom') == '' ? null : $this->input->post('LecCom');
        $LecFchRg   = $this->input->post('LecFchRg') == '' ? null : $this->input->post('LecFchRg');
        $LecImg = null;
        $resultado = $this->Lectura_model->insert_lectura_sincronizacion_movil($LecVal,$LecValInt1,$LecValInt2,$LecValInt3,$LecInt,$LecLat,$LecLong,$LecImg,$LecCom,$LecFchRg,$LecId);
        if($resultado)
        {
            $json = array('result' => true, 'mensaje' => 'Datos de Lectura '.$LecId.' Sincronizada!' , 'LecId' => $LecId);
        }
        else
        {
            $json = array('result' => false, 'mensaje' => 'Error : Lectura '.$LecId.', No puedo ser Sincronizada!');
        }
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($json));
    }
    public function sincronizar_imagen_lecturas_json() 
    {
        $LecId = $_FILES["file"]["name"];
        $lectura = $this->Lectura_model->get_one_lectura($LecId);
        
        $nombreImagenLectura = $lectura['SolFchEj']."_".$lectura['LecId']. ".jpg";
        $dir_path = FCPATH . 'assets/archivos/toma_estado/fotos_lectura/';
        
        $LecImg = 'assets/archivos/toma_estado/fotos_lectura/'.$nombreImagenLectura;
        move_uploaded_file($_FILES["file"]["tmp_name"], $dir_path.$nombreImagenLectura);
        
        $resultado = $this->Lectura_model->insert_imagen_lectura_sincronizacion_movil($LecImg, $LecId);
        
        if($resultado)
        {
            $json = array('result' => true, 'mensaje' => 'Imagen de Lectura con Id : '.$LecId.' Sincronizada!' , 'LecId' => $LecId);
        }
        else
        {
            $json = array('result' => false, 'mensaje' => 'Error : Imagen de Lectura con Id : '.$LecId.', No puedo ser Sincronizada!');
        }
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($json));
    }
     */

}
