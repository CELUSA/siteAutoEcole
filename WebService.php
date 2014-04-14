<?php
// PHP variable to store the host address
	$db_host  = "localhost";
// PHP variable to store the username
	$db_uid  = "root";
// PHP variable to store the password
	$db_pass = "";
// PHP variable to store the Database name  
	$db_name  = "auto_ecole_ppe"; 
// PHP variable to store the result of the PHP function 'mysql_connect()' which establishes the PHP & MySQL connection  
	$db_con = mysql_connect($db_host,$db_uid,$db_pass) or die('could not connect');
	
	mysql_select_db($db_name);
	
	$adresseMail = $_POST["adresse_mail"];
	$password = $_POST["password"];
	
	$type = mysql_query("SELECT COUNT(*) AS nbLignes FROM client WHERE adresse_mail LIKE '". $adresseMail. "';");
	while($rowType=mysql_fetch_object($type))
		$output1 = $rowType ->nbLignes;
	
	if($output1 == 1)
	{	
	$sql = "SELECT client.PRENOM_CLIENT, client.NOM_CLIENT, lecon.ID_LECON, lecon.DATE_LECON, lecon.HEURE_LECON, moniteur.PRENOM_MONITEUR, moniteur.NOM_MONITEUR FROM lecon, client, moniteur WHERE lecon.id_client = client.id_client AND client.adresse_mail LIKE '". $adresseMail."' AND client.password LIKE MD5('". $password."') AND lecon.id_moniteur = moniteur.id_moniteur AND DATE_LECON > CURDATE() ORDER BY DATE_LECON ASC";
	}
	if($output1 == 0)
	{
	$sql = "SELECT client.PRENOM_CLIENT, client.NOM_CLIENT, lecon.ID_LECON, lecon.DATE_LECON, lecon.HEURE_LECON, moniteur.PRENOM_MONITEUR, moniteur.NOM_MONITEUR FROM lecon, client, moniteur WHERE lecon.id_client = client.id_client AND moniteur.adresse_mail LIKE '". $adresseMail."' AND moniteur.password LIKE MD5('". $password."') AND lecon.id_moniteur = moniteur.id_moniteur AND DATE_LECON > CURDATE() ORDER BY DATE_LECON ASC";
	}
	
	$result = mysql_query($sql);
	
	while($row=mysql_fetch_assoc($result))
		$output[]=$row;
	print(json_encode($output));
	mysql_close();   
?>