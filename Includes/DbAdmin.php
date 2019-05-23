<?php

include_once "DbConnect.php";

class DbAdmin {
    private $con;
    private $msg;
    
    public function __construct() {
        //$temp = new DbConnect();
        //$this->con = $temp->connect();
        $this->con = $this->connect();  
    }
    
    //dodata privremeno zbog boljeg intellisense, kasnije obrisati jer se koristi klasa DbConnect
     private function connect() {
        $this->con = new mysqli(db_host, db_user, db_password, db_name);
        
        if($this->con->connect_errno){
            $this->greska("Greska prilikom uspostavljanja konekcije");
            return null;
        }
        $this->con->set_charset('utf8');

        return $this->con;    
    }
    //===============================================================
    function dodajNamirnicu(Namirnica $n){
        if($this->con === null)
            return false;
        $query = "INSERT INTO `namirnica` (`ID`, `NAZIV`, `KATEGORIJA`, `KALORIJE`, `PROTEINI`, `MASTI`, `UGLJENI_HIDRATI`, `SLIKA`) "
                . "VALUES (NULL, ?, ?, ?,?, ?, ?, ?);";
        if($stmt = $this->con->prepare($query)){
            $x= null;
            $stmt->bind_param("ssddddb", $n->naziv,$n->kategorija,$n->kalorije,$n->proteini,$n->masti,$n->ugljeni_hidrati,$x);
            $stmt->send_long_data(6, $n->slika);
            if($stmt->execute()){
                return true;
            }else{
                $this->greska("Greska prilikom izvrsenja upita!");
                return false;
            }
        }else{
            $this->greska("Greska u sintaksi upita!");
            return false;
        }
    }
    
    function azurirajNamirnicu(Namirnica $n){
        if($this->con === null)
            return false;
        
        if($n->slika === null){
            $query = "UPDATE `namirnica` SET NAZIV=?, KATEGORIJA=?, KALORIJE=?, PROTEINI=?, MASTI=?, UGLJENI_HIDRATI=? "
                . "WHERE ID=?";
        }else{
            $query = "UPDATE `namirnica` SET NAZIV=?, KATEGORIJA=?, KALORIJE=?, PROTEINI=?, MASTI=?, UGLJENI_HIDRATI=?, SLIKA=? "
                . "WHERE ID=?";
        }
        
        if($stmt = $this->con->prepare($query)){
            $x= null;
            if($n->slika===null){
                $stmt->bind_param("ssddddi", $n->naziv,$n->kategorija,$n->kalorije,$n->proteini,$n->masti,$n->ugljeni_hidrati,$n->id);
            }else{
                $stmt->bind_param("ssddddbi", $n->naziv,$n->kategorija,$n->kalorije,$n->proteini,$n->masti,$n->ugljeni_hidrati,$x,$n->id);
                $stmt->send_long_data(6, $n->slika);
            }
            
            if($stmt->execute()){
                return true;
            }else{
                $this->greska("Greska prilikom izvrsenja upita!");
                return false;
            }
        }else{
            $this->greska("Greska u sintaksi upita!");
            return false;
        }
    }
            
    function vratiNamirnice($kategorija,$sortiranje,$redosled,$limit){
        if($this->con === null)
            return false;
        $query = "SELECT * FROM `namirnica` WHERE KATEGORIJA LIKE '$kategorija' ORDER BY $sortiranje $redosled  $limit;";
        $rez = $this->con->query($query);
        if($rez){
            $niz = array();
            $i=0;
            while($red = $rez->fetch_assoc()){
                $niz[$i] = new Namirnica($red["ID"], $red["NAZIV"], $red["KATEGORIJA"], $red["KALORIJE"],
                                $red["PROTEINI"], $red["MASTI"], $red["UGLJENI_HIDRATI"], $red["SLIKA"]);
                $i++;
            }
            return $niz;
        }else{
            $this->greska("Greska prilikom izvrsenja upita!");
            return false;
        }
    }
    
    function vratiJednuNamirnicu($id){
        if($this->con === null)
            return null;
        if($stmt = $this->con->prepare("SELECT * FROM `namirnica` WHERE ID = ?;")){
            $stmt->bind_param("i", $id);
            if($stmt->execute()){
                $stmt->bind_result($id,$naziv,$kategorija,$kalorije,$proteini,$masti,$uglj_hid,$slika);
                $stmt->fetch();
                $n = new Namirnica($id, $naziv, $kategorija, $kalorije, $proteini, $masti, $uglj_hid, $slika);
                return $n;
            }else{
                $this->greska("Greska prilikom izvrsenja upita!");
                return null;
            }
        }else{
            $this->greska("Greska u sintaksi upita!");
            return null;
        }
    }
    
    function vratiBrojRezultata($kategorija){
        if($this->con===null)
            return false;
        $query = "SELECT COUNT(*) AS BROJ FROM `namirnica` WHERE KATEGORIJA LIKE '$kategorija'";
        $rez = $this->con->query($query);
        if($rez){
            $red = $rez->fetch_assoc();
            $br = $red["BROJ"];
            return $br;
        }else{
                $this->greska("Greska prilikom izvrsenja upita!");
        }
    }
    
