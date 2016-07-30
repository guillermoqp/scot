<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Default_ctrllr
 *
 * @author mchuquimango
 */
class Default_ctrllr extends CI_Controller{
    public function __construct() 
    {
        parent::__construct();
    }
    public function inicio() {
        redirect(base_url() . 'inicio');
    }
}
