<?php

class Administrator {
    public $id;
    public $username;
    public $password;
    public $email;
    public $image;
    
    public function __construct($id,$usr,$pass,$mail,$img) {
        $this->id = $id;
        $this->username = $usr;
        $this->password = $pass;
        $this->email = $mail;
        $this->image = $img;
    }
}
