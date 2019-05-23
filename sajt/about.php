
<?php include("header.php");?>

<main>
	<article class="mainContent">
		<header>
			<h1>Još nešto o aplikaciji...</h1>
		</header>
		<footer>
			<p class="postInfo">Struktura aplikacije<p>
		</footer>
		<content>
			
                    &emsp;Aplikacija AppleWorm izrađena je na principu troslojne arhitekture. <b>Klijentski</b> deo je predstavljen android aplikacijom koja je prilagođena za uređaje sa verzijom androida 4.0.0 i novijim. U aplikaciji se pre svega korisnici prijavljuju šta otvara pristup svim ostalim funkcionalnostima prilagođenim za korisnike. 
                    <br />&emsp;Aplikacija komunicira sa <b>središnjim</b>, serverskim slojem. Korisnici odgovarajućim akcijama aktiviraju php skripte na serverskoj strani. Da bi se u android aplikaciji pristupilo resursima iz baze neophodno je da se kontaktira serverski sloj koji onda generiše odgovarajuće upite ka bazi.  Na tom, središnjem sloju, se takođe vrši i delimična obrada podataka koji se vraćaju do android uređaja.
                    <br />&emsp;<b>Treći</b> sloj AppleWorm sistema je MySQL baza podataka. U njoj se pamte sve dostupne namirnice i aktivnosti, kao i osnovni podaci o administratorima i korisnicima aplikacije.

		</content>
	</article>
	<!--
	<article>
	</article>
	-->
</main>

<script src="../Includes/dinamickiTekst.js"></script> 
<?php include("aside.php");?>
<?php include("footer.php");?>
	
	