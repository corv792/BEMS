<?php 
class Utility_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database('locale');
        $this->load->model('config_model');
        $this->load->model('error_model');

    }

    // CREA IL MODELLO DI RITORNO DELLA CHIAMATA HTTP
    public function createResponse($data,$message='',$description='', $totalItemCount=0): array{

        $arrResponse = array(
            'item' => $data,
            'code' => 0,
            'message' => $message,
            'description' => $description
        );

        if ($totalItemCount>0){
            $arrResponse['totalItemCount'] = $totalItemCount;
        }

        return $arrResponse;
    }

    // CREA IL MODELLO DI RITORNO DELLA CHIAMATA HTTP
    public function createErrorResponse($errorMessage, $errorCode = -1): array{

        $arrResponse = array(
            'item' => false,
            'code' => $errorCode,
            'message' => $errorMessage,
            'description' => ''
        );

        return $arrResponse;
    }
  
    // RECUPERA I QUERY PARAMS DA UNA CHIAMATA HTTP
    public function getQueryParams(){
        $url = parse_url($_SERVER['REQUEST_URI']);
        if(isset($url['query'])){
            parse_str($url['query'], $params);
            return $params;
        }else{
            return array();
        }
    }

    // CONTROLLO I CAMPI E PREPARO L'ITEM
    public function checkAndPrepareItem($item,$mandatoryField,$fieldToRemove = array()){

        if(!$item){
            return false;
        }

		if(!$this->mandatoryFieldCheck($item,$mandatoryField)){
            $this->error_model->setError(ERRROR_MANDATORY_FIELD);
			return false;
		}

        $item = $this->clearId($item);

		foreach ($item as $key => $value){
            $item[$key]= str_replace('%20', ' ', $item[$key]);
        }

        foreach ($fieldToRemove as $key => $value){
            unset($item[$value]);       
        }

		return $item;
	}

    public function clearForeignKeyNullable($items, $keyNullable){
		for($i = 0; $i < count($items); $i++ ){
            for($y = 0; $y < count($keyNullable); $y++ ){
                if($items[$i][$keyNullable[$y]] == 0){
                    $items[$i][$keyNullable[$y]] = null;
                }
            }	
		}
        return $items;
    }

    // CONTROLLO CHE TUTTI I CAMPI OBBLIGATORI SIANO PRESENTI E VALORIZZATI
    public function mandatoryFieldCheck($item,$mandatoryField) : bool {
        
		foreach ($mandatoryField as $field){
			$found = false;
			foreach ($item as $key => $value){
				if($key === $field && $value !== '' && $value !== null){
					$found = true;
				}
			}
			if(!$found){
				return false;
			}
		}
		return true;
	}

    public function clearId($item){
        foreach ($item as $key => $value){
            $str = substr($key, 0 , 3);
            if ($str =='id_'){
                if( $value == 0 || $value == '' ||  $value == null){
                   $item[$key] = null;
                }
            }   
        }
        return $item;
	}


    public function linkDatabaseCostum(){
        // dati di accesso al database
        $servername = "scaffold";
        $username = "root";
        $password = "";
        $dbname = "my_conto";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        mysqli_set_charset($conn, "utf8");

        // Check connection
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

        /* change character set to utf8 */
        if (!$conn->set_charset("utf8"))
        {
            die("Error loading character set utf8: %s\n" . $conn->error);
        }

        return $conn;

    }

    public function composeResponse($output,$data){
        $result = $this->createResponse($data);
        if(is_array($data) ||  $data){
            $output->set_status_header($this->config_model->getHttpstatusOk())->set_content_type('application/json')->set_output(json_encode($result));
        }else{
            $result = $this->setErrorOutput($result);
            $output->set_status_header($this->config_model->getHttpstatusError())->set_content_type('application/json')->set_output(json_encode($result));
        }
    }

    public function responseApiCostum($status){
        if($status){
            $this->output->set_status_header(200)->set_content_type('application/json')
            ->set_output(json_encode(true));
        }else{
            $this->output->set_status_header(500)->set_content_type('application/json')
            ->set_output(json_encode(false));
        }
    }

    private function setErrorOutput($result){
        $result['code'] = $this->error_model->getErrorCode();
        $result['message'] = $this->error_model->getErrorDescription();
        return $result;
    }

    public function checkExistItem($id,$model) : bool{
		if(!$id){
			$this->error_model->setError(ERRROR_NO_ID_ITEM);
			return false;
		}
		
		if(!$model->getItem($id)){
			$this->error_model->setError(ERRROR_NO_ITEM);
			return false;
		}

        return true;
		
    }

    public function checkItem($id, $item, $model){
        if(!$this->checkExistItem($id,$model)){
			return false;
		}

		return $this->checkAndPrepareItem($item,$model->mandatoryField,$model->fieldToRemove);
    }


    // ###################################################################################################
    // FUNZIONI DI CRUD


	public function executeRequest($requestCode,$model,$id, $item= null , $params = null){
		switch($requestCode){
            case 'LIST':
				return $this->getItems($params,$model);
				break;
            case 'GET':
				return $this->getItem($id,$model);
				break;
			case 'DELETE': 
				return $this->deleteItem($id,$model);
				break;
			case 'NEW':
				return $this->newItem($item,$model);
				break;
			case 'UPDATE':
				return $this->updateItem($id,$item,$model);
				break;
			default : return false;
		}
	}

    private function updateItem($id, $item, $model){
        $item = $this->checkItem($id,$item,$model);
		if(!$item){
			return false;
		}
	
		return $this->db->update($model->table,$item,array('id' => $id));

    }

    private function deleteItem($id,$model){

        if(!$this->checkExistItem($id,$model)){
			return false;
		}

        if($this->hasLinkedElement($id,$model)){
            $this->error_model->setError(ERROR_LINKED_ITEM);
			return false;
		}

        if($this->hasLinkedFile($id,$model)){
            if(!$model->deleteAssociateFile($id)){
                return false;
            }
		}
	
		$this->db->where('id', $id);

		return $this->db->delete($model->table);
    }

    private function newItem($item,$model){ 
		$item = $this->checkAndPrepareItem($item,$model->mandatoryField,$model->fieldToRemove);
		if(!$item){
			return false;
		}
		
		return $this->db->insert($model->table, $item);

	}

    private function getItem($id,$model){
        $model->getSelectQuery();
		$model->db->where($model->table . '.id', $id);
		$result = $model->db->get($model->table)->result_array();
        if(!(count($result) > 0) ){
            $this->error_model->setError(ERRROR_NO_ITEM);
			return false; 
        }
		return $this->clearForeignKeyNullable($result,$model->keyNullable)[0];
    }

    private function getItems($params,$model){
        $model->getSelectQuery();
		$model->setFilters($params);
		$result = $model->db->get($model->table)->result_array();
		return $this->clearForeignKeyNullable($result,$model->keyNullable);
    }

    private function hasLinkedElement($id,$model){
       return $model->hasLinkedElement($id);
    }

    private function hasLinkedFile($id,$model){
        return $model->hasLinkedFile($id);
     }

    // ###################################################################################################


}



?>