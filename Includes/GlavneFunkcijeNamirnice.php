<?php
include "../Includes/DbAdmin.php";
include "../Klase/ZahtevNamirnica.php";

$db = new DbAdmin();

//klik na dugme u formi za dodavanje namirnice 
if(isset($_POST["btnDodajNamirnicu"])){
    $odgovor = DodavanjeNamirnice();
    $msg = $odgovor["poruka"];
    include "../Includes/PopUp.php";
}

//kliknuto na dugme Izmeni(za odredjenu namirnicu)
else if (isset($_POST["btnIzmeniNamirnicu"])){
    $id_namirnice = $_POST["id_namirnice"];
    /*$namirnice = $_SESSION["namirnice"];
    $nam;
    for($i=0;$i<count($namirnice);$i++){
        if($namirnice[$i]->id === $id_namirnice){
            $nam = $namirnice[$i];
            break;
        }
        $nam = null;
    }*/
    $nam = $db->vratiJednuNamirnicu($id_namirnice);
    if($nam===null){
        header("Location: admin_greska.php");
    }
    include "PopUpNamirnicaIzmena.php";
    //header("Location:".$_SERVER["HTTP_REFERER"]);     OVDE NE RADI!!! jer je otvoren pop up
}

//kliknuto na dugme Sacuvaj u formi za izmenu
else if (isset($_POST["btnSacuvajIzmNam"])){
    $slika = null;
    if(isset($_FILES["slika"]) && $_FILES["slika"]["size"]>0 && $_FILES["slika"]["size"]<10000000){
        $putanja = $_FILES["slika"]["tmp_name"];
        $fp = fopen($putanja, 'r');
        $slika = fread($fp, filesize($putanja));
        fclose($fp);
    }
    $n = new Namirnica($_POST["id"], $_POST["naziv"], $_POST["kategorija"], $_POST["kalorije"], $_POST["proteini"],
                        $_POST["masti"], $_POST["ugljeni_hidrati"], $slika);
    if(!$db->azurirajNamirnicu($n)){
        header("Location: admin_greska.php");
    }else{
        header("Location:".$_SERVER["HTTP_REFERER"]);
    }
}

//dugme za brisanje u dinamicki stampanom elementu
else if (isset($_POST["btnObrisiNamirnicu"])){
    $btnYes = "btnNamBrisYes";
    $btnNo = "btnNamBrisNo";
    $id_elementa = $_POST["id_namirnice"];
    $msg = "Da li ste sigurni da zelite da obrisete izabranu namirnicu iz baze?";
    include "../Includes/PopUpYesNo.php";
}
//potvrda prisanja u pop up-u 
else if (isset($_POST["btnNamBrisYes"])){
    $id = $_POST["id_elementa"];
    if(!$db->obrisiNamirnicu($id))
        header("Location: admin_greska.php");
    header("Location: ".$_SERVER["HTTP_REFERER"]);
}
//odustajanje od brisanja
else if (isset($_POST["btnNamBrisNo"])){
    header("Location: ".$_SERVER["HTTP_REFERER"]);
}

