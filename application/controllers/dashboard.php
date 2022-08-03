<?php 



class Dashboard extends CI_Controller {

    

    public function __construct() {



        parent::__construct();

            if (property_exists($this, 'load'))

            {

                $this->load->model('user_model');

                $this->load->model('login_model');



            }           

    }



	public function index(){       

        

	}





    public function getInformationDashboardCard(){       



        // GET STIPENDIO

        $params = array('categoria'=> 14 , 'order' => 'data:desc');



        $tmp =  $this->item_model->getItems($params);

        $result['stipendio'] = $tmp[0]['importo'];





        $params = array('order' => 'data:desc' , 'dataInizio'=> '2022-05-01');



        $x = 0;

        $tmp =  $this->item_model->getItems($params);

        foreach ($tmp as $item){

            $x+=$item['importo'];

        }



        $result['spesa'] = $x;



        $this->utility_model->composeResponse($this->output,$result);



	}



}