    function obrisiNamirnicu($id){
         if($this->con === null)
            return null;
        if($stmt = $this->con->prepare("DELETE FROM `namirnica` WHERE ID = ?;")){
            $stmt->bind_param("i", $id);
            if($stmt->execute()){
                return true;
            }else{
                $this->greska("Greska prilikom izvrsenja upita!");
                return null;
            }
        }else{
            $this->greska("Greska u sintaksi upita!");
            return null;
        }
    }
    //====================================  AKTIVNOSTI ==========================================
    function dodajAktivnost(Aktivnost $a){
        if($this->con === null)
            return false;
        $query = "INSERT INTO `aktivnost` (`ID`, `NAZIV`, `MET_VREDNOST`, `SLIKA`) "
                . "VALUES (NULL, ?, ?, ?);";
        if($stmt = $this->con->prepare($query)){
            $x= null;
            $stmt->bind_param("sdb", $a->naziv,$a->met_vrednost,$x);
            $stmt->send_long_data(2, $a->slika);
            if($stmt->execute()){
                return true;
            }else{
                $this->greska("Greska prilikom izvrsenja upita!");
                return false;
            }
        }else{
            $this->greska("Greska u sintaksi upita!");
            return false;
        }
    }
    
    function azurirajAktivnost(Aktivnost $a){
        if($this->con === null)
            return false;
        
        if($a->slika === null){
            $query = "UPDATE `aktivnost` SET NAZIV=?, MET_VREDNOST=? WHERE ID=?";
        }else{
            $query = "UPDATE `aktivnost` SET NAZIV=?, MET_VREDNOST=?, SLIKA=? WHERE ID=?";
        }
        
        if($stmt = $this->con->prepare($query)){
            $x= null;
            if($a->slika===null){
                $stmt->bind_param("sdi", $a->naziv, $a->met_vrednost, $a->id);
            }else{
                $stmt->bind_param("sdbi", $a->naziv, $a->met_vrednost, $x, $a->id);
                $stmt->send_long_data(2, $a->slika);
            }
            
            if($stmt->execute()){
                return true;
            }else{
                $this->greska("Greska prilikom izvrsenja upita!");
                return false;
            }
        }else{
            $this->greska("Greska u sintaksi upita!");
            return false;
        }
    }
    
    function vratiAktivnosti($sortiranje,$redosled,$limit){
        if($this->con === null)
            return false;
        $query = "SELECT * FROM `aktivnost` ORDER BY $sortiranje $redosled  $limit;";
        $rez = $this->con->query($query);
        if($rez){
            $niz = array();
            $i=0;
            while($red = $rez->fetch_assoc()){
                $niz[$i] = new Aktivnost($red["ID"], $red["NAZIV"], $red["MET_VREDNOST"], $red["SLIKA"]);
                $i++;
            }
            return $niz;
        }else{
            $this->greska("Greska prilikom izvrsenja upita!");
            return false;
        }
    }
    
    function vratiJednuAktivnost($id){
        if($this->con === null)
            return null;
        if($stmt = $this->con->prepare("SELECT * FROM `aktivnost` WHERE ID = ?;")){
            $stmt->bind_param("i", $id);
            if($stmt->execute()){
                $stmt->bind_result($id,$naziv,$met_vrednost,$slika);
                $stmt->fetch();
                $a = new Aktivnost($id, $naziv, $met_vrednost, $slika);
                return $a;
            }else{
                $this->greska("Greska prilikom izvrsenja upita!");
                return null;
            }
        }else{
            $this->greska("Greska u sintaksi upita!");
            return null;
        }
    }
    
    function vratiBrojAktivnosti(){
        if($this->con===null)
            return false;
        $query = "SELECT COUNT(*) AS BROJ FROM `aktivnost`;";
        $rez = $this->con->query($query);
        if($rez){
            $red = $rez->fetch_assoc();
            $br = $red["BROJ"];
            return $br;
        }else{
                $this->greska("Greska prilikom izvrsenja upita!");
        }
    }
    
    function obrisiAktivnost($id){
         if($this->con === null)
            return null;
        if($stmt = $this->con->prepare("DELETE FROM `aktivnost` WHERE ID = ?;")){
            $stmt->bind_param("i", $id);
            if($stmt->execute()){
                return true;
            }else{
                $this->greska("Greska prilikom izvrsenja upita!");
                return null;
            }
        }else{
            $this->greska("Greska u sintaksi upita!");
            return null;
        }
    }
    /*=================================== ZAHTEVI NAMIRNICE ========================================*/
    function vratiBrZahtNamirnice(){
        if($this->con===null)
            return false;
        $query = "SELECT COUNT(*) AS BROJ FROM `zahtev_za_namirnicu`;";
        $rez = $this->con->query($query);
        if($rez){
            $red = $rez->fetch_assoc();
            $br = $red["BROJ"];
            return $br;
        }else{
                $this->greska("Greska prilikom izvrsenja upita!");
                return false;
        }
    }
    
