<?php 



class Analog_Input_Model extends CI_Model {

    //Nome della tabella
	public $table = 'AnalogInput';
	public $tableCategory = "analog_input_categories";
	public $mandatoryField = array('idTipo','valore','timestamp');
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
		$this->db->select($this->table.".* ,  ".$this->tableCategory.".nome as categoria");
		$this->db->join($this->tableCategory, $this->table.'.idTipo ='.$this->tableCategory.'.id');
	}

	public function setFilters($params = null){

		if(!$params){

			return ;

		}



		$keyFilters = array_keys($params);



		foreach ($keyFilters as $key) {

			switch($key){

                case 'idTipo' : 

                    $this->db->where($this->table . '.idTipo ="' .$params[$key].'" ');

                    break;

                case 'dataInizio' : 

                    $this->db->where($this->table . '.timestamp >="' .$params[$key].'" ');

                    break;

                case 'dataFine' : 

                    $this->db->where($this->table . '.timestamp <="' .$params[$key].'" ');

                    break;

				case 'valoreMassimo' : 

					$this->db->where($this->table . '.valore <="' .$params[$key].'" ');

					break;

				case 'valoreMinimo' : 

					$this->db->where($this->table . '.valore >="' .$params[$key].'" ');

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

	public function hasLinkedFile($id){
		return false;
	}

	public function hasLinkedElement($id){
        return false;
    }



	// -------------------- CUSTOM ------------------------------ //



}



?>