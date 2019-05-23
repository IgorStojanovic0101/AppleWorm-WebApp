<?php

include ("../phpmailer/PHPMailerAutoload.php");
include_once ("../Klase/Administrator.php");

function GeneratePass(){
    $karakteri="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    $pass="";
    for($i=0;$i<8;$i++){
        $index = rand(0, strlen($karakteri)-1);
        $pass .= $karakteri[$index];
    }
    return $pass;
}

function SendMessage(Administrator $a){
    
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    
    $mail->Username = "applewormaplikacija@gmail.com";
    $mail->Password = "phpmail123";
    $mail->setFrom('applewormaplikacija@gmail.com', 'AppleWorm');
    
    $mail->addAddress("$a->email", "$a->username");
    $mail->Subject = "Obnavljanje lozinke";
    
    $mail->Body = "Postovani korisnice $a->username,\r\n\r\n"
                    . "vasa lozinka za pristup sajtu je resetovana.\r\n"
                    . "Vasa nova lozinka je: $a->password\r\n"
                    . "Mozete je promeniti u podesavanjima naloga na sajtu.\r\n";
    
    //$mail->addAttachment('sajt/logo_mini.png');   ne prikazuje sliku
    if ($mail->send())
        return true;
    return false;
    
}


?>
