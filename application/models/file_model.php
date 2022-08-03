<?php 

class File_Model extends CI_Model {

	public $table = 'Files';
	public $tableCategory = "File_type";

	public $mandatoryField = array('codice','id_type','size');
	public $fieldToRemove = array('categoria', 'um');
	public $keyNullable = array('id_transaction');


	public function __construct() {
		parent::__construct();
		$this->load->database('locale');
		$this->load->model('utility_model');
		$this->load->model('upload_file_model');
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
		$this->db->select($this->table.".* ,  file_type.description as categoria, file_type.ext as ext");
		$this->db->join($this->tableCategory, $this->table.'.id_type = file_type.id');
	}

	public function hasLinkedElement($id){
       return false;
    }


	public function setFilters($params = null){
		if(!$params){
			return ;
		}

		$keyFilters = array_keys($params);

		foreach ($keyFilters as $key) {
			switch($key){
				case 'searchFilter' : 
					$this->db->where($this->table . '.codice LIKE "%' .$params[$key].'%"');
					break;
				case 'categoria' : 
					$this->db->where($this->tableCategory . '.id ="' .$params[$key].'" ');
					break;
				default : break;
			}
			
		}

	}
	
	public function getItemCount($params){
		$this->getSelectQuery();
		$this->setFilters($params);
		return $this->db->get($this->table)->num_rows();
	}

	// -------------------- FILE ------------------------------ //

	public function hasLinkedFile($id){
		$file = $this->utility_model->executeRequest('GET',$this,$id);

		if(!empty($file['filename'])){
			return true;
		}

		return false;
	}

	public function deleteAssociateFile($id){
		return $this->upload_file_model->executeRequestDelete('BILL_PDF',$id);
	}

	public function getFile($id){
		return $this->upload_file_model->composeGetFile("BILL_PDF",$id,$this);
	}
	

	// -------------------- CUSTOM ------------------------------ //


}

?>