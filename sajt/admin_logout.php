<?php
session_start();
if(isset($_SESSION["admin"]))
{
    unset($_SESSION["admin"]);
}
else{
    $_SESSION["greska"]="Niste prijavljeni!";
    header("Location: admin_greska.php");
}
    
?>
<?php include("header.php");?>

<main>
	<article class="mainContent">
		<header>
			<h1>Hvala na poseti.</h1>
		</header>
		<content>
                    <a href="home.php">HOME</a>
                        
		</content>
	</article>
	<!--
	<article>
	</article>
	-->
</main>

<?php include("aside.php");?>
<?php include("footer.php");

/*Stranicu sam izbacio jer smatram da nije neophodno da se :D zahvaljuje administratoru na poseti. Sve je smesteno na 
    admin.php stranicu i kad se izloguje samo se vrati na stranicu za logovanje */
?>
	
