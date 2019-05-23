<?php

require_once "../Klase/Administrator.php";
session_start();

header("Content-type: image/jpeg");
 echo $_SESSION["admin"]->image;