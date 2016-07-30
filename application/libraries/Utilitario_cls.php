<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Utilitario_cls {

    var $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
    var $meses = array(0 => "Diciembre", 1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio", 7 => "Julio", 8=> "Agosto", 9=> "Septiembre", 10=>"Octubre", 11=>"Noviembre", 12=>"Diciembre", 13=>"Enero");
    var $mesesOri = array(1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio", 7 => "Julio", 8=> "Agosto", 9=> "Septiembre", 10=>"Octubre", 11=>"Noviembre", 12=>"Diciembre");

    public function nombreMes($mes) {
        return $this->meses[$mes];
    }
    
    public function numeroMes($nombre){
        return array_search($nombre, $this->meses);
    }
    
    public function getMes_x_num($mes){
        return array($mes, $this->meses[$mes]);
    }
    
    public function getMeses(){
        return $this->mesesOri;
    }

    public function getLecturasXhoja(){
        return 25;
    }
}