//otvaranje liste namirnica
else if (isset($_GET["page"])){
    //provera da li su zadati svi parametri
    if(!isset($_GET["kategorija"])||!isset($_GET["sortiranje"])||!isset($_GET["redosled"])){
        $_SESSION["greska"] = "Nisu zadati svi parametri!";
        header("Location: admin_greska.php");
    }
    
    //provera parametra stranica
    $str = $_GET["page"];
    if(!filter_var($str, FILTER_VALIDATE_INT)){
        $_SESSION["greska"]="Nepostojeci broj stranice!";
        header("Location: admin_greska.php");
    }
    
    //provera paramtra kategorija
    $kategorija=$_GET["kategorija"];
    if($kategorija==="Sve kategorije")
        $kategorija = "%";              //za operator LIKE u sql upit (bilo sta)
    else if (!($kategorija==="Žitarica" || $kategorija==="Testenina" || $kategorija==="Mesnati proizvod" || $kategorija==="Mlečni proizvod"  
        || $kategorija==="Voće" || $kategorija==="Povrće" || $kategorija==="Slatkiš" || $kategorija==="Piće" || $kategorija==="Jelo" 
        || $kategorija==="Ostalo"))
    {
        $_SESSION["greska"] = "Nepostojeca kategorija!";
        header("Location:admin_greska.php");
    }
    //provera parametra nacin_sortiranja
    $sortiranje = $_GET["sortiranje"];
    if($sortiranje!="Kalorije" && $sortiranje!="Proteini" && $sortiranje!="Masti" 
            && $sortiranje!="Ugljeni hidrati" && $sortiranje!="Kategorija")
    {
        $_SESSION["greska"] = "Nepostojeci nacin sortiranja!";
        header("Location:admin_greska.php");
    }
    //provera parametra redosled
    if($_GET["redosled"]=="Rastuce")
        $redosled = "ASC";
    else
        $redosled = "DESC";
    
    //generisanje parametara za LINK u sql naredbi (koji opseg rezultata da prikazuje)
    $nam_po_str = 10;
    $br_rez = $db->vratiBrojRezultata($kategorija);
    if($br_rez == 0){
        echo "<div id='nemaRezultata'>";
            echo"<label>Nema namirnica za trazenu kategoriju</label>";
        echo"</div>";
        return;
    }
    $ukupno_str = ceil($br_rez/$nam_po_str);
    if($str<1)                                              //ovo treba pomeriti vise pri vrhu!!!!!!!
        $str = 1;
    else if($str > $ukupno_str)
        $str = $ukupno_str;
    $l = ($str-1)*$nam_po_str;
    $limit = "LIMIT $l,$nam_po_str";
    
    //stampanje brojeva stranica, tj linkova na stranice
    $redosl = $_GET["redosled"];
    $kat = $_GET["kategorija"];
    $sort = $_GET["sortiranje"];
    echo"<div id='divStranice'>";
        if($str<$ukupno_str)
            echo "<a id='nextPage' href='admin.php?page=". ($str+1) ."&kategorija="."$kat"."&sortiranje="."$sort"."&redosled="."$redosl&mode=lista_namirnica#cont'>Sledeca</a>";
        for($i=1;$i<=$ukupno_str;$i++){
            $curr="";
            if($i==$str)
                $curr="class='currPage'";
            echo("<a "."$curr"."href='admin.php?page=$i&kategorija="."$kat"."&sortiranje="."$sort"."&redosled="."$redosl&mode=lista_namirnica#cont'>$i</a> ");
        }
        if($str>1)
            echo "<a id='prevPage' href='admin.php?page=". ($str-1) ."&kategorija="."$kat"."&sortiranje="."$sort"."&redosled="."$redosl&mode=lista_namirnica#cont'>Prethodna</a>";
    echo"</div>";
    
    $link = "admin.php?page=$str&mode=lista_namirnica&kategorija="."$kat"."&sortiranje="."$sort"."&redosled="."$redosl"."";

    //pribavljanje namirnica
    $namirnice = $db->vratiNamirnice($kategorija, $sortiranje, $redosled, $limit);
    if(!$namirnice)
        header("Location: admin_greska.php");
    //prikaz namirnica iz baze
    echo "<div id='glNamirnice'>";
    for($i=0;$i<count($namirnice);$i++){
        echo "<form id='namirnica' action='$link' method='post'>";  //treba ovaj link zbog prkaza iz adminFunctions.js i da bi se
            echo '<img src="data:image/jpeg;base64,'.base64_encode( $namirnice[$i]->slika ).'"/>'; //na njega vratilo posle izmene
            echo "<div id='prvi'>";
                echo "<label>Naziv:<b> {$namirnice[$i]->naziv}</b></label><br />";
                echo "<label>Kategorija: {$namirnice[$i]->kategorija}</label><br />";
                echo "<label>Kalorije: {$namirnice[$i]->kalorije}</label><br />";
            echo "</div>";
            echo "<div id='drugi'>";
                echo "<label>Proteini: {$namirnice[$i]->proteini}</label><br />";
                echo "<label>Masti: {$namirnice[$i]->masti}</label><br />";
                echo "<label>Ugljeni hidrati: {$namirnice[$i]->ugljeni_hidrati}</label><br />";
            echo "</div>";
            echo "<div id='dugmad'>";
                echo "<input type='hidden' name='id_namirnice' value='{$namirnice[$i]->id}' />";
                echo "<input type='submit' name='btnObrisiNamirnicu' value='Obrisi' />";
                echo "<input type='submit' name='btnIzmeniNamirnicu' value='Izmeni' />";
            echo "</div>";
        echo "</form>";
    }
    echo "</div>";
    
     echo"<div id='divStranice'>";
        if($str<$ukupno_str)
            echo "<a id='nextPage' href='admin.php?page=". ($str+1) ."&kategorija="."$kat"."&sortiranje="."$sort"."&redosled="."$redosl&mode=lista_namirnica#cont'>Sledeca</a>";
        for($i=1;$i<=$ukupno_str;$i++){
            $curr="";
            if($i==$str)
                $curr="class='currPage'";
            echo("<a "."$curr"."href='admin.php?page=$i&kategorija="."$kat"."&sortiranje="."$sort"."&redosled="."$redosl&mode=lista_namirnica#cont'>$i</a> ");
        }
        if($str>1)
            echo "<a id='prevPage' href='admin.php?page=". ($str-1) ."&kategorija="."$kat"."&sortiranje="."$sort"."&redosled="."$redosl&mode=lista_namirnica#cont'>Prethodna</a>";
    echo"</div>";
}

