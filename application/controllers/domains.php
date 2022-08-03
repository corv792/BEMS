<?php 



class Domains extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->loadModels();
    }

    private function loadModels(){
        $this->load->model('utility_model');
        $this->load->model('analog_input_category_model');          
        $this->load->model('login_model');          
    }

    public function getList(){ 
        $params =  $this->utility_model->getQueryParams();
        if(!isset($params['DOMAIN_KEY'])){
            $this->utility_model->composeResponse($this->output,false); 
            return;  
        }
        $this->getDomains($params);
    }

    public function getDomains($params){
        switch($params['DOMAIN_KEY']){
            case 'ANALOG_INPUT_CATEGORIES' : 
                $this->utility_model->composeResponse($this->output,$this->analog_input_category_model->getDomain($params)); 
                break;
            default : break;
        }
    }
}



