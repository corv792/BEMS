<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AnalogInput extends CI_Controller {

	public function index()
	{

  
	}  


    public function get(){

        //Aggancio valori filtri
        $filter = $this->input->get('some_variable', TRUE);

        $servername = "31.11.39.26";
        $username = "Sql1651738";
        $password = "Database510916!";
        $dbname = "Sql1651738_1";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }

        $dataInizio = $filter['dataInizio'];
        $dataInizio = $filter['dataFine'];
        $tipo = $filter['tipo'];


        $sql = "SELECT valore,timestamp FROM AnalogInput where idTipo = 4;";

        // Se nei filtri c'è anche data inizio
        if(isset($filter['dataInizio'])){
            $sql=. " AND timestamp > '".$dataInizio."'"
        }

        if(isset($filter['dataFine'])){
            $sql=. " AND timestamp < '".$dataFine."' "
        }

        if(isset($filter['tipo'])){
            $sql=. " AND idTipo = ".$tipo;
        }

        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) { 
            echo json_encode($row);

         } 
	}

    public function post(){
		
	}

    public function put(){
		
	}

    public function delete($id){
		
	}

    
}
