
<?php 

class Analog_Input_Category_Model extends CI_Model {

	public $table = 'analog_input_categories';
	public $mandatoryField = array('nome'); 
	public $fieldToRemove = array();


	public function __construct() {
		parent::__construct();
		$this->load->database('locale');
		$this->load->model('utility_model');
		$this->load->model('analog_input_model');            

	}

	public function getItems($params = null){		
		$this->db->select("");
		$this->setFilters($params);
		return $this->db->get($this->table)->result_array();	
	}

	public function getItem($idItem){
		$this->db->select('*')->where($this->table . '.id', $idItem);
		return $this->db->get($this->table)->row();  
	}

	public function getItemCount(){
		$this->getSelectQuery();
		$this->setFilters($params);
		return $this->db->get($this->table)->num_rows();
	}

	public function newItem($item){ 
		return $this->utility_model->executeRequest('NEW',$this,null,$item);
	}

	public function deleteItem($id){
		return $this->utility_model->executeRequest('DELETE',$this, $id);
	}

	public function updateItem($id,$item){
		return $this->utility_model->executeRequest('UPDATE',$this,$id,$item);
	}

	public function getDomain($params = null){
			
		$this->db->select("id , nome as description");
		$this->setFilters($params);

		return $this->db->get($this->table)->result_array();
		
	}

	private function setFilters($params = null){
		if(!$params){
			return ;
		}

		if(isset($params['filterTable'])){
			$this->db->where($this->table . '.nome LIKE "%' .$params['filterTable'].'%" ');
		}
	}

	public function hasLinkedElement($id){
        $qParams = array("idTipo" => $id);
		return $this->analog_input_model->getItemCount($qParams) > 0;
    }

	public function hasLinkedFile($id){
		return false;
	}


}

?>