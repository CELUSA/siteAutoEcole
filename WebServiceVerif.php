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
	
	$sql = "SELECT COUNT(*) FROM client WHERE client.adresse_mail LIKE '". $_POST["adresse_mail"]."'";
	
	$result = mysql_query($sql);
	
	while($row=mysql_fetch_assoc($result))
		$output[]=$row;
	print(json_encode($output));
	mysql_close();   
?>