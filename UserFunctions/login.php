<?php
include "../Includes/DbUser.php";

$db = new DbUser();

$response = array();
if (isset($_POST["username"]) && isset($_POST["password"]))
{
    $user = $db->returnUser($_POST["username"], $_POST["password"]);
    if($user != null){
        $response['error']=false;
        $response['user'] = $user;
    }else{
        $response['error']=true;
        $response['msg'] = "Uneli ste nevalidne podatke! Pokusajte ponovo.";
    }
    echo json_encode($response);
}else{
    $response['error']=true;
    $response['msg']="Molimo vas popunite sva polja!";
    echo json_encode($response);
}


