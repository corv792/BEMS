<?php 

class AnalogInput extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->loadModels();
    }

    private function loadModels(){
        $this->load->model('utility_model');
        $this->load->model('login_model');
        $this->load->model('analog_input_model','item_model');  
    }

    public function getList(){ 
        $params =  $this->utility_model->getQueryParams();
        $this->utility_model->composeResponse($this->output,$this->item_model->getItems($params)); 
    }

    public function getSingle($idItem){ 
        $this->utility_model->composeResponse($this->output,$this->item_model->getItem($idItem));
    }

    public function deleteItem($idItem){ 
        $this->utility_model->composeResponse($this->output,$this->item_model->deleteItem($idItem));
    }

    public function newItem(){ 
        $item = json_decode(file_get_contents('php://input'), true);
        $this->utility_model->composeResponse($this->output,$this->item_model->newItem($item));
    }

    public function updateItem($id){ 
        $item = json_decode(file_get_contents('php://input'), true);
        $this->utility_model->composeResponse($this->output,$this->item_model->updateItem($id,$item));
    }

  
}
