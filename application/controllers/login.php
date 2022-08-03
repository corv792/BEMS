<?php 
require "vendor/autoload.php";
use \Firebase\JWT\JWT;

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (property_exists($this, 'load')){
            $this->load->model('login_model');
            $this->load->model('user_model');
            $this->load->model('token_model');
            $this->load->model('utility_model');
            $this->load->model('error_model');

        } 
      
    }

    public function index(){

      $secretKey= array(
        'jwtSecret'=> '38a2f3f3e42c780021d03d126e89e6e77ddd2ec1', // Secret Password for Jwt
        'jwtHeader'=> 'AppAuthorization', // HTTP Header dove sarà inserito il token JWT
        'salt' => array('password'=>'9430311ba652d504c2','reset'=>'c9db88b82650ac2b385'), //Salt for generate Password
      );

      $data = json_decode(file_get_contents("php://input"));

      if($data->email == "" || $data->password == "" ){
        echo json_encode(array("message" => "Inserisci delle credenziali valide"));
        http_response_code(300);  
      }else{
          $input['email'] =$data->email;
          $input['password'] =$data->password;

          $result=$this->login_model->checkCredenziali($input);
          if(!$result){
            $response = $this->utility_model->createErrorResponse('Credenziali errate');
         
            return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));   
          }
          $tokenData = array(
              'id' => $result['id'],
              'email' =>  $result['email'],
              'ruolo' => $result['ruolo']
          );

          $token = $this->token_model->getToken($tokenData, '38a2f3f3e42c780021d03d126e89e6e77dcc2ec1');
          $token = $this->utility_model->createResponse($token, 'NO_ERROR');
          $this->output->set_status_header(200)->set_content_type('application/json')
          ->set_output(json_encode($token));
      }
      
    }
}


?>