    function vratiZahteveZaNamirnice($limit){
        if($this->con === null)
            return false;
        $query = "SELECT * FROM `zahtev_za_namirnicu` ORDER BY ID $limit;";
        $rez = $this->con->query($query);
        if($rez){
            $niz = array();
            $i=0;
            while($red = $rez->fetch_assoc()){
                $niz[$i] = new ZahtevNamirnica($red["ID"],$red["KORISNIK_ID"], $red["NAZIV"], $red["KATEGORIJA"], $red["KALORIJE"],
                                $red["PROTEINI"], $red["MASTI"], $red["UGLJENI_HIDRATI"]);
                $i++;
            }
            return $niz;
        }else{
            $this->greska("Greska prilikom izvrsenja upita!");
            return false;
        }
    }
    
    function vratiJedanZahtevNamirnice($id){
        if($this->con === null)
            return null;
        if($stmt = $this->con->prepare("SELECT * FROM `zahtev_za_namirnicu` WHERE ID = ?;")){
            $stmt->bind_param("i", $id);
            if($stmt->execute()){
                $stmt->bind_result($id,$id_korisnika,$naziv,$kategorija,$kalorije,$proteini,$masti,$ugljeni_hidrati);
                $stmt->fetch();
                $z = new ZahtevNamirnica($id,$id_korisnika,$naziv,$kategorija,$kalorije,$proteini,$masti,$ugljeni_hidrati);
                return $z;
            }else{
                $this->greska("Greska prilikom izvrsenja upita!");
                return null;
            }
        }else{
            $this->greska("Greska u sintaksi upita!");
            return null;
        }
    }
    
    function obrisiZahtevNamirnice($id){
         if($this->con === null)
            return null;
        if($stmt = $this->con->prepare("DELETE FROM `zahtev_za_namirnicu` WHERE ID = ?;")){
            $stmt->bind_param("i", $id);
            if($stmt->execute()){
                return true;
            }else{
                $this->greska("Greska prilikom izvrsenja upita!");
                return null;
            }
        }else{
            $this->greska("Greska u sintaksi upita!");
            return null;
        }
    }
        /*=================================== ZAHTEVI AKTIVNOSTI ========================================*/
    function vratiBrZahtAktivnosti(){
        if($this->con===null)
            return false;
        $query = "SELECT COUNT(*) AS BROJ FROM `zahtev_za_aktivnost`;";
        $rez = $this->con->query($query);
        if($rez){
            $red = $rez->fetch_assoc();
            $br = $red["BROJ"];
            return $br;
        }else{
            $this->greska("Greska prilikom izvrsenja upita!");
            return false;
        }
    }
    
    function vratiZahteveZaAktivnosti($limit){
        if($this->con === null)
            return false;
        $query = "SELECT * FROM `zahtev_za_aktivnost` ORDER BY ID $limit;";
        $rez = $this->con->query($query);
        if($rez){
            $niz = array();
            $i=0;
            while($red = $rez->fetch_assoc()){
                $niz[$i] = new ZahtevAktivnost($red["ID"],$red["KORISNIK_ID"], $red["NAZIV"], $red["MET_VREDNOST"]);
                $i++;
            }
            return $niz;
        }else{
            $this->greska("Greska prilikom izvrsenja upita!");
            return false;
        }
    }
    
    function vratiJedanZahtevAktivnosti($id){
        if($this->con === null)
            return null;
        if($stmt = $this->con->prepare("SELECT * FROM `zahtev_za_aktivnost` WHERE ID = ?;")){
            $stmt->bind_param("i", $id);
            if($stmt->execute()){
                $stmt->bind_result($id,$id_korisnika,$naziv,$met_vrednost);
                $stmt->fetch();
                $z = new ZahtevAktivnost($id,$id_korisnika, $naziv, $met_vrednost);
                return $z;
            }else{
                $this->greska("Greska prilikom izvrsenja upita!");
                return null;
            }
        }else{
            $this->greska("Greska u sintaksi upita!");
            return null;
        }
    }
    
    function obrisiZahtevAktivnosti($id){
         if($this->con === null)
            return null;
        if($stmt = $this->con->prepare("DELETE FROM `zahtev_za_aktivnost` WHERE ID = ?;")){
            $stmt->bind_param("i", $id);
            if($stmt->execute()){
                return true;
            }else{
                $this->greska("Greska prilikom izvrsenja upita!");
                return null;
            }
        }else{
            $this->greska("Greska u sintaksi upita!");
            return null;
        }
    }
    
     function greska ($poruka){      //vidi da li moze da se prebaci u fajl DodatneFunkcije (dvoumim se zbog con)
        $this->msg = "<strong>";    //mozda ce treba da se salje i con obj
        $this->msg .= $poruka;
        $this->msg .="</strong><br />";
        $this->msg .= mysqli_error($this->con);
        $_SESSION["greska"] = $this->msg;
    }
    
}
