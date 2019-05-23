<?php

require_once "../Includes/DbUser.php";
$db = new DbUser();
$response = array();

if (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"])
        &&isset($_POST["starost"])&&isset($_POST["kilaza"])&&isset($_POST["visina"]))
{
    
    if(!$db->checkEmail($_POST["email"])){  //provera da li postoji u bazi uneti email
        $response['error']=true;
        $response['msg']="Vec postoji osoba sa zadatim email-om!";
    }
    else if(!$db->checkUsername($_POST["username"])){//provera da li postoji u bazi uneti username
        $response['error']=true;
        $response['msg']="Vec postoji osoba sa zadatim username-om!";
    }
    else{
        $user = $db->createUser($_POST["username"], $_POST["email"], $_POST["password"], 
                $_POST["starost"], $_POST["kilaza"], $_POST["visina"]);
        if($user!=null){
            $response['error']=false;
            $response['user'] = $user;
        }else{
            $response['error']=true;
            $response['msg']="Greska pri kreiranju korisnika!";
        }
    }
    echo json_encode($response);
}else{
    $response['error']=true;
    $response['msg']="Molimo vas popunite sva polja!";
    echo json_encode($response);
}
