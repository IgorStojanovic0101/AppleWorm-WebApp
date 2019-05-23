<?php
include "../Klase/ZahtevAktivnost.php";

$db = new DbAdmin();

//klik na dugme u formi za dodavanje aktivnosti
if (isset($_POST["btnDodajAktivnost"])){
    $odgovor = DodavanjeAktivnosti();
    $msg = $odgovor["poruka"];
    include "../Includes/PopUp.php";
}

//klik na dugme Izmeni u dinamicki stampanoj listi
else if(isset($_POST["btnIzmeniAktivnost"])){
    $id_aktivnosti = $_POST["id_aktivnosti"];
    $aktivnost = $db->vratiJednuAktivnost($id_aktivnosti);
    if($aktivnost===null){
        header("Location: admin_greska.php");
    }
    include '../Includes/PopUpAktivnostIzmena.php';
}


//dugme za brisanje u dinamicki stampanom elementu
else if(isset($_POST["btnObrisiAktivnost"])){
    $btnYes = "btnAktBrisYes";
    $btnNo = "btnAktBrisNo";
    $id_elementa = $_POST["id_aktivnosti"];
    $msg = "Da li ste sigurni da zelite da obrisete izabranu aktivnost iz baze?";
    include "../Includes/PopUpYesNo.php";
}
//potvrda prisanja u pop up-u 
else if (isset($_POST["btnAktBrisYes"])){
    $id = $_POST["id_elementa"];
    if(!$db->obrisiAktivnost($id))
        header("Location: admin_greska.php");
    header("Location: ".$_SERVER["HTTP_REFERER"]);
}
//odustajanje od brisanja
else if (isset($_POST["btnAktBrisNo"])){
    header("Location: ".$_SERVER["HTTP_REFERER"]);
}
//klik na dugme Sacuvaj u formi za izmenu
else if (isset($_POST["btnSacuvajIzmAkt"])){
    $slika = null;
    if(isset($_FILES["slika"]) && $_FILES["slika"]["size"]>0 && $_FILES["slika"]["size"]<10000000){
        $putanja = $_FILES["slika"]["tmp_name"];
        $fp = fopen($putanja, 'r');
        $slika = fread($fp, filesize($putanja));
        fclose($fp);
    }
    
    $a = new Aktivnost($_POST["id"], $_POST["naziv"], $_POST["met_vrednost"], $slika);
    
    if(!$db->azurirajAktivnost($a)){
        header("Location: admin_greska.php");
    }else{
        header("Location:".$_SERVER["HTTP_REFERER"]);
    }
}

