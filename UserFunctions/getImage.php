<?php
include "../Includes/DbUser.php";

$db=new DbUser();

$id = $_GET["id"];

$slika = $db->vratiSlikuNamirnice($id);

header("Content-type: image/jpeg");

echo $slika;


