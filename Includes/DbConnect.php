<?php

include_once 'Constants.php';

class DbConnect{
    
    private $con;
    
    public function __construct() {
        
    }
    
    public function connect() {
        $this->con = new mysqli(db_host, db_user, db_password, db_name);
        $this->con->set_charset('utf-8');
        
        if($this->con->connect_errno){
            $this->greska("Greska prilikom uspostavljanja konekcije");
            return false;
        }
        return $this->con;    
    }
    
     function greska ($poruka){      //vidi da li moze da se prebaci u fajl DodatneFunkcije (dvoumim se zbog con)
        $this->msg = "<strong>";    //mozda ce treba da se salje i con obj
        $this->msg .= $poruka;
        $this->msg .="</strong><br />";
        $this->msg .= mysqli_error($this->con); 
        $_SESSION["greska"] = $this->msg;
    }
}
/*Prebaceno sve u DbOperations klasu*/

