<?php

?>

<div id="glNamirnicaDodaj">
    <h1>Dodavanje nove namirnice</h1>
    <form id="formaNovaNam" method="post" action="admin.php?mode=nova_namirnica#cont" enctype="multipart/form-data">
      <div id="kontejner">                                                                  <!--DODATO za poravnanje-->
        <label>Naziv: </label> <input type="text" name="naziv" /><br />

        <label>Kategorija: </label>
        <select name="kategorija">
            <option id="zitarice">Žitarica</option>
            <option id="testenie">Testenina</option>
            <option id="mesnati">Mesnati proizvod</option>
            <option id="mlecni">Mlečni proizvod</option>
            <option id="voce">Voće</option>
            <option id="povrce">Povrće</option>
            <option id="slatkisi">Slatkiš</option>
            <option id="pice">Piće</option>
            <option id="jelo">Jelo</option>
            <option id="ostalo">Ostalo</option>
        </select>
        <br />

        <label>Kalorije: </label> <input type="number" step="0.1" name="kalorije" /><br />
        <label>Proteini: </label> <input type="number" step="0.1" name="proteini" /><br />
        <label>Masti: </label> <input type="number"  step="0.1" name="masti" /><br />
        <label>Ugljeni hidrati: </label> <input type="number" step="0.1" name="ugljeni_hidrati" /><br />

        <label>Slika:</label> <input type="file" name="slika" /><br />

        <input type="submit" name="btnDodajNamirnicu" value="Dodaj" />
      </div>
    </form>
</div>

<div id="glPrikazNamirnica">
    <h1>Prikazivanje namirnica</h1>
    <form id="formaPrikazNam" method="get" action="admin.php">
        <input type="hidden" name="page" value="1" /><!--Da bi odma usao u if sa $_GET["page"]-->
        
        <label>Prikazi kategoriju: </label>
        <select name="kategorija">
            <option id="sve">Sve kategorije</option>
            <option id="zitarice">Žitarica</option>
            <option id="testenie">Testenina</option>
            <option id="mesnati">Mesnati proizvod</option>
            <option id="mlecni">Mlečni proizvod</option>
            <option id="voce">Voće</option>
            <option id="povrce">Povrće</option>
            <option id="slatkisi">Slatkiš</option>
            <option id="pice">Piće</option>
            <option id="jelo">Jelo</option>
            <option id="ostalo">Ostalo</option>
        </select>
        <label>Sortiraj po: </label>
        <select name="sortiranje">
            <option id="sortKategorija">Kategorija</option>
            <option id="sortKalorije">Kalorije</option>
            <option id="sortProteini">Proteini</option>
            <option id="sortMasti">Masti</option>
            <option id="sortUgljHidr">Ugljeni hidrati</option>
        </select>
        <label>Redosled: </label>
        <select name="redosled">
            <option id="rastuce">Rastuce</option>
            <option id="opadajuce">Opadajuce</option>
        </select>
        
        <input type="hidden" name="mode" value="lista_namirnica" />
        <input type="submit" value="Prikazi" name="btnPrikaziNamirnice"/>
    </form>
</div>


<div id="glAktivnostDodaj">
    <h1>Dodavanje nove aktivnosti</h1>
    <form method="post" action="admin.php?mode=nova_aktivnost#cont" enctype="multipart/form-data">
        <label>Naziv: </label> <input type="text" name="naziv" /><br />
        <label>MET vrednost: </label> <input type="number" step="0.1" name="met_vrednost" /><br />
        <label>Slika: </label> <input type="file" name="slika" /><br />

        <input type="submit" name="btnDodajAktivnost" value="Dodaj" />
    </form>
</div>

<div id="glPrikazAktivnosti">
    <h1>Prikazivanje aktivnosti</h1>
    <form method="get" action="admin.php">
        <input type="hidden" name="akt_page" value="1" />
        <label>Sortiraj po: </label>
        <select name="sortiranje">
            <option id="naziv">Naziv</option>
            <option id="met_vrednost">MET vrednost</option>
        </select>
        <label>Redosled: </label>
        <select name="redosled">
            <option id="rastuce">Rastuce</option>
            <option id="opadajuce">Opadajuce</option>
        </select>
        
        <input type="hidden" name="mode" value="lista_aktivnosti" />
        <input type="submit" name="btnPrikaziAktivnosti" value="Prikazi" />
    </form>
</div>