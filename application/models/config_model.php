<?php 
class Config_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database('locale');
    }

    private $HTTP_STATUS_OK = 200;
    private $HTTP_STATUS_ERROR = 500;

    

    public function getHttpStatusOk(){
        return $this->HTTP_STATUS_OK;
    }

    public function getHttpStatusError(){
        return$this->HTTP_STATUS_ERROR;
    }


}


?>