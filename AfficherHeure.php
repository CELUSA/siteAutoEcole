<!DOCTYPE HTML>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Auto-Ecole Driver</title>
<META NAME="description" CONTENT="Auto-Ecole Paris"> 
<META NAME="keywords" CONTENT="Auto, école, conduite,  Paris, Paris 1e ">
        <meta name="robots" content="index,follow" />
        <meta property="og:title" content="Driver, Paris 1er"/>
        <meta property="og:type" content="autoecole" />
        <meta property="og:latitude" content="2.358404"/>
        <meta property="og:longitude" content="48.856022"/>
        <meta property="og:street-address" content="24 rue de Rivoli"/>
        <meta property="og:locality" content="Paris 1ème"/>
        <meta property="og:region" content="Île-de-France"/>
        <meta property="og:postal-code" content="75001"/>
        <meta property="og:country-name" content="France"/>
		
		<!-- Include Main jQuery library for slider -->
        <script type="text/Javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
        <!-- Include Main Easy Slides library & default css file-->
        <script type="text/Javascript" src="jQuery.easySlides/js/jquery.easyslides.min.v1.1.js"></script>
        <link rel="stylesheet" type="text/css" href="jQuery.easySlides/css/easySlides.default.min.css" />
        <!-- Include own Javascript/jQuery & CSS file for Slider -->
        <script type="text/Javascript" src="Examples/Example 1/example_1.js"></script>
        <link rel="stylesheet" type="text/css" href="Examples/Example 1/styles.css" />
		<!-- Include CSS stylesheet for this page -->
        <link rel="stylesheet" type="text/css" href="index.css" />
        
</head>

<header>

<!--header-->
<?php require 'header.html'; ?>

</header>

<body class="page">
<div class="container">


<div class="content">
  
<?php
$password = $_POST["password"];
$adresseMail = $_POST["adresseMail"];
 require "C:\wamp\www\Site_PPE\heureDeCoursEtudiant.php";
 require "C:\wamp\www\Site_PPE\heureDeCoursMoniteur.php";

 
// CONNEXION BASE DE DONNEES

/*if ($password != "" && $adresseMail != "")
{*/

	try
	{
		$autoecole = new PDO('mysql:host=localhost;dbname=auto_ecole_ppe', 'root', '');
		//$autoecole = new PDO('mysql:host=172.20.45.39;dbname=auto_ecole_ppe', 'root', '');
	}
	catch (Exception $e)
	{
        die('Erreur : '.$e->getMessage('Erreur de connexion a la base de données'));
	}
	
// VERIFICATION LOGIN ET MOT DE PASSE
	
	//$verifMdpClient = $autoecole->query("SELECT count(*) as nbligne FROM client WHERE adresse_mail LIKE '".$_POST["adresseMail"]."' AND password LIKE //MD5('". $_POST["password"]."')");
	
	$verifMdpClient = $autoecole->query("SELECT count(*) as nbligne FROM client WHERE adresse_mail LIKE '".$adresseMail."' AND password LIKE MD5('".$password."')");
	
		
	//$reponseClient = mysql_query($verifMdpClient);
	$ligne=$verifMdpClient->fetch();
		if ($ligne['nbligne'] == 1 )
			{
        //echo "GOOD CLIENT !";
		afficherHeureDeCoursEtudiant($_POST['adresseMail']);
			}
		else
			{
				$verifMdpMoniteur = $autoecole->query("SELECT count(*) as nbligne FROM moniteur WHERE adresse_mail LIKE '". $_POST["adresseMail"]."' AND password LIKE MD5('". $_POST["password"]."')");

				$ligne=$verifMdpMoniteur->fetch();
					if ($ligne['nbligne'] == 1 )
						{
					//echo "GOOD MONITEUR !";
					afficherHeureDeCoursMoniteur($_POST['adresseMail']);
						}
					else if($ligne['nbligne'] == 0)
						{
							echo "Mauvaise combinaison adresse mail / mdp.";
						}
			}
/*}*/

?>
<html>
<!-- Bouton Retour Index -->
	<body>
		<form method="post" name="retour" action="http://81.64.83.238:8081/Site_PPE/index.php">
			<input type="submit" value="Accueil">
		</form>
	</body>
</html>
  </div>
  <!-- end .content -->
  
  <!-- end .container --></div>
</body>

<footer>

<!--footer-->
<?php require 'footer.html'; ?>

</footer>
</html>












