<?php
session_start();
include("../Includes/DbOperations.php");
//session_start(); sesija se startuje u DbOperations.php koji je include-ovan

if(isset($_SESSION["admin"]))
    header("Location: admin.php");



/*Sign in procedura*/
//isset je da li postoje u POST, a empty da li su popunjena polja
if(isset($_POST["subSignin"])){
    if(!empty($_POST["username"])&& !empty($_POST["password"])&& !empty($_POST["email"])){
        $db = new DbOperations();
        if($db->createAdmin($_POST["username"],$_POST["password"],$_POST["email"])){//sesija o gresci se popunjava u create
            $a = new Administrator(null, $_POST["username"], $_POST["password"], $_POST["email"], null);
            $_SESSION["admin"]= $a;
            header("Location: admin_uspesno.php");
        }else{
            header("Location: admin_greska.php");
        }
    }
    else{
        $_SESSION["greska"]="Molimo vas popunite sva polja!";
        header("Location: admin_greska.php");
    }
}

/*Log in procedura*/
if(isset($_POST["subLogin"])){
    if(!empty($_POST["username"]) && !empty($_POST["password"])){
        $db = new DbOperations();
        $a;
        if(($a = $db->logIn($_POST["username"], $_POST["password"]))!=null){
            $_SESSION["admin"] = $a;
            header("Location: admin.php");
        } else {
            header("Location: admin_greska.php");
        }
    }else{
        $_SESSION["greska"]="Molimo vas popunite sva polja!";
        header("Location: admin_greska.php");
    }
}
/*Treba mi sesija koja ce da na pocetku prverava da li je ima aktivnog elementa korisnik u nizu sesija. Ako ima
  odma redirektuje(header) na admin stranicu(nema log-in), ako nema, stampanje... */



include("header.php");
?>
<main>
    <article class="mainContent">
            <header class="subHeader">
                <link href='../css/login.css' type='text/css' rel='stylesheet' />
                    
                <ul>
                    <li><a class="tab activeTab" id="aLogin" href="#login">Log in</a></li>
                    <li><a class="tab" id="aSignin" href="#signin">Sign in</a></li>
                </ul>
            </header>
        
            <content>    <!--Crtaju se obe forme, ali se preko js-a i css-a dinamicki menja prikaz-->
                <form id="fLogin" method="post" action="admin_login.php">
                    <label>Username: </label>
                    <input type='text' name='username'> <br />
                    <label>Password:</label> 
                    <input type='password' name='password'> <br />
                    <input class="btnSubmit" type='submit' name='subLogin' value="LOGIN"> <br />
                    <a href="admin_forgot.php">Zaboravili ste password?</a>
                </form>
                <form id="fSignin" method="post" action="admin_login.php">
                    <label>Username: </label> 
                    <input type='text' name='username'> <br />
                    <label>Password:</label>
                    <input type='password' name='password'> <br />
                    <label>Email:</label>
                    <input type='text' name='email'> <br />
                    <input class="btnSubmit" type='submit' name='subSignin' value="SIGN IN"> <br />
                </form>
            </content>
    </article>
    <!--
    <article>
    </article>
    -->
    <script src='../Includes/tabovi.js'></script>
</main>

<?php include("footer.php");?>