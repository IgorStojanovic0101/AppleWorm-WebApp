<?php
include "../Includes/DbUser.php";
$db = new DbUser();

if($_GET["id_namirnice"]){
    $id=$_GET["id_namirnice"];
    
    $namirnica = $db->vratiJednuNamirnicu($id);
    
    echo json_encode($namirnica);
}
