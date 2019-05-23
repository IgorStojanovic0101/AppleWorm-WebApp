<?php

include 'DbConnect.php';
include '../Klase/Korisnik.php';
include '../Klase/Namirnica.php';

class DbUser {
   
    private $con;
    private $response=array();
    
    function __construct() {
        $temp = new DbConnect();
        $this->con = $temp->connect();
    }
    
    //vraca true i false samo
     function checkEmail($email){
        if($this->con === null)
            return false;
        
        $stmt = $this->con->prepare("SELECT EMAIL FROM `korisnik` WHERE EMAIL = ?");
        if($stmt){
            $stmt->bind_param("s", $email);
        
            if($stmt->execute()){
                $stmt->store_result();      //baferuje rez. Mora da se napise da bi mogao da se koristi property num_rows!
                if($stmt->num_rows!=0){
               //     $this->response['error']=true;
               //     $this->response['msg'] = "Vec postoji nalog sa unetom email adresom.";//sta je sad mysqli_error?????????????????
                    return false;       //neuspesno, postoji vec ta adresa
                }else
                    return true;        //uspesno
            }else{
              //  $this->greska("Greska prilkom izvrsenja upita:");
                return false;
            }
        }
        else{                  //neuspesno, greska u upitu
           // $this->greska("Greska u sintaksi upita:");
            return false;
        }
    }
   
    //vraca true i false samo
    function checkUsername($username){
        if($this->con === null)
            return false;
        
        $stmt = $this->con->prepare("SELECT USERNAME FROM `korisnik` WHERE USERNAME = ?");
        if($stmt){
            $stmt->bind_param("s", $username);
        
            if($stmt->execute()){
                $stmt->store_result();      //baferuje rez. Mora da se napise da bi mogao da se koristi property num_rows!
                if($stmt->num_rows!=0){
                   // $this->greska("Vec postoji nalog sa unetim korisnickim imenom.");
                    return false;       //neuspesno, postoji vec ta adresa
                }else
                    return true;        //uspesno
            }else{
               // $this->greska("Greska prilkom izvrsenja upita:");
                return false;
            }
        }
        else{                  //neuspesno, greska u upitu
           // $this->greska("Greska u sintaksi upita:");
            return false;
        }
    }
    
    function returnUser($username,$password){
        if($this->con === null)
            return null;
        $pass = md5($password);
        
        $stmt = $this->con->prepare("SELECT * FROM `korisnik` WHERE USERNAME = ? "
                                        . "AND PASS = ?");
        if($stmt){
            $stmt->bind_param("ss",$username,$pass);
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows===1){
                    $stmt->bind_result($id,$mail,$usr,$pass,$starost,$kilaza,$visina);
                    $stmt->fetch();
                    $k = new Korisnik($id, $mail, $usr, $pass, $starost, $kilaza, $visina);
                    return $k;
                }
                else{
                    //header("Location:Greska.php?err='pogresni podaci'");
                    return null;
                }
            } else {
                //header("Location:Greska.php?err='Greska prilkom izvrsenja upita'");
                return null;
            }
        }else{
            //header("Location:Greska.php?err='Greska u sintaksi upita'");
            return null;
        }
    }
    
    function createUser($name,$password,$email,$starost,$kilaza,$visina){
        if($this->con === null)
            return null;
        $pass = md5($password);
        
        $stmt = $this->con->prepare("INSERT INTO `korisnik` (`ID`,`EMAIL`, `USERNAME`, `PASS`, `STAROST`,`KILAZA`,`VISINA`)"
                                    . " VALUES (NULL,?,?,?,?,?,?);");
        if($stmt){
            $stmt->bind_param("sssiii",$email,$name,$pass,$starost,$kilaza,$visina);

            if($stmt->execute()){
                $user = $this->returnUser ($name, $password);   //ne salje se ekriptovani pass(i u register se enkriptuje)
                return $user;
            }else {
                return null;
               // $this->response['error']=true;
               // $this->response['msg']="Greska prilkom izvrsenja upita:";
            }
        }else{
            return null;
            //$this->response['error']=true;
            //$this->response['msg']="Greska u sintaksi upita:";
        }
        
    }
    
    function vratiNamirnice($kategorija,$sortiranje,$redosled,$limit){
        if($this->con === null)
            return false;
        $query = "SELECT ID,NAZIV,KATEGORIJA,KALORIJE,PROTEINI,MASTI,UGLJENI_HIDRATI  "
                . "FROM `namirnica` WHERE KATEGORIJA LIKE '$kategorija' ORDER BY $sortiranje $redosled  $limit;";
        $rez = $this->con->query($query);
        if($rez){
            $niz = array();
            $i=0;
            while($red = $rez->fetch_assoc()){
                $slika = "localhost/AppleWorm/UserFunctions/getImage.php?id={$red['ID']}";
                $niz[$i] = new Namirnica($red["ID"], $red["NAZIV"], $red["KATEGORIJA"], $red["KALORIJE"],
                                $red["PROTEINI"], $red["MASTI"], $red["UGLJENI_HIDRATI"], $slika);
                $i++;
            }
            return $niz;
        }else{
            //$this->greska("Greska prilikom izvrsenja upita!");
            return null;
        }
    }
    
    function vratiJednuNamirnicu($id){
        if($this->con === null)
            return null;
        if($stmt = $this->con->prepare("SELECT ID,NAZIV,KATEGORIJA,KALORIJE,PROTEINI,MASTI,UGLJENI_HIDRATI "
                . "FROM `namirnica` WHERE ID = ?;")){
            $stmt->bind_param("i", $id);
            if($stmt->execute()){
                $stmt->bind_result($id,$naziv,$kategorija,$kalorije,$proteini,$masti,$uglj_hid);
                $stmt->fetch();
                $slika = "localhost/AppleWorm/UserFunctions/getImage.php?id=$id";
                $n = new Namirnica($id, $naziv, $kategorija, $kalorije, $proteini, $masti, $uglj_hid, $slika);
                return $n;
            }else{
               // $this->greska("Greska prilikom izvrsenja upita!");
                return null;
            }
        }else{
            //$this->greska("Greska u sintaksi upita!");
            return null;
        }
    }
    function vratiSlikuNamirnice($id){
        if($this->con === null)
            return null;
        if($stmt = $this->con->prepare("SELECT SLIKA FROM `namirnica` WHERE ID = ?;")){
            $stmt->bind_param("i", $id);
            if($stmt->execute()){
                $stmt->bind_result($slika);
                $stmt->fetch();
                return $slika;
            }else{
                //$this->greska("Greska prilikom izvrsenja upita!");
                return null;
            }
        }else{
            //$this->greska("Greska u sintaksi upita!");
            return null;
        }
    }
}
