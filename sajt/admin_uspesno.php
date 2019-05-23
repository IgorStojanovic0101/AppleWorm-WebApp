<?php

require_once "../Klase/Administrator.php";
session_start();

if(!isset($_SESSION["admin"])){
    $_SESSION["greska"] = "Niste prijavljeni!";
    header("Location: admin_greska.php");
}
?>
<?php include("header.php");?>

<main>
	<article class="mainContent">
		<header>
			<h1>Dobrodošli!</h1>
                        Administrator: <?php echo($_SESSION["admin"]->username); ?>
		</header>
		<content>
                    <form method="post" action="admin.php">
			Uspesno ste registrovali svoj nalog. Kliknite na dugme da biste nastavili...<br />
                        
                        <input type="submit" value="Nastavi" />
                    </form>
		</content>
	</article>
	<!--
	<article>
	</article>
	-->
</main>

<?php include("aside.php");?>
<?php include("footer.php");?>
	
<!--
    Treba da ova stranica ima isti koncept kao admin_greska.php, tj da prikazuje poruke iz session niza da bi
    mogla da se koristi za vise stvari...
-->