//otvorena stranica ili klikom na dugme prikazi ili na link(br stranice)
else if(isset($_GET["akt_page"])){
    
    $str = $_GET["akt_page"];
    if(!filter_var($str, FILTER_VALIDATE_INT)){
        $_SESSION["greska"]="Nepostojeci broj stranice!";
        header("Location: admin_greska.php");
    }
    
    //provera da li su uneti svi parametri (kad se link rucno unosi)
    if(!isset($_GET["sortiranje"]) && !isset($_GET["redosled"])){
        $_SESSION["greska"] = "Nisu zadati svi parametri!";
        header("Location: admin_greska.php");
    }
    
    
    //provera zbog moguceg rucnog kucanja               //NE RADI!! !!!!!! !!!!!!!!
    $sort = $_GET["sortiranje"];
    if($sort==="Naziv"){
        $sortiranje = "NAZIV";
    }else if($sort==="MET vrednost"){
        $sortiranje = "MET_VREDNOST";
    }else{
        $_SESSION["greska"]="Ne postojeci nacin sortiranja!";
        header("Location: admin_greska.php");
    }
    if($sort!="MET vrednost" && $sort!="Naziv"){
        $_SESSION["greska"]="Ne postojeci nacin sortiranja!";
        header("Location: admin_greska.php");
    }
    
    //provera zbog moguceg rucnog kucanja
    $redosl = $_GET["redosled"];
    if($redosl==="Rastuce")
        $redosled="ASC";
    else if($redosl==="Opadajuce")
        $redosled="DESC";
    else{
        $_SESSION["greska"]="Ne postojeci redosled sortiranja!";
        header("Location: admin_greska.php");
    }
    
    $broj = $db->vratiBrojAktivnosti();
    if($broj == 0){
        echo "<div id='nemaRezultata'>";
            echo"<label>Nema aktivnosti za prikaz</label>";
        echo"</div>";
        return;
    }
    $el_po_str = 10;
    $ukupno_str = ceil($broj/$el_po_str);
    if($str<1)                                              //ovo treba pomeriti vise pri vrhu!!!!!!!
        $str = 1;
    else if($str > $ukupno_str)
        $str = $ukupno_str;
    $l = ($str-1)*$el_po_str;
    $limit = "LIMIT $l, $el_po_str";
    
    if(!($aktivnosti = $db->vratiAktivnosti($sortiranje, $redosled, $limit))){
        header("Location: admin_greska.php");
    }
    
    $link = "admin.php?akt_page=$str&sortiranje=$sort&redosled=$redosl&mode=lista_aktivnosti";
    echo"<div id='divStranice'>";
       if($str<$ukupno_str)
           echo "<a id='nextPage' href='admin.php?akt_page=". ($str+1) ."&mode=lista_aktivnosti&sortiranje=".$sort."&redosled=".$redosl."&mode=lista_aktivnosti#cont'>Sledeca</a>";
       for($i=1;$i<=$ukupno_str;$i++){
           $curr="";
           if($i==$str)
               $curr="class='currPage'";
           echo("<a "."$curr"."href='admin.php?akt_page=$i&mode=lista_aktivnosti&sortiranje=".$sort."&redosled=".$redosl."&mode=lista_aktivnosti#cont'>$i</a> ");
       }
       if($str>1)
            echo "<a id='prevPage' href='admin.php?akt_page=". ($str-1) ."&mode=lista_aktivnosti&sortiranje=".$sort."&redosled=".$redosl."&mode=lista_aktivnosti#cont'>Prethodna</a>";
    echo"</div>";
    
    echo "<div id='glAktivnosti'>";
    for($i=0;$i<count($aktivnosti);$i++){
        echo "<form id='aktivnost' action='$link' method='post'>";  //treba ovaj link zbog prkaza iz adminFunctions.js
            echo '<img src="data:image/jpeg;base64,'.base64_encode( $aktivnosti[$i]->slika ).'"/>';
            echo "<div id='aktivnost_labele'>";
                echo "<b><label>Naziv: </label> {$aktivnosti[$i]->naziv}</b><br />";
                echo "<label>MET vrednost: </label>{$aktivnosti[$i]->met_vrednost}";
            echo "</div>";
            echo "<div id='dugmad'>";
                echo "<input type='hidden' name='id_aktivnosti' value='{$aktivnosti[$i]->id}' />";
                echo "<input type='submit' name='btnObrisiAktivnost' value='Obrisi' />";
                echo "<input type='submit' name='btnIzmeniAktivnost' value='Izmeni' />";
                echo "</div>";
        echo "</form>";
    }
    echo "</div>";
    echo"<div id='divStranice'>";
       if($str<$ukupno_str)
           echo "<a id='nextPage' href='admin.php?akt_page=". ($str+1) ."&mode=lista_aktivnosti&sortiranje=".$sort."&redosled=".$redosl."&mode=lista_aktivnosti#cont'>Sledeca</a>";
       for($i=1;$i<=$ukupno_str;$i++){
           $curr="";
           if($i==$str)
               $curr="class='currPage'";
           echo("<a "."$curr"."href='admin.php?akt_page=$i&mode=lista_aktivnosti&sortiranje=".$sort."&redosled=".$redosl."&mode=lista_aktivnosti#cont'>$i</a> ");
       }
       if($str>1)
            echo "<a id='prevPage' href='admin.php?akt_page=". ($str-1) ."&mode=lista_aktivnosti&sortiranje=".$sort."&redosled=".$redosl."&mode=lista_aktivnosti#cont'>Prethodna</a>";
    echo"</div>";
}

//klik na dugme iz dinamicki stampanog elementa zahteva
else if (isset($_POST["btnObradiZahtevAktivnost"])){
    $id = $_POST["id_zahteva"];
    $link = $_POST["link"];
    $zahtev = $db->vratiJedanZahtevAktivnosti($id);
    if($zahtev===null)
        header("Location: admin_greska.php");
    include "../Includes/PopUpZahtevAktivnost.php";
}

//klik na dugme Dodaj pri obradi zahteva za namirnicom
else if (isset($_POST["btnZahAktOK"])){
    $odgovor = DodavanjeAktivnosti();
    $msg = $odgovor["poruka"];
    if(!$odgovor["greska"]){    //ako nema greske ulazi u if
        if(!$db->obrisiZahtevAktivnosti($_POST["id"])){
            $msg="Zahtev nije obrisan, postoji greska!";
        }else{
            $msg.= "<br />Zahtev obrisan!";
        }
    }
    include "../Includes/PopUpPorukaZahtevi.php";
}

//klik na dugme obrisi u pop up za obradu zahteva za namirnicom
else if (isset($_POST["btnZahAktObrisi"])){
    if(!$db->obrisiZahtevAktivnosti($_POST["id"])){
        header("Location: admin_greska.php");
    }else{
        $msg = "Zahtev za namirnicu obrisan.";
        include "../Includes/PopUpPorukaZahtevi.php";
    }
}

