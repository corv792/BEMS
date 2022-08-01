<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{			
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
        $dataInizio = "2022-01-01 06:00:00";
        $dataFine = "2022-04-18 06:00:00";
        $sql = "SELECT valore,timestamp FROM AnalogInput where idTipo = 4 and timestamp > '".$dataInizio."' and timestamp < '".$dataFine."' ;";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) { 
            echo json_encode($row);

         } 
		// $this->load->view('welcome_message');
	}
}
