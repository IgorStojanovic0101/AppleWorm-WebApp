
<?php 
include("header.php");
require_once ("../Includes/DbOperations.php");
?>

<?php
    if(isset($_POST["btnPosalji"])){
        $db = new DbOperations(); 
        $a = $db->resetPass($_POST["email"]);
        if($a != null) 
        {
            if(SendMessage($a)){
                $msg="Email uspesno poslat.";
                include ("../Includes/PopUp.php");
            }
            else{
                $msg="Slanje email-a neuspesno. Proverite intenet konekciju i pokusajte ponovo kasnije.";
                include("../Includes/PopUp.php");
            }
        }
        else{
            header("Location: admin_greska.php");
        }
    }
?>



<main>
	<article class="mainContent">
		<header>
			<h1>Proces oproravka lozinke</h1>
		</header>
		<content>
                    <i>Unesite vašu email adresu u polje za unos. <br />
                        Nakon klika na dugme pošalji, na vaš mail će stići generisana lozinka.</i>
                    <form method="POST" action='admin_forgot.php'>
                        <input type="text" name="email" placeholder="Vasa adresa.." />
                        <input type="submit" name="btnPosalji" class="btnSubmit"  value="Pošalji" />
                    </form>
		</content>
	</article>
</main>

<link href="../css/forgot.css" rel="stylesheet" type="text/css"/>


<?php include("aside.php");?>
<?php include("footer.php");?>
	
	