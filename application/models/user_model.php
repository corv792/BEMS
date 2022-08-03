<?php 

class User_Model extends CI_Model {

	public $table = 'Users';
	public $mandatoryField = array('nome','cognome','email' ,'cellulare');
	public $keyNullable = array();
	public $fieldToRemove = array();

	public function __construct() {
			parent::__construct();
			$this->load->database('locale');
			$this->load->model('utility_model');
	}

	// -------------------- CRUD ------------------------------ //

	public function getItems($params = null){	
		return $this->utility_model->executeRequest('LIST',$this,null,null,$params);
	}

	public function getItem($id){
		return $this->utility_model->executeRequest('GET',$this,$id);
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

	// -------------------- UTILITY ------------------------------ //

	public function getSelectQuery(){
		$this->db->select($this->table.".* ");
	}

	public function setFilters($params = null){
		if(!$params){
			return ;
		}

		$keyFilters = array_keys($params);

		foreach ($keyFilters as $key) {
			switch($key){
				case 'searchFilter' : 
					$this->db->where($this->table . '.email LIKE "%' .$params[$key].'%" OR ' . $this->table . '.nome LIKE "%' .$params[$key].'%"');
					break;
				default : break;
			}
			
		}

	}

	public function getItemCount(){
			$this->db
			->select('*');
			return $this->db->get($this->table)->num_rows();
	}

	public function hasLinkedFile($id){
		return false;
	}

	// -------------------- CUSTOM ------------------------------ //


}

?>