//otvara se odavde pop up za obradu zahteva(klik na dugme (dinamicki element zahtev))
else if (isset($_POST["btnObradiZahtev"])){
    $id = $_POST["id_zahteva"];
    $link = $_POST["link"];
    $zahtev = $db->vratiJedanZahtevNamirnice($id);
    if($zahtev===null)
        header("Location: admin_greska.php");
    include "../Includes/PopUpZahtevNamirnica.php";
}

//kliknuto na dugme za dodavanje zahteva u pop up
else if (isset($_POST["btnZahNamOK"])){
    $odgovor = DodavanjeNamirnice();
    $msg = $odgovor["poruka"];
    if(!$odgovor["greska"]){
        if(!$db->obrisiZahtevNamirnice($_POST["id"])){
            $msg="Zahtev nije obrisan, postoji greska!";
        }else{
            $msg.= "<br />Zahtev obrisan!";
        }
    }
    
    include "../Includes/PopUpPorukaZahtevi.php";
}

//kliknuto na ok u poruci posle dodavanja i povratak na prethodnu stranu
else if (isset($_POST["btnPorukaOk"])){
        header("Location: ".$_SERVER["HTTP_REFERER"]);
}

//kliknuto na obrisi u formi za obradu zahteva
else if (isset($_POST["btnZahNamObrisi"])){
    if(!$db->obrisiZahtevNamirnice($_POST["id"])){
        header("Location: admin_greska.php");
    }else{
        $msg = "Zahtev za namirnicu obrisan.";
        include "../Includes/PopUpPorukaZahtevi.php";
    }
}

