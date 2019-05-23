<?php
include "../Includes/DbUser.php";
$db = new DbUser();

$response = array();


//TREBA ISPITIVANJE USLOVA!!!!!!!!

$kategorija = "%";
$sortiranje="Kalorije";
$redosled = "ASC";
$liimt = "LIMIT 0,10";
$namirnice = $db->vratiNamirnice($kategorija,$sortiranje,$redosled,$liimt);//($limit);

if($namirnice != null){
    $response['error']=false;
    $response['namirnice']=$namirnice;
}else{
    $response['error']=true;
    $response['msg']="Greska pri izvrsenju operacije pribavljanja namirnica!";
}

echo json_encode($response);


