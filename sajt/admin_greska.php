
<?php
    session_start();
    include("header.php");
?>

<main>
	<article class="mainContent">
		<header>
			<h1>GREŠKA!!!</h1>
		</header>
		<footer>
			Postoje izvesne nepravilnosti.<br />
                        <?php 
                            echo($_SESSION["greska"]);
                            echo("<br />");
                            unset($_SESSION["greska"]);
                        ?>
                        <br />
		</footer>
		<content>
                    <form action="admin_login.php" method="post">
			Molimo vas kliknite na dugme da bi ste se vratili na stranicu za logovanje i pokusate opet.<br />
                        <input type="submit" value="Nazad" />
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
    U ovom fajlu treba da se procita poruka iz niza sesije i da se ona prikaze. Poruke ce biti poslate iz razl. izvora.
    Npr. poruka o gresci pri konektovanju, ili izvrsenju upita, ili greska pri logovanju...
-->