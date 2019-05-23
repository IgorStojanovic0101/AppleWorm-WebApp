<?php

class Korisnik {
    public $id;
    public $username;
    public $password;
    public $email;
    public $starost;
    public $kilaza;
    public $visina;
    
    public function __construct($id,$mail,$usr,$pass,$starost,$kilaza,$visina) {
        $this->id = $id;
        $this->username = $usr;
        $this->password = $pass;
        $this->email = $mail;
        $this->starost = $starost;
        $this->kilaza = $kilaza;
        $this->visina = $visina;
    }
}
