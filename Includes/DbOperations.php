<?php
if(session_status()== PHP_SESSION_NONE){
    session_start();
}

//include_once 'Constants.php';
include 'DbConnect.php';
include '../Includes/DodatneFunkcije.php';


/*Zatvaranje konekcije, destruktor???*/
class DbOperations{
    
    private $con;
    private $msg="";
    
    function __construct() {
        $temp = new DbConnect();
        $this->con = $temp->connect();
    }
    /*
    //konektovanje na bazu
    private function connect() {
        $this->con = new mysqli(db_host, db_user, db_password, db_name);
        
        if($this->con->connect_errno){
            $this->greska("Greska prilikom uspostavljanja konekcije");
            return null;
        }
        return $this->con;    
    }
    */
    
    
    /*provera da li postoji email u bazi, pri sign in procesu*/
    function checkEmail($email){
        if($this->con === null)
            return false;
        
        $stmt = $this->con->prepare("SELECT EMAIL FROM `administrator` WHERE EMAIL = ?");
        if($stmt){
            $stmt->bind_param("s", $email);
        
            if($stmt->execute()){
                $stmt->store_result();      //baferuje rez. Mora da se napise da bi mogao da se koristi property num_rows!
                if($stmt->num_rows!=0){
                    $this->greska("Vec postoji nalog sa unetom email adresom.");//sta je sad mysqli_error?????????????????
                    return false;       //neuspesno, postoji vec ta adresa
                }else
                    return true;        //uspesno
            }else{
                $this->greska("Greska prilkom izvrsenja upita:");
                return false;
            }
        }
        else{                  //neuspesno, greska u upitu
            $this->greska("Greska u sintaksi upita:");
            return false;
        }
    }
    
    /*provera da li postoji username u bazi, pri sign in procesu*/
    function checkUsername($username){
        if($this->con === null)
            return false;
        
        $stmt = $this->con->prepare("SELECT USERNAME FROM `administrator` WHERE USERNAME = ?");
        if($stmt){
            $stmt->bind_param("s", $username);
        
            if($stmt->execute()){
                $stmt->store_result();      //baferuje rez. Mora da se napise da bi mogao da se koristi property num_rows!
                if($stmt->num_rows!=0){
                    $this->greska("Vec postoji nalog sa unetim korisnickim imenom.");
                    return false;       //neuspesno, postoji vec ta adresa
                }else
                    return true;        //uspesno
            }else{
                $this->greska("Greska prilkom izvrsenja upita:");
                return false;
            }
        }
        else{                  //neuspesno, greska u upitu
            $this->greska("Greska u sintaksi upita:");
            return false;
        }
    }
    
    /*kreiranje administratora u bazi*/
    function createAdmin($name,$password,$email){
        if($this->con === null)
            return false;   //vec je definisana geska u sesiji u connect f-ji
        $pass = md5($password);
        
        if(!$this->checkEmail($email)  //provera da li postoji u bazi uneti email
                || !$this->checkUsername($name))//provera da li postoji u bazi uneti username
            return false;
        
        $stmt = $this->con->prepare("INSERT INTO `administrator` (`ID`, `USERNAME`, `PASS`, `EMAIL`, `SLIKA`)"
                                    . " VALUES (NULL, ?, ?, ?,NULL);");
        if($stmt){  //mora i ovde if jer ako ima greske u upitu puca kad se binduju parametri
            $stmt->bind_param("sss",$name,$pass,$email);
            
            if($stmt->execute())
                return true;
            else {
               $this->greska("Greska prilkom izvrsenja upita:");
                return false; 
            }
        }
        $this->greska("Greska u sintaksi upita:");
        return false;
    }
    
    function updateAdmin(Administrator $a){
        if($this->con === null)
            return null;
        $stmt = $this->con->prepare("UPDATE `administrator` SET "
                . "USERNAME=?, PASS=?, SLIKA=? WHERE ID=?;");
        if($stmt){
            $null=null;
            $stmt->bind_param("ssbi", $a->username,$a->password,$null,$a->id);
            $stmt->send_long_data(2, $a->image);
            if($stmt->execute()){
                return true;
            }else{
                $this->greska("Greska prilkom izvrsenja upita:");
                return false;
            }
        }
        else{
            $this->greska("Greska u sintaksi upita.");
        }
    }
    
    
    /*logovanje, provera da li odgovaraju uneti parametri*/
    /*vraca obj administrator iz kog na admin str. uzimam potrebne elemente za prikaz*/
    function logIn($username,$password){
        if($this->con === null)
            return null;
        $pass = md5($password);
        
        $stmt = $this->con->prepare("SELECT * FROM `administrator` WHERE USERNAME = ? "
                                        . "AND PASS = ?");
        if($stmt){
            $stmt->bind_param("ss",$username,$pass);
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows===1){
                    $stmt->bind_result($id,$usr,$pass,$mail,$img);
                    $stmt->fetch();
                    $a = new Administrator($id, $usr, $pass, $mail,$img);
                    return $a;
                }
                else{
                    $this->greska ("Uneti podaci su pogresni.");
                    return null;
                }
            } else {
                $this->greska("Greska prilkom izvrsenja upita:");
                return null;
            }
        }else{
            $this->greska("Greska u sintaksi upita:");
            return null;
        }
    }
    
    //provera da li postoji mail i ako postoji vraca se username
    function mailExists($email){
        
        $stmt = $this->con->prepare("SELECT USERNAME FROM `administrator` WHERE EMAIL = ?");
        if($stmt){
            $stmt->bind_param("s", $email);
        
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows==0){         //neuspesno
                    $this->greska("Ne postoji korisnik sa zadatom email adresom.");
                    return false;
                }
                $stmt->bind_result($usr);
                $stmt->fetch();
                return $usr;        //uspesno, vraca korisnicko ime
            }else{
                $this->greska("Greska prilkom izvrsenja upita:");
                return false;
            }
        }
        else{                  //neuspesno, greska u upitu
            $this->greska("Greska u sintaksi upita:");
            return false;
        }
    }
    
    function resetPass($email){
        $pass="";
        if(($user = $this->mailExists($email))){
            $pass = GeneratePass();
            $pwd = md5($pass);
            $rez = $this->con->query("UPDATE `administrator` SET PASS='$pwd' WHERE USERNAME='$user';");
            if($rez)
                return new Administrator(0,$user,$pass,$email,null);
            else {
                $this->greska("Greska prilikom izvrsenja upita.");
                return null;
            }
        }
        return null;
    }
    
    function greska ($poruka){      //vidi da li moze da se prebaci u fajl DodatneFunkcije (dvoumim se zbog con)
        $this->msg = "<strong>";    //mozda ce treba da se salje i con obj
        $this->msg .= $poruka;
        $this->msg .="</strong><br />";
        $this->msg .= mysqli_error($this->con); 
        $_SESSION["greska"] = $this->msg;
    }
}


/*f-ja greska
 * pomeri van klase da moze da se koristi i u fajlovima koji inkluduju ovaj bez objekta
/razmotri da li moze, jer ova je za greske u bazi, ima mysqli... 
/mozda moze da se napravi druga za obicne greske...
  */