<?php 
class Login_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database('locale');
    }

    public function checkCredenziali($input){
    
        $user = false;

        $query=$this->db->select('*')->where('Email',$input['email'])->get('Users');
        $result = $query->row();

        if(isset($result)){
            if ($input['password'] == $result->password){
                $user['id'] = $result->id;
                $user['ruolo'] = $result->ruolo;
                $user['email'] = $result->email;
            }
        }
        return $user;
    }

}


?>