<!DOCTYPE html>

<html>
<head>
	<title>AppleWorm</title>
	<meta charset="utf-8"/>
	<link href="../css/glavni.css" type="text/css" rel="stylesheet" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="icon" type="image/png" href="../Images/logo-transparent-24x24.png" />
</head>
<body>
	<header class="mainHeader" >
		
            <img src="../Images/LOGO2-1.png" class="image1"/>
            <img src="../Images/LOGO2-1Mobile.png" class="image2"/>
		
		<nav>		
                    <ul>
                        <?php 
                            $home="";$about="";$contact="";$admins="";
                            $str = $_SERVER["PHP_SELF"];
                            switch ($str){
                                case "/AppleWorm/sajt/home.php":$home='active_htab';break;
                                case "/AppleWorm/sajt/about.php":$about='active_htab';break;
                                case "/AppleWorm/sajt/contact.php":$contact='active_htab';break;
                                default: $admins='active_htab';
                            }
                        ?>
                        <li><a class=" <?php echo($home);?>" href="home.php">Home</a></li>	<!--li ili a da ima klasu???-->
                        <li><a class=" <?php echo($about);?>" href="about.php">About</a></li>
                        <li><a class=" <?php echo($contact);?>" href="contact.php">Contact</a></li>
                        <li><a class=" <?php echo($admins);?>" href="admin_login.php">Admin Part</a></li>
                    </ul>
		</nav>
	</header>