//prikaz zahteva korisnika za novu namirnicu
else if(isset($_GET["zn_page"])){
    
    //provera parametra stranica
    $str = $_GET["zn_page"];
    if(!filter_var($str, FILTER_VALIDATE_INT)){
        $_SESSION["greska"]="Nepostojeci broj stranice!";
        header("Location: admin_greska.php");
    }
    
    //generisanje parametara za LINK u sql naredbi (koji opseg rezultata da prikazuje)
    $zah_po_str = 5;
    $br_rez = $db->vratiBrZahtNamirnice();
    if($br_rez == 0){
        echo "<div id='nemaRezultata'>";
            echo"<label>Nema zahteva za namirnice</label>";
        echo"</div>";
        return;
    }
    $ukupno_str = ceil($br_rez/$zah_po_str);
    if($str<1)                                              //ovo treba pomeriti vise pri vrhu!!!!!!!
        $str = 1;
    else if($str > $ukupno_str)
        $str = $ukupno_str;
    $l = ($str-1)*$zah_po_str;
    $limit = "LIMIT $l,$zah_po_str";
    
    //stampanje brojeva stranica, tj linkova na stranice
    echo"<div id='divStranice'>";
       if($str<$ukupno_str)
           echo "<a id='nextPage' href='admin.php?zn_page=". ($str+1) ."&mode=zahtevi_namirnica#cont'>Sledeca</a>";
       for($i=1;$i<=$ukupno_str;$i++){
           $curr="";
           if($i==$str)
               $curr="class='currPage'";
           echo("<a "."$curr"."href='admin.php?zn_page=$i&mode=zahtevi_namirnica#cont'>$i</a> ");
       }
       if($str>1)
            echo "<a id='prevPage' href='admin.php?zn_page=". ($str-1) ."&mode=zahtevi_namirnica#cont'>Prethodna</a>";
    echo"</div>";
    
    $link = "admin.php?zn_page=$str&mode=zahtevi_namirnica";

    //pribavljanje zahteva
    $zahtevi = $db->vratiZahteveZaNamirnice($limit);
    if(!$zahtevi)
        header("Location: admin_greska.php");
    //prikaz zahteva iz baze
    echo "<div id='glZahtevi'>";
    for($i=0;$i<count($zahtevi);$i++){
        echo "<form id='zahtev' action='$link' method='post'>";
            echo "<div id='prvi'>";
                echo "<label>Naziv: <b>{$zahtevi[$i]->naziv}</b></label><br />";
                echo "<label>Kategorija: {$zahtevi[$i]->kategorija}</label><br />";
                echo "<label>Kalorije: {$zahtevi[$i]->kalorije}</label><br />";
            echo "</div>";
            echo "<div id='drugi'>";
                echo "<label>Proteini: {$zahtevi[$i]->proteini}</label><br />";
                echo "<label>Masti: {$zahtevi[$i]->masti}</label><br />";
                echo "<label>Ugljeni hidrati: {$zahtevi[$i]->ugljeni_hidrati}</label><br />";
            echo "</div>";
            echo "<div id='treci'>";
                echo "<label id='id_kor'>ID korisnika: {$zahtevi[$i]->korisnik_id}</label><br />";
                echo "<input type='hidden' name='id_zahteva' value='{$zahtevi[$i]->id}' />";
                echo "<input type='hidden' name='link' value='{$link}' />";
                echo "<input type='submit' class='dugmad' name='btnObradiZahtev' value='Obradi' />";
            echo "</div>";
        echo "</form>";
    }
    echo "</div>";
    
    echo"<div id='divStranice'>";
       if($str<$ukupno_str)
           echo "<a id='nextPage' href='admin.php?zn_page=". ($str+1) ."&mode=zahtevi_namirnica#cont'>Sledeca</a>";
       for($i=1;$i<=$ukupno_str;$i++){
           $curr="";
           if($i==$str)
               $curr="class='currPage'";
           echo("<a "."$curr"."href='admin.php?zn_page=$i&mode=zahtevi_namirnica#cont'>$i</a> ");
       }
       if($str>1)
            echo "<a id='prevPage' href='admin.php?zn_page=". ($str-1) ."&mode=zahtevi_namirnica#cont'>Prethodna</a>";
    echo"</div>";
}


//funkcija za dodavanje na osnovu parametara iz POST niza, izvdojena jer se koristi
//i kod forme za klasicno dodavanje i pri obradi zahteva za dodavanje namirnice
function DodavanjeNamirnice(){
    global $db;
    $odgovor;
    if($_POST["naziv"]!="" && !empty($_POST["kalorije"]) && 
            !empty($_POST["proteini"])&&!empty($_POST["masti"])&&!empty($_POST["ugljeni_hidrati"])){
        
        //uneo i sliku
        if(isset($_FILES["slika"]) && $_FILES["slika"]["size"]>0 && $_FILES["slika"]["size"]<10000000){
            $putanja = $_FILES["slika"]["tmp_name"];
            $fp = fopen($putanja, 'r');
            $slika = fread($fp, filesize($putanja));
            fclose($fp);
        }else{//nije uneo sliku, ucitava se default slika za namirnicu
            $putanja = "../Images/namirnica_bez_slike.png";
            $fp = fopen($putanja, 'r');
            $slika = fread($fp, filesize($putanja));
            fclose($fp);
        }
        //proveri koja se slika unosi kad se ne klikne na dugme za dodavanje slike
        $n = new Namirnica(0,$_POST["naziv"], $_POST["kategorija"], $_POST["kalorije"], 
                $_POST["proteini"], $_POST["masti"], $_POST["ugljeni_hidrati"], $slika);
        
        if($db->dodajNamirnicu($n)){
            $odgovor["greska"] = false;
            $odgovor["poruka"]="Namirnica je dodata u bazu";
        }else{
            $odgovor["greska"]= true;
            $odgovor["poruka"]="Greska UPIT";
        }
    }
    else{
        $odgovor["greska"]= true;
        $odgovor["poruka"]="Molimo vas popunite sva polja!";
    }
    return $odgovor;
}