//prikaz liste zahteva za aktivnosti
else if(isset($_GET["za_page"])){
    
    //provera parametra stranica
    $str = $_GET["za_page"];
    if(!filter_var($str, FILTER_VALIDATE_INT)){
        $_SESSION["greska"]="Nepostojeci broj stranice!";
        header("Location: admin_greska.php");
    }
    //generisanje parametara za LINK u sql naredbi (koji opseg rezultata da prikazuje)
    $zah_po_str = 5;
    $br_rez = $db->vratiBrZahtAktivnosti();
    if($br_rez == 0){
        echo "<div id='nemaRezultata'>";
            echo"<label>Nema zahteva za aktivnosti</label>";
        echo"</div>";
        return;
    }
    $ukupno_str = ceil($br_rez/$zah_po_str);
    if($str<1){                                            //ovo treba pomeriti vise pri vrhu!!!!!!!
        $str = 1;
    }else if($str > $ukupno_str){
        $str = $ukupno_str;
    }
    $l = ($str-1)*$zah_po_str;
    $limit = "LIMIT $l,$zah_po_str";
    
    //stampanje brojeva stranica, tj linkova na stranice
    echo"<div id='divStranice'>";
       if($str<$ukupno_str)
           echo "<a id='nextPage' href='admin.php?za_page=". ($str+1) ."&mode=zahtevi_aktivnost#cont'>Sledeca</a>";
       for($i=1;$i<=$ukupno_str;$i++){
           $curr="";
           if($i==$str)
               $curr="class='currPage'";
           echo("<a "."$curr"."href='admin.php?za_page=$i&mode=zahtevi_aktivnost#cont'>$i</a> ");
       }
       if($str>1)
            echo "<a id='prevPage' href='admin.php?za_page=". ($str-1) ."&mode=zahtevi_aktivnost#cont'>Prethodna</a>";
    echo"</div>";
    $link = "admin.php?za_page=$str&mode=zahtevi_aktivnost";

    //pribavljanje zahteva
    $zahtevi = $db->vratiZahteveZaAktivnosti($limit);
    if(!$zahtevi)
        header("Location: admin_greska.php");
    //prikaz zahteva iz baze
    echo "<div id='glZahtevi'>";
    for($i=0;$i<count($zahtevi);$i++){
        echo "<form id='zahtev' action='$link' method='post'>";
            echo "<div id='prvi'>";
                echo "<label>Naziv: <b>{$zahtevi[$i]->naziv}</b></label><br />";
                echo "<label>MET vrednost: </label>{$zahtevi[$i]->met_vrednost}";
            echo "</div>";
            echo "<div id='treci'>";
                echo "<label id='id_kor'>ID korisnika: {$zahtevi[$i]->korisnik_id}</label><br />";
                echo "<input type='hidden' name='id_zahteva' value='{$zahtevi[$i]->id}' />";
                echo "<input type='hidden' name='link' value='$link' />";
                echo "<input type='submit' class='dugmad' name='btnObradiZahtevAktivnost' value='Obradi' />";
            echo "</div>";
        echo "</form>";
    }
    echo "</div>";
    
    echo"<div id='divStranice'>";
       if($str<$ukupno_str)
           echo "<a id='nextPage' href='admin.php?za_page=". ($str+1) ."&mode=zahtevi_aktivnost#cont'>Sledeca</a>";
       for($i=1;$i<=$ukupno_str;$i++){
           $curr="";
           if($i==$str)
               $curr="class='currPage'";
           echo("<a "."$curr"."href='admin.php?za_page=$i&mode=zahtevi_aktivnost#cont'>$i</a> ");
       }
       if($str>1)
            echo "<a id='prevPage' href='admin.php?za_page=". ($str-1) ."&mode=zahtevi_aktivnost#cont'>Prethodna</a>";
    echo"</div>";
}

function DodavanjeAktivnosti(){
    $odgovor;
    if(!empty($_POST["naziv"]) && !empty($_POST["met_vrednost"])){
        $slika=null;
        if(isset($_FILES["slika"]) && $_FILES["slika"]["size"]>0 && $_FILES["slika"]["size"]<10000000){
            $put = $_FILES["slika"]["tmp_name"];
            $fp = fopen($put, 'r');
            $slika = fread($fp, filesize($put));
            fclose($fp);
        }else{
            $put = "../Images/aktivnost_default.png";
            $fp = fopen($put, 'r');
            $slika = fread($fp, filesize($put));
            fclose($fp);
        }
        $akt = new Aktivnost(0, $_POST["naziv"], $_POST["met_vrednost"], $slika);
        $ad = new DbAdmin();
        if($ad->dodajAktivnost($akt)){
            $odgovor["greska"]=false;
            $odgovor["poruka"]="Aktivnost je dodata u bazu.";
        }else{
            $odgovor["greska"]=false;
            $odgovor["poruka"]="Greska u upitu pri dodavanju aktivnosti u bazu!";
        }
    }else{
        $odgovor["greska"]=false;
        $odgovor["poruka"]="Molimo vas popunite sva polja!";
    }
    return $odgovor;
}