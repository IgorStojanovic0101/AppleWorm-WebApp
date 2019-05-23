<?php 
//includes         //!!!!! javlja se greska incomplete object...!!!!!!!
require ("../Klase/Administrator.php");     //ovo je ubaceno, jer sesija mora da zna za Administrator-a i Namirnicu
session_start();                            //kad se deserijalizuje objekat pa zato mora da bude ispred session_start()
include("header.php");
include ("../Includes/DbOperations.php");
require ("../Klase/Aktivnost.php");
require ('../Klase/Namirnica.php');  

//logovanje
if(isset($_SESSION["admin"]))       //ako nije ubaceno, onda nije prosao login
{
    if(isset($_POST["logout"])){
        unset($_SESSION["admin"]);
        header("Location: admin_login.php");
    }
    $admin = $_SESSION["admin"];        //postavljeno u sesiju u login funkciji
}
else{
    $_SESSION["greska"]="Niste prijavljeni!";
    header("Location: admin_greska.php");
}

//izabrana promena slike
if(isset($_POST["btnSlika"]) && isset($_FILES["slika"]) &&
     $_FILES["slika"]["size"]>0 && $_FILES["slika"]["size"]<10000000)
{
    $putanja = $_FILES["slika"]["tmp_name"];
    $fp = fopen($putanja, 'r');
    $sadrzaj = fread($fp, filesize($putanja));
    fclose($fp);
    $admin = new Administrator($admin->id, $admin->username, $admin->password, $admin->email, $sadrzaj);
    $db = new DbOperations();
    if(!$db->updateAdmin($admin))//azurira se slika u bazi
        header("Location:admin_greska.php");
}
//izabrana promena username
else if(isset($_POST["btnUsername"]) && $_POST["txtNoviUsername"]!="")
{  
    $db = new DbOperations();
    $admin = new Administrator($admin->id, $_POST["txtNoviUsername"], $admin->password, $admin->email, $admin->image);
    if(!$db->updateAdmin($admin))
           header("Location:admin_greska.php");
    $_SESSION["admin"] = $admin;
    $msg="Korisnicko ime uspesno promenjeno.";
    include '../Includes/PopUp.php';
}
//izabrana promena lozinke
else if (isset($_POST["btnPassword"])){
    if(md5($_POST["txtPasswordOld"])===$admin->password &&      //stari pass ispravan
        $_POST["txtPasswordNew"]!="" && 
            $_POST["txtPasswordRep"]!="" &&                     //polja popunjena
                $_POST["txtPasswordNew"] === $_POST["txtPasswordRep"])  //dva puta uneta ista nova lozinka
    {
        $db = new DbOperations();
        $pass = md5($_POST["txtPasswordNew"]);
        $admin = new Administrator($admin->id, $admin->username, $pass, $admin->email, $admin->image);
        if(!$db->updateAdmin($admin))
           header("Location:admin_greska.php");
        
        $msg="Lozinka uspesno promenjena.";
        include '../Includes/PopUp.php';
    }
    else{
        $_SESSION["greska"]="Lose popunjena polja !";
        header("Location: admin_greska.php");
    }
}
$_SESSION["admin"]=$admin;      //postavlja se novi, promenjeni, u sesiji (za sve 3 prethodne f-je na jednom mestu)
?>
<link href="../css/admin.css" type="text/css" rel="stylesheet" />
<main>
	<article class="mainContent" id="cont">
		<header>
			
		</header>
		<content>
                    <?php 
                    
                    include "../Includes/GlavneFunkcijeNamirnice.php";
                    include "../Includes/GlavneFunkcijeAktivnosti.php";
                    include "../Includes/AdminGlavneForme.php";
                    
                    ?>
                        
		</content>
	</article>
	<!--
	<article>
	</article>
	-->
</main>
<aside>
	<article class="top-sidebar">
                    <form method="post" action="admin.php">
                        <?php 
                        $src = $admin->image;
                        if($src==="" || is_null($src))
                            echo "<img widht='100px' height='100px' id='profil' src='../Images/profile.png' name='profil'/>";
                        else
                            echo "<img widht='100px' height='100px' id='profil' src='getImage.php' name='profil'/>";
                        ?>
                        <h2><?php echo($admin->username);?></h2>
                        <label>username administratora</label>
                        <input class="btnSubmit" type="submit" name="logout" value="Log Out" />
                    </form>
	</article>
	<article class="middle-sidebar">
            <h2>Izaberite akciju:</h2>
                <ul>
                    <li><a id="aZahNam" href="admin.php?zn_page=1&mode=zahtevi_namirnica#cont">Zahtevi korisnika za namirnice</a></li>
                    <li><a id="aZahAkt" href="admin.php?za_page=1&mode=zahtevi_aktivnost#cont">Zahtevi korisnika za aktivnosti</a></li>
                    <li><a id="aDodNam" href="admin.php?mode=nova_namirnica#cont">Dodavanje nove namirnice</a></li>
                    <li><a id="aDodAkt" href="admin.php?mode=nova_aktivnost#cont">Dodavanje nove aktivnosti</a></li>
                    <li><a id="aPriNam" href="admin.php?mode=prikaz_namirnica#cont">Prikaz namirnica</a></li>
                    <li><a id="aPriAkt" href="admin.php?mode=prikaz_aktivnosti#cont">Prikaz aktivnosti</a></li>
                </ul>
        </article>
	<article class="bottom-sidebar">
		<h2>Podesavanja naloga</h2>
                <div class="div-lista">
                    <ul>
                        <li><a class="activeLink" id="aSlika" href="#">Slika</a></li>
                        <li><a id="aUsername" href="#">Username</a></li>
                        <li><a id="aPass" href="#">Password</a></li>
                    </ul>
                </div>
                <div id="dSlika" class="div-slika">
                    <form action="admin.php" method="post" enctype="multipart/form-data">
                        <label><h3>Promena slike:</h3><br /></label>
                        <input class="btnFajl" type="file" name="slika" />
                        <input class="btnSlika" type="submit" name="btnSlika" value="Sacuvaj" />
                    </form>
                </div>
                <div id="dUsername" class="div-username">
                    <form action="admin.php" method="post">
                        <label><h3>Promena korsinickog imena:</h3><br /></label>
                        <label>Username: <?php echo $admin->username;?></label><br />
                        <input type="text" name="txtNoviUsername" placeholder="Novi username..."/><br />
                        <input class="btnUsername" type="submit" name="btnUsername" value="Sacuvaj" />
                    </form>
                </div>
                <div id="dPass" class="div-password">
                    <form action="admin.php" method="post">
                        <label><h3>Promena lozinke:</h3><br /></label>
                        <input type="password" name="txtPasswordOld" placeholder="Password..."/>
                        <input type="password" name="txtPasswordNew" placeholder="Novi password..."/>
                        <input type="password" name="txtPasswordRep" placeholder="Ponovi novi password..."/><br />

                        <input class="btnPassword" type="submit" name="btnPassword" value="Sacuvaj" />
                    </form>
                </div>

	</article>
</aside>
<script src="../Includes/adminFunctions.js"></script>   

<?php include("footer.php");?>
	
	