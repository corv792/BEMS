<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
ob_start();
// session_start();

require "vendor/autoload.php";
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

class Token_Model extends CI_Model {
   
    // SETTING GENERAZIONE TOKEN
    private $tokenKey = "X435mx-D29slSm";
    private $tokenAlg = 'HS256';
    public function __construct() {
        parent::__construct();
        $this->load->database('locale');
    }

    public function getToken($data,$secret){

        $token=null;

        $curDateTime = time();
        $expDateTime = time() + (3600 * 24); // 1 giorno
        $tokenData = array(
            'iss' => $_SERVER['SERVER_NAME'],
            'aud' => $_SERVER['SERVER_NAME'],
            'iat' => $curDateTime,
            'nbf' => $curDateTime,
            'exp' => $expDateTime
        );

        foreach ($data as $key => $value){
            $tokenData[$key]=$value;
        }

        $token = JWT::encode($tokenData,$secret, "HS256");
        return $token;
    }

}


?>