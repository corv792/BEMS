<?php 

define('ERROR_GENERIC','-1000');
define('ERRROR_MANDATORY_FIELD','-1001');
define('ERRROR_NO_ID_ITEM','-1002');
define('ERRROR_NO_ITEM','-1003');
define('ERROR_LINKED_ITEM','-1004');
define('ERROR_FILE_NOT_FOUND','-1005');
define('ERROR_FILE_DELETE','-1006');



class Error_Model extends CI_Model {


    public function __construct() {
        parent::__construct();
        $this->load->database('locale');
    }

    private $currentErrorCode = ERROR_GENERIC;

    public $errors = array(
        ERROR_GENERIC => "Errore generico",
        ERRROR_MANDATORY_FIELD  => "Mancano campi obbligatori",
        ERRROR_NO_ID_ITEM  => "L'id selezionato non è valido",
        ERRROR_NO_ITEM  => "L'oggetto non esiste",
        ERROR_LINKED_ITEM  => "Impossibile eliminare, l'elemento è collegato ad altre entità nel sistema",
        ERROR_FILE_NOT_FOUND  => "Impossibile trovare il file richiesto",
        ERROR_FILE_DELETE  => "Impossibile eliminare il file richiesto",


    );

    public function setError($error){
        $this->currentErrorCode = $error;
    }

    public function getErrorDescription(){
        return $this->errors[$this->currentErrorCode];
    }

    public function getErrorCode(){
        return $this->currentErrorCode;
    }


    

}


?>