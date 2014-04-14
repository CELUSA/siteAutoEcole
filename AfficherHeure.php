<?php
$password = $_POST["password"];
$adresseMail = $_POST["adresseMail"];
 require "C:\wamp\www\Dev_pour_PPE\heureDeCoursEtudiant.php";
 require "C:\wamp\www\Dev_pour_PPE\heureDeCoursMoniteur.php";
 //require "C:\wamp\www\Site_PPE\Formulaire_connexion.php";
 
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
		<form method="post" name="retour" action="http://localhost:8080/Site_PPE/Index.html">
			<input type="submit" value="Accueil">
		</form>
	</body>
</html>